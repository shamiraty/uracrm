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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 450px;
            width: 90%;
        }
        .error-code {
            font-size: 4rem;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        .title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .message {
            font-size: 1rem;
            color: #7f8c8d;
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background 0.3s ease;
            display: inline-block;
        }
        .btn:hover {
            background: #2980b9;
            text-decoration: none;
            color: white;
        }
        .security-notice {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 1rem;
            margin: 1.5rem 0;
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">403</div>
        <div class="title">Access Denied</div>
        <div class="message">
            You do not have permission to access this resource. This incident has been logged for security monitoring.
        </div>
        <div class="security-notice">
            <strong>Security Notice:</strong> Unauthorized access attempts are monitored and logged.
        </div>
        <a href="/" class="btn">Return to Dashboard</a>
    </div>
</body>
</html>', 403)->header('Content-Type', 'text/html');
    }
}