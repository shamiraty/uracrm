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


class AuthenticatedSessionController extends Controller
{

    public function create(): View
    {
    
        return view('auth.login');  
    }

    /**
     * Handle an incoming authentication request.
      */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(RouteServiceProvider::HOME);
    // }




    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     // Generate and send OTP
    //     $user = Auth::user();
    //     $otp = rand(100000, 999999);
    //     Cache::put('otp_' . $user->phone_number, $otp, now()->addMinutes(5));
    //     $this->sendEnquiryapproveSMS($user->phone_number, "Your OTP is: $otp");

    //     // Temporarily logout user until OTP is verified
    //     Auth::logout();

    //     // Store user's phone in session for OTP verification
    //     $request->session()->put('otp_phone', $user->phone_number);

    //     // Redirect to an OTP verification page
    //     return redirect('/otp-verify');
    // }
    public function store(LoginRequest $request)
{
    $request->authenticate();

    // Generate and send OTP
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

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
