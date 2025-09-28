<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class UnauthorizedAccessGuard
{
    /**
     * Handle an incoming request to unauthorized access route
     * Ensures the route is only accessible through proper middleware redirect
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a valid redirect from RoleAccessMiddleware
        $hasToken = session()->has('unauthorized_access_token');
        $hasTimestamp = session()->has('unauthorized_timestamp');
        $isRecentTimestamp = session('unauthorized_timestamp', 0) >= now()->subMinutes(10)->timestamp;

        // ONLY allow access if coming from valid middleware redirect (no exceptions)
        if ($hasToken && $hasTimestamp && $isRecentTimestamp) {
            // Valid access through middleware, proceed and clear the tokens
            session()->forget(['unauthorized_access_token', 'unauthorized_timestamp']);
            return $next($request);
        }

        // Log suspicious direct access attempt
        $user = auth()->user();
        $userRole = $user && $user->roles->first() ? $user->roles->first()->name : null;

        Log::warning('Blocked direct access to unauthorized route', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'user_role' => $userRole,
            'referer' => $request->header('referer'),
            'url' => $request->fullUrl(),
            'timestamp' => now()
        ]);

        // For direct access attempts, return a simple 403 response
        // This prevents redirect loops while maintaining security
        return response('<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 50px; text-align: center; }
        .container { background: white; padding: 40px; border-radius: 8px; max-width: 500px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error-code { font-size: 48px; color: #dc3545; font-weight: bold; margin-bottom: 20px; }
        .message { font-size: 18px; color: #333; margin-bottom: 20px; }
        .btn { background: #17479E; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">403</div>
        <div class="message">Access Denied</div>
        <p>This page is only accessible through proper authorization flow.</p>
        <a href="/" class="btn">Go to Dashboard</a>
    </div>
</body>
</html>', 403)->header('Content-Type', 'text/html');
    }
}