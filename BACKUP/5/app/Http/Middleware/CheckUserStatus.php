<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        // If user is authenticated, prevent access to login and password change pages
        if (Auth::check()) {
            $user = Auth::user();

            // Get current route name safely
            $currentRoute = $request->route() ? $request->route()->getName() : null;

            // Check if user is trying to access login page while authenticated
            if ($currentRoute === 'login' || $request->is('login')) {
                // Redirect authenticated users away from login page
                return redirect()->route('dashboard');
            }

            // Check if authenticated user is trying to access first password change
            // but has already completed initial setup
            if ($currentRoute === 'password.change.first' || $request->is('password/change/first')) {
                // If user has already changed password and completed first login, block access
                if (!empty($user->last_password_change) && !is_null($user->last_password_change) && $user->first_login > 1) {
                    return redirect()->route('dashboard')->with('info', 'You have already completed the initial password setup.');
                }
                // Otherwise allow access for genuine first-time setup
                return $next($request);
            }

            // Routes to skip checking (to avoid redirect loops)
            $skipRoutes = [
                'logout',
                'password.change.required',
                'otp.verify',
                'password.change.first.store',
                'password.change.required.store'
            ];

            // Skip if on excluded routes or if current route starts with 'password.' (except first password change)
            if (in_array($currentRoute, $skipRoutes) || (str_starts_with($currentRoute ?? '', 'password.') && $currentRoute !== 'password.change.first')) {
                return $next($request);
            }

            // CRITICAL SECURITY: Check if user has completed OTP verification
            $otpPhone = $request->session()->get('otp_phone');

            // ADDITIONAL SECURITY: Check if there's an active OTP in backend Cache for this user
            $userOtpKey = 'otp_user_' . $user->id . '_' . $user->phone_number;
            $activeOtp = Cache::get($userOtpKey);

            // ADDITIONAL SECURITY: Check if user is under cooldown
            $cooldownKey = 'otp_cooldown_user_' . $user->id . '_' . $user->phone_number;
            $activeCooldown = Cache::get($cooldownKey);
            $isUnderCooldown = $activeCooldown && now()->timestamp < $activeCooldown;

            // Force OTP verification if ANY of these conditions are true:
            // 1. Session indicates pending OTP
            // 2. Backend Cache has active OTP for this user
            // 3. User is under cooldown (means they were in OTP flow recently)
            if ($otpPhone || $activeOtp || $isUnderCooldown) {
                \Log::warning("SECURITY BLOCK: User {$user->id} attempting to bypass OTP. Session OTP: " . ($otpPhone ? 'YES' : 'NO') . ", Cache OTP: " . ($activeOtp ? 'YES' : 'NO') . ", Cooldown: " . ($isUnderCooldown ? 'YES' : 'NO'));

                Auth::logout();
                $request->session()->put('otp_phone', $user->phone_number);
                $request->session()->put('otp_user_id', $user->id);

                if ($isUnderCooldown) {
                    $request->session()->put('otp_cooldown_end', $activeCooldown);
                }

                return redirect('/otp-verify');
            }

            // Check if user account is not active
            if ($user->status !== 'active') {
                Auth::logout();
                return redirect('/login')->withErrors(['email' => 'Your account is not active. Please contact support.']);
            }

            // Check if last_password_change is NULL or empty
            if (empty($user->last_password_change) || is_null($user->last_password_change)) {
                return redirect()->route('password.change.first');
            }

            // Check if last_login is NULL or empty
            if (empty($user->last_login) || is_null($user->last_login)) {
                return redirect('/login')->withErrors(['email' => 'Your account is not active. Please contact support.']);
            }

            // Additional check: if password is older than 3 months
            if ($user->last_password_change && Carbon::parse($user->last_password_change)->addMonths(3)->isPast()) {
                return redirect()->route('password.change.required');
            }

            // Role-based dashboard access restriction - prevent certain roles from accessing dashboard
            if ($request->is('/') || $request->routeIs('dashboard')) {
                $redirectUrl = $this->getRoleBasedRedirectUrl($user);

                // If we get a role-specific redirect URL (not dashboard), redirect there
                if ($redirectUrl !== route('dashboard')) {
                    return redirect($redirectUrl);
                }
            }
        }

        return $next($request);
    }

    /**
     * Get role-based redirect URL
     */
    private function getRoleBasedRedirectUrl($user)
    {
        // Get user's primary role
        $userRole = $user->roles->first()->name ?? null;

        // Role-based redirect mapping
        switch ($userRole) {
            case 'accountant':
                return route('disbursements.pending');

            case 'registrar':
            case 'public_relation_officer':
            case 'registrar_hq':
                return route('enquiries.index');

            case 'loanofficer':
                return route('loan-offers.index');

            case 'system_admin':
            case 'admin':
                return route('users.index');

            case 'general_manager':
                return route('payment.manager.dashboard');

            default:
                // For unhandled roles like superadmin, allow dashboard access
                return route('dashboard');
        }
    }
}