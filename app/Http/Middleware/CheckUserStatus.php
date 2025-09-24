<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Get current route name safely
            $currentRoute = $request->route() ? $request->route()->getName() : null;
            
            // Routes to skip checking (to avoid redirect loops)
            $skipRoutes = [
                'login',
                'logout',
                'password.change.first',
                'password.change.required',
                'otp.verify',
                'password.change.first.store',
                'password.change.required.store'
            ];
            
            // Skip if on excluded routes or if current route starts with 'password.'
            if (in_array($currentRoute, $skipRoutes) || str_starts_with($currentRoute ?? '', 'password.')) {
                return $next($request);
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
        }

        return $next($request);
    }
}