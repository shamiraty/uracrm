<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status !== 'active') {
            Auth::logout();
            return redirect('/login')->withErrors(['email' => 'Your account is not active. Please contact support.']);
        }

        return $next($request);
    }

    
}