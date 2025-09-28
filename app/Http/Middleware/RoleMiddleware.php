<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
}