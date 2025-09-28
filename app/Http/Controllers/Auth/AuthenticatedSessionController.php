<?php
namespace App\Http\Controllers\Auth;
use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; 
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    // Display the login view.
    public function create(): View
    {
        return view('auth.login');   
    }

public function store(LoginRequest $request)
{
    try {
        //handle incomming request
        $request->authenticate();
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Authentication failed - increment login attempts
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->increment('login_attempts');

            // If login attempts reach 3, set status to inactive
            if ($user->login_attempts >= 3) {
                $user->status = 'inactive';
                $user->save();
                return redirect()->route('login')->withErrors(['email' => 'Account has been locked due to multiple failed login attempts. Please contact support.']);
            }
            $user->save();
        }
        throw $e;
    }

    //include user authentication
    $user = Auth::user();

    // Check if user has been inactive for 3 months - set to inactive
    if ($user->last_login && Carbon::parse($user->last_login)->addMonths(3)->isPast()) {
        $user->status = 'inactive';
        $user->save();
        Auth::logout();
        return redirect()->route('login')->withErrors(['email' => 'Your account has been inactive for more than 3 months. Please contact support.']);
    }

    //check if user account is active,  else redirect to login with error
    if ($user->status !== 'active') {
        Auth::logout();
        return redirect()->route('login')->withErrors(['email' => 'Your account is not active. Please contact support.']);
    }

    // Store original values before updating
    $isFirstPasswordChange = empty($user->last_password_change) || is_null($user->last_password_change);
    $isFirstLogin = empty($user->last_login) || is_null($user->last_login);

    // Check if this is first password change - redirect immediately
    if ($isFirstPasswordChange) {
        return redirect()->route('password.change.first');
    }

    // Check if this is first login - redirect immediately
    if ($isFirstLogin) {
        return redirect()->route('password.change.first');
    }

    // Check if last password change is 3 months ago
    if ($user->last_password_change && Carbon::parse($user->last_password_change)->addMonths(3)->isPast()) {
        return redirect()->route('password.change.required'); // Redirect to password change route
    }

    // Check for first login and redirect to change password
    if ($user->first_login <= 1) {
        $user->increment('first_login');
        $user->save();

        // Redirect to password change route
        return redirect()->route('password.change.first');
    }

    // CRITICAL: Clear any previous user's session data to prevent cross-contamination
    $request->session()->forget('otp_phone');
    $request->session()->forget('otp_user_id');
    $request->session()->forget('otp_cooldown_end');
    $request->session()->forget('first_otp_time');
    $request->session()->forget('has_been_cooled_down');
    $request->session()->forget('otp_sent_after_cooldown');

    // Regenerate session ID to prevent cross-user contamination
    $request->session()->regenerate();
    \Log::info("SESSION ISOLATED: Cleared previous session data and regenerated session ID for user {$user->id} ({$user->name})");

    //re-initialize login attemptation to 0 (successful login)
    $user->login_attempts = 0;

    //re-initialize last login to now (only after checks)
    $user->last_login = now();

    //each login increment 'first login'
    $user->increment('first_login');

    $user->save();

    // Check if user is under OTP cooldown (only for returning users) - USER SPECIFIC
    $cooldownKey = 'otp_cooldown_user_' . $user->id . '_' . $user->phone_number;
    $cooldownEnd = Cache::get($cooldownKey);

    if ($cooldownEnd && now()->timestamp < $cooldownEnd) {
        // User is still under cooldown - don't send new OTP
        $remainingTime = $cooldownEnd - now()->timestamp;
        \Log::info("ISOLATED COOLDOWN: User {$user->id} ({$user->name}) under personal cooldown: {$remainingTime} seconds remaining. Cache key: {$cooldownKey}");

        Auth::logout();
        $request->session()->put('otp_phone', $user->phone_number);
        $request->session()->put('otp_user_id', $user->id);
        $request->session()->put('otp_cooldown_end', $cooldownEnd);
        return redirect('/otp-verify');
    } elseif ($cooldownEnd && now()->timestamp >= $cooldownEnd) {
        // Cooldown has expired - clean it up
        Cache::forget($cooldownKey);
        \Log::info("COOLDOWN EXPIRED: User {$user->id} cooldown has expired and been cleared. Cache key: {$cooldownKey}");
    }

    // Check if there's already an active OTP for this user - USER SPECIFIC
    $existingOtp = Cache::get('otp_user_' . $user->id . '_' . $user->phone_number);
    if ($existingOtp) {
        // User already has an active OTP but is trying to login again
        // This is abuse - they should be on OTP verification page, not login page

        // IMMEDIATE COOLDOWN - No tolerance for returning to login after OTP issued
        $cooldownEnd = now()->addMinutes(3)->timestamp;
        Cache::put($cooldownKey, $cooldownEnd, now()->addMinutes(3));
        \Log::info("COOLDOWN SET: User {$user->id} ({$user->name}) abuse cooldown activated. Cache key: {$cooldownKey}");

        Auth::logout();
        $request->session()->put('otp_phone', $user->phone_number);
        $request->session()->put('otp_user_id', $user->id);
        $request->session()->put('otp_cooldown_end', $cooldownEnd);
        $request->session()->put('has_been_cooled_down', true);

        // Clear the existing OTP since user is abusing the system - USER SPECIFIC
        Cache::forget('otp_user_' . $user->id . '_' . $user->phone_number);
        Cache::forget('first_otp_time_user_' . $user->id . '_' . $user->phone_number);
        Cache::forget('login_attempts_with_otp_user_' . $user->id . '_' . $user->phone_number);

        return redirect('/otp-verify');
    }

    // Generate Random OTP and send OTP (only if no existing OTP) - USER SPECIFIC
    $user = Auth::user();
    $otp = rand(100000, 999999);
    Cache::put('otp_user_' . $user->id . '_' . $user->phone_number, $otp, now()->addMinutes(5));

    // Store first OTP time in cache (survives session destruction) - USER SPECIFIC
    $firstOtpTime = now()->timestamp;
    Cache::put('first_otp_time_user_' . $user->id . '_' . $user->phone_number, $firstOtpTime, now()->addMinutes(10));

    $this->sendEnquiryapproveSMS($user->phone_number, "Your OTP is: $otp");

    // DON'T set cooldown for first login - cooldown will be set only when user requests resend
    // The cooldown logic will be handled in the resendOTP method

    // Temporarily logout user
    Auth::logout();

    // Store phone, user ID and first OTP time in session for OTP verification (no cooldown for first attempt)
    $request->session()->put('otp_phone', $user->phone_number);
    $request->session()->put('otp_user_id', $user->id);
    $request->session()->put('first_otp_time', $firstOtpTime);

    // Redirect to OTP verification page that triggers SweetAlert automatically
    return redirect('/otp-verify');
}


    public function confirmOTP(Request $request)
{
    $request->validate(['otp' => 'required']);

    $phone = $request->session()->get('otp_phone');
    $userId = $request->session()->get('otp_user_id');

    if ($phone && $userId && Cache::get('otp_user_' . $userId . '_' . $phone) == $request->otp) {
        $user = User::where('phone_number', $phone)->where('id', $userId)->firstOrFail();
        Auth::login($user);

        // Clear OTP-related session data
        $request->session()->forget('otp_phone');
        $request->session()->forget('otp_user_id');
        $request->session()->forget('otp_cooldown_end');
        $request->session()->forget('first_otp_time');
        $request->session()->forget('has_been_cooled_down');
        $request->session()->forget('otp_sent_after_cooldown');
        $request->session()->regenerate();

        // Clear the OTP and cooldown from cache - USER SPECIFIC
        Cache::forget('otp_user_' . $userId . '_' . $phone);
        Cache::forget('otp_cooldown_user_' . $userId . '_' . $phone);
        Cache::forget('first_otp_time_user_' . $userId . '_' . $phone);
        Cache::forget('login_attempts_with_otp_user_' . $userId . '_' . $phone);

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Invalid OTP'], 401);
}

public function resendOTP(Request $request)
{
    $phone = $request->session()->get('otp_phone');
    $userId = $request->session()->get('otp_user_id');

    if (!$phone || !$userId) {
        return response()->json(['success' => false, 'message' => 'No phone number or user ID found in session'], 400);
    }

    // Check if user is under cooldown - USER SPECIFIC
    $cooldownKey = 'otp_cooldown_user_' . $userId . '_' . $phone;
    $cooldownEnd = Cache::get($cooldownKey);

    if ($cooldownEnd && now()->timestamp < $cooldownEnd) {
        $remainingSeconds = $cooldownEnd - now()->timestamp;
        return response()->json([
            'success' => false,
            'message' => 'OTP request is still under cooldown',
            'remaining_seconds' => max(0, $remainingSeconds)
        ], 429);
    } elseif ($cooldownEnd && now()->timestamp >= $cooldownEnd) {
        // Cooldown has expired - clean it up
        Cache::forget($cooldownKey);
        \Log::info("RESEND: Cooldown expired for User {$userId}, cleaned up cache");
    }

    // Check if user exists - USER SPECIFIC
    $user = User::where('phone_number', $phone)->where('id', $userId)->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    // PRIORITY CHECK: If user already received OTP after cooldown and is requesting another
    // This covers the scenario: User waited through cooldown → Got OTP → Didn't enter → Requesting again
    $otpSentAfterCooldown = $request->session()->get('otp_sent_after_cooldown', false);
    if ($otpSentAfterCooldown) {
        // User already got their "reward OTP" after waiting for cooldown, now they want another
        // This time we apply immediate cooldown - NO MORE CHANCES
        $cooldownEnd = now()->addMinutes(3)->timestamp;
        Cache::put($cooldownKey, $cooldownEnd, now()->addMinutes(3));
        $request->session()->put('otp_cooldown_end', $cooldownEnd);
        $request->session()->forget('otp_sent_after_cooldown'); // Clear the flag

        return response()->json([
            'success' => false,
            'message' => 'You already received an OTP but did not use it. Please wait 3 minutes.',
            'remaining_seconds' => 180
        ], 429);
    }

    // Check if user has been through cooldown before
    $hasBeenCooledDown = $request->session()->get('has_been_cooled_down', false);

    if (!$hasBeenCooledDown) {
        // First time requesting resend - check if enough time has passed since first OTP (3 minutes)
        $firstOtpTime = $request->session()->get('first_otp_time') ?: Cache::get('first_otp_time_user_' . $userId . '_' . $phone);
        if ($firstOtpTime) {
            $timeSinceFirstOtp = now()->timestamp - $firstOtpTime;
            if ($timeSinceFirstOtp < 180) { // Less than 3 minutes (180 seconds)
                $remainingSeconds = 180 - $timeSinceFirstOtp;

                // Set cooldown to prevent further requests - BUT DON'T SEND OTP
                $cooldownEnd = now()->addSeconds($remainingSeconds)->timestamp;
                Cache::put($cooldownKey, $cooldownEnd, now()->addSeconds($remainingSeconds));
                $request->session()->put('otp_cooldown_end', $cooldownEnd);
                $request->session()->put('has_been_cooled_down', true);

                return response()->json([
                    'success' => false,
                    'message' => 'Please wait 3 minutes since your first login before requesting new OTP',
                    'remaining_seconds' => $remainingSeconds
                ], 429);
            }
        }

        // If we reach here, it's been 3+ minutes since first login
        // SET COOLDOWN FIRST for future requests - BUT DON'T SEND OTP YET
        $cooldownEnd = now()->addMinutes(3)->timestamp;
        Cache::put($cooldownKey, $cooldownEnd, now()->addMinutes(3));
        $request->session()->put('otp_cooldown_end', $cooldownEnd);
        $request->session()->put('has_been_cooled_down', true);

        return response()->json([
            'success' => false,
            'message' => 'Cooldown activated. Please wait 3 minutes before requesting new OTP',
            'remaining_seconds' => 180
        ], 429);

    } else {
        // User has been through cooldown before and cooldown has expired
        // NOW we can send OTP - but DON'T set cooldown immediately
        // Let them enter the OTP first, then cooldown will apply to next request

        // Generate and send OTP - USER SPECIFIC
        $otp = rand(100000, 999999);
        Cache::put('otp_user_' . $userId . '_' . $phone, $otp, now()->addMinutes(5));

        // Send SMS
        $result = $this->sendEnquiryapproveSMS($phone, "Your OTP is: $otp");

        if ($result) {
            // DON'T set cooldown yet - let user enter this OTP first
            // Mark that they have received an OTP and can now enter it
            $request->session()->put('otp_sent_after_cooldown', true);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully. You can now enter the OTP.',
                'no_immediate_cooldown' => true
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to send OTP'], 500);
        }
    }
}


private function sendEnquiryapproveSMS($phone, $message)
{
    $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
    $apiKey = 'xYz123#';

    $client = new \GuzzleHttp\Client();
    try {
        $response = $client->request('POST', $url, [
            'verify' => false,  // Keep SSL verification disabled as in your working script
            'form_params' => [
                'msisdn' => $phone,
                'message' => $message,
                'key' => $apiKey,
            ]
        ]);

        $responseBody = $response->getBody()->getContents();
        \Log::info("SMS sent response: " . $responseBody);
        return $responseBody;
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        \Log::error("Failed to send SMS: " . $e->getMessage());
        return null;
    }
}


    // Destroy an authenticated session.

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showFirstPasswordChangeForm()
{
    // Check if user is already authenticated and logged in properly
    if (Auth::check()) {
        $user = Auth::user();

        // If user has already changed their password and completed first login process
        // they shouldn't access this route - redirect to dashboard
        if (!empty($user->last_password_change) && !is_null($user->last_password_change) && $user->first_login > 1) {
            return redirect()->route('dashboard')->with('info', 'You have already completed the initial password setup.');
        }

        // If this is genuinely a first-time password change, allow access
        return view('auth.required-password-change');
    }

    // If not authenticated, redirect to login
    return redirect()->route('login')->withErrors(['message' => 'Please login first to access password change.']);
}

public function storeFirstPasswordChange(Request $request)
{
    // Ensure user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->withErrors(['message' => 'Authentication required.']);
    }

    $user = Auth::user();

    // Additional security check - prevent abuse if user already completed initial setup
    if (!empty($user->last_password_change) && !is_null($user->last_password_change) && $user->first_login > 1) {
        return redirect()->route('dashboard')->with('warning', 'Initial password setup already completed.');
    }

    $request->validate([
        'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
    ]);

    if (Hash::check($request->password, Auth::user()->password)) {
        throw ValidationException::withMessages([
           'password' => ['The new password must be different from the current password.'],
        ]);
    }

    $user->password = Hash::make($request->password);
    $user->first_login = 10; // Set to higher value to indicate completion
    $user->last_password_change = now();
    $user->save();

    return redirect()->route('dashboard')->with('success', 'Password changed successfully! Welcome to URA SACCOS.');
}

