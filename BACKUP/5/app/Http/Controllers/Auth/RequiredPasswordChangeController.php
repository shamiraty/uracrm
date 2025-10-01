<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class RequiredPasswordChangeController extends Controller
{
    /**
     * Show the required password change form.
     */
    public function show(): View
    {
        // Prevent authenticated users who have already changed their password
        if (auth()->check()) {
            $user = auth()->user();

            // If user has already changed password, redirect them based on role
            if (!empty($user->last_password_change) && !is_null($user->last_password_change)) {
                // Check if password is not older than 3 months
                if (!Carbon::parse($user->last_password_change)->addMonths(3)->isPast()) {
                    // Redirect based on role
                    if ($user->hasRole('accountant') || $user->hasRole('loanofficer')) {
                        return redirect()->route('enquiries.my');
                    } elseif ($user->hasRole('registrar') || $user->hasRole('public_relation_officer') || $user->hasRole('registrar_hq')) {
                        return redirect()->route('enquiries.index');
                    } elseif ($user->hasRole('system_admin') || $user->hasRole('admin')) {
                        return redirect()->route('users.index');
                    } else {
                        return redirect()->intended('/dashboard');
                    }
                }
            }
        }

        return view('auth.required-password-change');
    }

    /**
     * Handle the required password change form submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        // Verify the old password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The current password is incorrect.']);
        }

        // Update the password and timestamp
        $user->update([
            'password' => Hash::make($request->password),
            'last_password_change' => Carbon::now(),
        ]);

        // Redirect based on role after successful password change
        if ($user->hasRole('accountant') || $user->hasRole('loanofficer')) {
            return redirect()->route('enquiries.my')->with('status', 'Password changed successfully!');
        } elseif ($user->hasRole('registrar') || $user->hasRole('public_relation_officer') || $user->hasRole('registrar_hq')) {
            return redirect()->route('enquiries.index')->with('status', 'Password changed successfully!');
        } elseif ($user->hasRole('system_admin') || $user->hasRole('admin')) {
            return redirect()->route('users.index')->with('status', 'Password changed successfully!');
        } else {
            return redirect()->intended('/dashboard')->with('status', 'Password changed successfully!');
        }
    }
}