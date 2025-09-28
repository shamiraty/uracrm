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
    //handle incomming request
    $request->authenticate();

    //include user authentication
    $user = Auth::user();

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

    //re-initialize login attemptation to 0
    $user->login_attempts = 0; 

    //re-initialize last login to now (only after checks)
    $user->last_login = now();

    //each login increment 'first login'
    $user->increment('first_login');

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
    $user->save();

    // Generate Random OTP and send OTP
    $user = Auth::user();
    $otp = rand(100000, 999999);
    Cache::put('otp_' . $user->phone_number, $otp, now()->addMinutes(5));
    $this->sendEnquiryapproveSMS($user->phone_number, "Your OTP is: $otp");

    // Temporarily logout user
    Auth::logout();

    // Store phone in session for OTP verification
    $request->session()->put('otp_phone', $user->phone_number);

    // Redirect to OTP verification page that triggers SweetAlert automatically
    return redirect('/otp-verify');
}


    public function confirmOTP(Request $request)
{
    $request->validate(['otp' => 'required']);

    $phone = $request->session()->get('otp_phone');
    if ($phone && Cache::get('otp_' . $phone) == $request->otp) {
        $user = User::where('phone_number', $phone)->firstOrFail();
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Invalid OTP'], 401);
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
    return view('auth.required-password-change');
}

public function storeFirstPasswordChange(Request $request)
{
    $request->validate([
        'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
    ]);

    if (Hash::check($request->password, Auth::user()->password)) {
        throw ValidationException::withMessages([
           'password' => ['The new password must be different from the current password.'],
        ]);
    }

    $user = Auth::user();
    $user->password = Hash::make($request->password);
    $user->first_login = false;
    $user->last_password_change = now();
    $user->save();

    return redirect()->route('dashboard')->with('success', 'Password changed successfully!');
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

    // Generate and send OTP
    $otp = rand(100000, 999999);
    Cache::put('otp_' . $user->phone_number, $otp, now()->addMinutes(5));
    $this->sendEnquiryapproveSMS($user->phone_number, "Your OTP is: $otp");

    // Logout user na kumpeleka OTP verification
    Auth::logout();
    $request->session()->put('otp_phone', $user->phone_number);

    return redirect()->route('otp.verify');
}

}
