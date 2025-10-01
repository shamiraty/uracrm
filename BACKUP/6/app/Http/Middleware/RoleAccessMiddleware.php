<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UnauthorizedAccess;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RoleAccessMiddleware
{
    /**
     * Route role mapping cache to avoid reading file multiple times
     */
    private static $routeRoleMapping = null;

    /**
     * Get route role mapping by parsing comments from web.php
     */
    private function getRouteRoleMapping()
    {
        if (self::$routeRoleMapping !== null) {
            return self::$routeRoleMapping;
        }

        $webRoutesPath = base_path('routes/web.php');
        $content = file_get_contents($webRoutesPath);

        self::$routeRoleMapping = [];

        // Parse the file to extract roles from comments
        $lines = explode("\n", $content);
        $currentRoles = [];

        for ($i = 0; $i < count($lines); $i++) {
            $line = trim($lines[$i]);

            // Check if line contains roles in comment format
            if (preg_match('/^\/\/(.+)$/', $line, $matches)) {
                $rolesLine = trim($matches[1]);

                // Skip comment lines that are clearly not role definitions
                if (strpos($rolesLine, '====') !== false ||
                    strpos($rolesLine, 'ROUTES') !== false ||
                    strpos($rolesLine, 'END') !== false ||
                    strpos($rolesLine, '---') !== false) {
                    $currentRoles = []; // Reset roles on section headers
                    continue;
                }

                // Check if this is a roles definition line (contains role names)
                $possibleRoles = ['admin', 'accountant', 'loanofficer', 'Registrar', 'superadmin', 'system_admin',
                                'public_relation_officer', 'registrar_hq', 'representative', 'general_manager', 'branch_manager'];

                $foundRoles = [];

                // Look for each role in the line, being careful about partial matches
                foreach ($possibleRoles as $role) {
                    // Use word boundaries to avoid partial matches
                    if (preg_match('/\b' . preg_quote($role, '/') . '\b/', $rolesLine)) {
                        $foundRoles[] = $role;
                    }
                }

                // If we found roles, add them to current roles (accumulative)
                if (!empty($foundRoles) && !preg_match('/Route::|function|class|use /', $rolesLine)) {
                    $currentRoles = array_unique(array_merge($currentRoles, $foundRoles));
                }
            }

            // Check if line contains a route definition with name
            if (preg_match('/->name\([\'"]([^\'"]+)[\'"]\)/', $line, $matches)) {
                $routeName = $matches[1];
                if (!empty($currentRoles)) {
                    self::$routeRoleMapping[$routeName] = $currentRoles;
                }
                // Don't reset currentRoles here to allow multiple routes to share same roles
            }

            // Reset roles when we hit a new comment block or route group
            if (strpos($line, '// ====') !== false || strpos($line, 'Route::middleware') !== false) {
                $currentRoles = [];
            }

            // Also reset when we encounter a blank line followed by another comment
            if (empty($line) && $i + 1 < count($lines)) {
                $nextLine = trim($lines[$i + 1]);
                if (preg_match('/^\/\/[^=]/', $nextLine)) {
                    $currentRoles = [];
                }
            }
        }

        return self::$routeRoleMapping;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        $routeName = $request->route()->getName();
        $routeRoleMapping = $this->getRouteRoleMapping();

        // Skip role checking for routes not in our mapping or public routes
        if (!isset($routeRoleMapping[$routeName])) {
            return $next($request);
        }

        $requiredRoles = $routeRoleMapping[$routeName];
        $userRole = $user->roles->first()->name ?? null;

        // Check if user has required role
        if (!in_array($userRole, $requiredRoles)) {
            // Log unauthorized access attempt
            UnauthorizedAccess::logAttempt(
                $user,
                $routeName,
                $request->fullUrl(),
                $request->method(),
                $requiredRoles
            );

            // Send immediate SMS alert for every unauthorized access attempt
            $this->sendImmediateAlert($user, $routeName, $request->fullUrl());

            // Check for frequent attempts and alert superadmins with escalated message
            $this->checkAndAlertFrequentAttempts($user);

            // Return secure backend response without template exposure
            return $this->handleUnauthorizedAccess($request, [
                'user_role' => $userRole,
                'required_roles' => $requiredRoles,
                'attempted_route' => $routeName,
                'attempted_url' => $request->fullUrl(),
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);
        }

        return $next($request);
    }

    /**
     * Handle unauthorized access with secure backend response
     */
    private function handleUnauthorizedAccess(Request $request, array $data)
    {
        // Check if this is an AJAX request or API call
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Insufficient permissions.',
                'error_code' => 'UNAUTHORIZED_ACCESS',
                'timestamp' => now()->toISOString(),
                'request_id' => uniqid('REQ_')
            ], 403);
        }

        // For regular web requests, return a secure response without exposing system details
        $message = "Access denied. You don't have permission to access this resource.";

        // Determine appropriate action based on request type
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE')) {
            // For form submissions, return back with error
            return back()->withErrors([
                'access_denied' => $message
            ])->withInput();
        }

        // For GET requests, set security token and redirect to unauthorized page
        if ($request->route()->getName() !== 'dashboard') {
            // Set security tokens for authorized access to unauthorized page
            session([
                'unauthorized_access_token' => uniqid('unauth_', true),
                'unauthorized_timestamp' => now()->timestamp
            ]);

            return redirect()->route('unauthorized.access');
        }

        // If already on dashboard, return a 403 response with minimal info
        return response($this->getSecureErrorHtml($message), 403)
            ->header('Content-Type', 'text/html')
            ->header('X-Frame-Options', 'DENY')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Referrer-Policy', 'no-referrer')
            ->header('X-XSS-Protection', '1; mode=block');
    }

    /**
     * Generate secure error HTML without template exposure
     */
    private function getSecureErrorHtml($message)
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #17479E;
            margin: 0;
            padding: 50px;
            text-align: center;
        }
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        .error-code {
            font-size: 48px;
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .error-message {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .security-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #856404;
        }
        .btn {
            background: #17479E;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .btn:hover {
            background: #0F3678;
            color: white;
            text-decoration: none;
        }
        .timestamp {
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <div class="error-message">' . htmlspecialchars($message) . '</div>
        <div class="security-notice">
            <strong>Security Notice:</strong> This access attempt has been logged and monitored.
        </div>
        <a href="/" class="btn">Go to Dashboard</a>
        <div class="timestamp">Generated: ' . date('Y-m-d H:i:s T') . '</div>
    </div>
</body>
</html>';
    }

    /**
     * Send immediate SMS alert for EVERY unauthorized access attempt
     */
    private function sendImmediateAlert($user, $routeName, $attemptedUrl)
    {
        // Get superadmin users
        $superAdmins = User::whereHas('roles', function($query) {
            $query->where('name', 'superadmin');
        })->get();

        $regionName = $user->region ? $user->region->name : 'N/A';
        $districtName = $user->district ? $user->district->name : 'N/A';
        $roleName = $user->roles->first() ? $user->roles->first()->name : 'N/A';

        $message = "SECURITY BREACH: {$user->name} ({$roleName}) from {$regionName}, {$districtName} tried accessing: {$routeName}. Time: " . now()->format('d/m/Y H:i:s');

        foreach ($superAdmins as $admin) {
            if ($admin->phone_number) {
                $this->sendSMS($admin->phone_number, $message);
            }
        }

        // Log the alert
        Log::warning("Immediate security alert sent for unauthorized access", [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'route' => $routeName,
            'url' => $attemptedUrl,
            'superadmins_notified' => $superAdmins->count(),
            'alert_type' => 'immediate'
        ]);
    }

    /**
     * Check for frequent unauthorized attempts and alert superadmins with escalated message
     */
    private function checkAndAlertFrequentAttempts($user)
    {
        // Check if user has made 3+ unauthorized attempts in last 10 minutes
        if (UnauthorizedAccess::hasFrequentAttempts($user->id, 10, 3)) {
            // Prevent spam by checking if we already sent alert recently
            $cacheKey = "frequent_attempts_alert_{$user->id}";

            if (!Cache::has($cacheKey)) {
                // Set cache for 30 minutes to prevent spam
                Cache::put($cacheKey, true, 30);

                // Send SMS to superadmins
                $this->alertSuperAdmins($user);
            }
        }
    }

    /**
     * Send SMS alert to superadmins about frequent unauthorized attempts
     */
    private function alertSuperAdmins($user)
    {
        // Get superadmin users
        $superAdmins = User::whereHas('roles', function($query) {
            $query->where('name', 'superadmin');
        })->get();

        $regionName = $user->region ? $user->region->name : 'N/A';
        $districtName = $user->district ? $user->district->name : 'N/A';
        $roleName = $user->roles->first() ? $user->roles->first()->name : 'N/A';

        $message = "ESCALATED ALERT: User {$user->name} ({$roleName}) from {$regionName}, {$districtName} has made 3+ REPEATED unauthorized access attempts! URGENT ACTION REQUIRED. Time: " . now()->format('d/m/Y H:i:s');

        foreach ($superAdmins as $admin) {
            if ($admin->phone_number) {
                $this->sendSMS($admin->phone_number, $message);
            }
        }
    }

    /**
     * Send SMS using the existing API from AuthenticatedSessionController
     */
    private function sendSMS($phone, $message)
    {
        $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
        $apiKey = 'xYz123#';

        $client = new Client();
        try {
            $response = $client->request('POST', $url, [
                'verify' => false,
                'form_params' => [
                    'msisdn' => $phone,
                    'message' => $message,
                    'key' => $apiKey,
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            Log::info("Security alert SMS sent: " . $responseBody);
            return $responseBody;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            Log::error("Failed to send security alert SMS: " . $e->getMessage());
            return null;
        }
    }
}
