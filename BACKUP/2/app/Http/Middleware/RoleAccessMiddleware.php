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
     * Route role mapping based on comments in web.php
     */
    private $routeRoleMapping = [
        // User activity routes
        'users.update-activity' => ['admin', 'superadmin', 'system_admin'],
        'users.online-users' => ['admin', 'superadmin', 'system_admin'],
        'users.clear-online-status' => ['admin', 'superadmin', 'system_admin'],
        'users.export' => ['admin', 'superadmin', 'system_admin'],
        'users.export-range' => ['admin', 'superadmin', 'system_admin'],
        'users.export-quick' => ['admin', 'superadmin', 'system_admin'],
        'users.security-audit' => ['admin', 'superadmin', 'system_admin'],
        'users.schedule-report' => ['admin', 'superadmin', 'system_admin'],
        'users.analytics-data' => ['admin', 'superadmin', 'system_admin'],

        // Export routes
        'exportLoanApplication' => ['admin', 'accountant', 'loanofficer', 'Registrar', 'superadmin', 'system_admin', 'public_relation_officer', 'registrar_hq', 'representative', 'general_manager', 'branch_manager'],
        'exportEnquiriesUnjoinMembership' => ['admin', 'accountant', 'loanofficer', 'Registrar', 'superadmin', 'system_admin', 'public_relation_officer', 'registrar_hq', 'representative', 'general_manager', 'branch_manager'],

        // Enquiry routes
        'enquiries.create' => ['registrar_hq', 'Registrar'],
        'enquiries.show' => ['admin', 'accountant', 'loanofficer', 'Registrar', 'superadmin', 'system_admin', 'public_relation_officer', 'registrar_hq', 'representative', 'general_manager', 'branch_manager'],
        'enquiries.edit' => ['registrar_hq', 'Registrar'],
        'enquiries.update' => ['registrar_hq', 'Registrar'],

        // Dashboard
        'dashboard' => ['general_manager'],

        // Responses
        'responses.create' => ['accountant', 'loanofficer', 'Registrar', 'registrar_hq', 'general_manager', 'branch_manager'],
        'responses.store' => ['accountant', 'loanofficer', 'Registrar', 'registrar_hq', 'general_manager', 'branch_manager'],

        // Notifications
        'notifications.read' => ['admin', 'accountant', 'loanofficer', 'Registrar', 'superadmin', 'system_admin', 'public_relation_officer', 'registrar_hq', 'representative', 'general_manager', 'branch_manager'],

        // Members
        'members.details' => ['admin', 'accountant', 'loanofficer', 'Registrar', 'superadmin', 'system_admin', 'public_relation_officer', 'registrar_hq', 'representative', 'general_manager', 'branch_manager'],

        // Bulk operations
        'enquiries.bulk-assign' => ['registrar_hq'],
        'enquiries.bulk-reassign' => ['registrar_hq'],
        'enquiries.bulk-delete' => ['registrar_hq'],

        // User management
        'users.toggle-status' => ['superadmin', 'system_admin', 'admin'],
        'users.reset-password' => ['superadmin', 'system_admin', 'admin'],
        'users.bulk-operations' => ['superadmin', 'system_admin', 'admin'],

        // Loan calculations
        'calculate.loan' => ['loanofficer'],

        // Payments
        'payments.create' => ['accountant'],
        'payments.store' => ['accountant'],
        'payment.initiate' => ['accountant'],
        'payment.approve' => ['general_manager'],
        'payment.pay' => ['accountant'],
        'payment.reject' => ['general_manager', 'accountant'],
        'payments.timeline' => ['general_manager', 'accountant'],
        'payments.type' => ['general_manager', 'accountant'],

        // OTP routes
        'send.otp.approve' => ['general_manager'],
        'verify.otp.approve' => ['general_manager'],

        // Payment dashboards
        'payment.accountant.dashboard' => ['accountant'],
        'payment.manager.dashboard' => ['general_manager', 'accountant'],

        // Loan routes
        'loans.my' => ['loanofficer'],
        'loans.pending' => ['loanofficer'],
        'loans.approved' => ['loanofficer'],
        'loans.rejected' => ['loanofficer'],
        'loans.disbursed' => ['loanofficer'],
        'loans.reports' => ['loanofficer'],
        'loans.collections' => ['loanofficer'],

        // Disbursements
        'disbursements.pending' => ['accountant'],
        'disbursements.process' => ['accountant'],
        'disbursements.reject' => ['accountant'],

        // Loan offers
        'loan-offers.details' => ['loanofficer'],
        'loan-offers.batch-disburse' => ['accountant'],
        'loan-offers.reject-disbursement' => ['accountant'],

        // Deductions
        'deductions.import' => ['superadmin', 'system_admin', 'admin'],
        'deductions.contributions.handle' => ['admin', 'accountant', 'loanofficer', 'Registrar', 'superadmin', 'system_admin', 'public_relation_officer', 'registrar_hq', 'representative', 'general_manager', 'branch_manager'],

        // Members import
        'uramembers.import' => ['superadmin', 'system_admin'],

        // Commands and ranks
        'commands.index' => ['superadmin', 'system_admin'],
        'commands.create' => ['superadmin', 'system_admin'],
        'commands.store' => ['superadmin', 'system_admin'],
        'commands.show' => ['superadmin', 'system_admin'],
        'commands.edit' => ['superadmin', 'system_admin'],
        'commands.update' => ['superadmin', 'system_admin'],
        'commands.destroy' => ['superadmin', 'system_admin'],
        'ranks.create' => ['superadmin', 'system_admin'],
        'ranks.store' => ['superadmin', 'system_admin'],

        // Bulk SMS
        'bulk.sms.form' => ['admin', 'accountant', 'loanofficer', 'superadmin', 'system_admin', 'public_relation_officer', 'general_manager'],
        'bulk.sms.parse' => ['admin', 'accountant', 'loanofficer', 'superadmin', 'system_admin', 'public_relation_officer', 'general_manager'],
        'bulk.sms.send' => ['admin', 'accountant', 'loanofficer', 'superadmin', 'system_admin', 'public_relation_officer', 'general_manager'],

        // Card details
        'card-details.index' => ['superadmin', 'system_admin'],
        'card-details.sync' => ['superadmin', 'system_admin'],
        'card-details.edit' => ['superadmin', 'system_admin'],
        'card-details.update' => ['superadmin', 'system_admin'],
        'card-details.destroy' => ['superadmin', 'system_admin'],
    ];

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

        // Skip role checking for routes not in our mapping or public routes
        if (!isset($this->routeRoleMapping[$routeName])) {
            return $next($request);
        }

        $requiredRoles = $this->routeRoleMapping[$routeName];
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

        $message = "SECURITY ALERT: User {$user->name} from {$user->region->name ?? 'N/A'}, {$user->district->name ?? 'N/A'} (Role: {$user->roles->first()->name ?? 'N/A'}) attempted unauthorized access 3+ times. Time: " . now()->format('d/m/Y H:i');

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
