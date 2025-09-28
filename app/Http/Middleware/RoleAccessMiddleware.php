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

            // Check for frequent attempts and alert superadmins
            $this->checkAndAlertFrequentAttempts($user);

            // Redirect to custom unauthorized page with role information
            return redirect()->route('unauthorized.access')
                ->with('unauthorized_data', [
                    'user_role' => $userRole,
                    'required_roles' => $requiredRoles,
                    'attempted_route' => $routeName,
                    'attempted_url' => $request->fullUrl()
                ]);
        }

        return $next($request);
    }

    /**
     * Check for frequent unauthorized attempts and alert superadmins
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

        $message = "SECURITY ALERT: User {$user->name} from {$regionName}, {$districtName} (Role: {$roleName}) attempted unauthorized access 3+ times. Time: " . now()->format('d/m/Y H:i');

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
