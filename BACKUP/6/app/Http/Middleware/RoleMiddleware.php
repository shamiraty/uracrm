<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $roles = null): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();
        $userRole = $user->roles->first()->name ?? null;

        if (!$userRole) {
            return redirect()->route('unauthorized.access')
                ->with('error', 'No role assigned to your account.');
        }

        // If no specific roles required, allow access
        if (!$roles) {
            return $next($request);
        }

        // Parse allowed roles (separated by | or ,)
        $allowedRoles = preg_split('/[|,]/', $roles);
        $allowedRoles = array_map('trim', $allowedRoles);

        // Check if user has one of the allowed roles
        if (!in_array($userRole, $allowedRoles)) {
            // Log unauthorized access attempt
            \App\Models\UnauthorizedAccess::logAttempt(
                $user,
                $request->route()->getName(),
                $request->fullUrl(),
                $request->method(),
                $allowedRoles
            );

            // Send immediate SMS alert for unauthorized access
            $this->sendImmediateAlert($user, $request->route()->getName(), $request->fullUrl());

            // Check for frequent attempts and send escalated alert
            $this->checkAndAlertFrequentAttempts($user);

            return redirect()->route('unauthorized.access')
                ->with('unauthorized_data', [
                    'user_role' => $userRole,
                    'required_roles' => $allowedRoles,
                    'attempted_route' => $request->route()->getName(),
                    'attempted_url' => $request->fullUrl()
                ]);
        }

        return $next($request);
    }

    /**
     * Send immediate SMS alert for unauthorized access attempt
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

        $message = "🚨 SECURITY BREACH: {$user->name} ({$roleName}) from {$regionName}, {$districtName} tried accessing: {$routeName}. Time: " . now()->format('d/m/Y H:i:s');

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
        if (\App\Models\UnauthorizedAccess::hasFrequentAttempts($user->id, 10, 3)) {
            // Prevent spam by checking if we already sent alert recently
            $cacheKey = "frequent_attempts_alert_{$user->id}";

            if (!Cache::has($cacheKey)) {
                // Set cache for 30 minutes to prevent spam
                Cache::put($cacheKey, true, 30);

                // Send escalated SMS to superadmins
                $this->alertSuperAdmins($user);
            }
        }
    }

    /**
     * Send escalated SMS alert to superadmins about frequent unauthorized attempts
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

        $message = "🔥 ESCALATED ALERT: User {$user->name} ({$roleName}) from {$regionName}, {$districtName} has made 3+ REPEATED unauthorized access attempts! URGENT ACTION REQUIRED. Time: " . now()->format('d/m/Y H:i:s');

        foreach ($superAdmins as $admin) {
            if ($admin->phone_number) {
                $this->sendSMS($admin->phone_number, $message);
            }
        }
    }

    /**
     * Send SMS using the existing API
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