public function showRequiredPasswordChangeForm()
    {
        return view('auth.required-password-change');
    }

    public function storeRequiredPasswordChange(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
    ]);

    if (!Hash::check($request->old_password, Auth::user()->password)) {
        throw ValidationException::withMessages([
            'old_password' => ['The old password does not match our records.'],
        ]);
    }

    if (Hash::check($request->password, Auth::user()->password)) {
        throw ValidationException::withMessages([
           'password' => ['The new password must be different from the old password.'],
        ]);
    }

    $user = Auth::user();
    $user->password = Hash::make($request->password);
    $user->last_password_change = now();
    $user->save();

    // Generate and send OTP - USER SPECIFIC
    $otp = rand(100000, 999999);
    Cache::put('otp_user_' . $user->id . '_' . $user->phone_number, $otp, now()->addMinutes(5));
    $this->sendEnquiryapproveSMS($user->phone_number, "Your OTP is: $otp");

    // Logout user na kumpeleka OTP verification
    Auth::logout();
    $request->session()->put('otp_phone', $user->phone_number);
    $request->session()->put('otp_user_id', $user->id);

    return redirect()->route('otp.verify');
}

    /**
     * Check cooldown status for any user - for debugging and frontend sync
     */
    public function checkCooldownStatus(Request $request)
    {
        $phone = $request->input('phone');
        $userId = $request->input('user_id');

        if (!$phone || !$userId) {
            return response()->json(['error' => 'Phone and user_id required'], 400);
        }

        $cooldownKey = 'otp_cooldown_user_' . $userId . '_' . $phone;
        $cooldownEnd = Cache::get($cooldownKey);

        if ($cooldownEnd && now()->timestamp < $cooldownEnd) {
            $remainingSeconds = max(0, $cooldownEnd - now()->timestamp);
            return response()->json([
                'under_cooldown' => true,
                'cooldown_end' => $cooldownEnd,
                'remaining_seconds' => $remainingSeconds,
                'remaining_time' => gmdate('i:s', $remainingSeconds)
            ]);
        } elseif ($cooldownEnd && now()->timestamp >= $cooldownEnd) {
            // Cooldown has expired - clean it up
            Cache::forget($cooldownKey);
        }

        return response()->json([
            'under_cooldown' => false,
            'cooldown_end' => null,
            'remaining_seconds' => 0
        ]);
    }

    /**
     * Debug function to check cache state for all users
     */
    public function debugCacheState(Request $request)
    {
        $userId = $request->input('user_id');
        $phone = $request->input('phone');

        if (!$userId || !$phone) {
            return response()->json(['error' => 'user_id and phone required'], 400);
        }

        $cooldownKey = 'otp_cooldown_user_' . $userId . '_' . $phone;
        $otpKey = 'otp_user_' . $userId . '_' . $phone;
        $firstOtpKey = 'first_otp_time_user_' . $userId . '_' . $phone;

        return response()->json([
            'user_id' => $userId,
            'phone' => $phone,
            'cache_keys' => [
                'cooldown' => $cooldownKey,
                'otp' => $otpKey,
                'first_otp_time' => $firstOtpKey
            ],
            'cache_values' => [
                'cooldown_end' => Cache::get($cooldownKey),
                'otp_exists' => Cache::get($otpKey) ? true : false,
                'first_otp_time' => Cache::get($firstOtpKey)
            ],
            'session_data' => [
                'otp_phone' => $request->session()->get('otp_phone'),
                'otp_user_id' => $request->session()->get('otp_user_id'),
                'otp_cooldown_end' => $request->session()->get('otp_cooldown_end')
            ],
            'timestamp' => now()->timestamp
        ]);
    }

}
