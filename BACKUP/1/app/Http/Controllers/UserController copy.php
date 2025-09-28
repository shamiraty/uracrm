<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Department;
use App\Models\District;
use App\Models\Rank;
use App\Models\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('rank')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $branches = Branch::all();
        $departments = Department::all();
        $districts = District::all();
        $commands = Command::all();
        $ranks = Rank::all();
        $regions = Region::with('districts')->get();
        return view('users.create', compact('roles', 'branches', 'regions', 'departments', 'districts', 'commands', 'ranks'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'branch_id' => 'required|exists:branches,id',
            'designation' => 'required|string|max:255',
            'rank' => 'required|string|max:255',
            'status' => 'required|string',
            'phone_number' => 'required|string|max:255|unique:users,phone_number',
            'region_id' => 'required|exists:regions,id',
            'department_id' => 'required|exists:departments,id',
            'district_id' => 'required|exists:districts,id',
            'command_id' => 'required|exists:commands,id',
            'role' => 'required|exists:roles,name',
        ]);

        // Generate a strong random password
        $password = Str::random(16);
        $validatedData['password'] = Hash::make($password);

        $user = User::create($validatedData);
        $user->assignRole($request->role);

        // Send SMS with credentials
        $message = "Hello " . $validatedData['name'] . ", your credentials for URA-CRM are: Username: " . $validatedData['email'] . ", Password: " . $password . ". Keep these details safe and change your password once you log in.";
        $this->sendEnquiryapproveSMS($validatedData['phone_number'], $message);

        return redirect()->route('users.index')->with('success', 'User created successfully and credentials sent via SMS.');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $branches = Branch::all();
        $departments = Department::all();
        $districts = District::all();
        $commands = Command::all();
        $ranks = Rank::all();
        $regions = Region::with('districts')->get();

        return view('users.edit', compact('user', 'roles', 'branches', 'regions', 'departments', 'districts', 'commands', 'ranks'));
    }

    /**
     * Show the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['branch', 'role', 'region', 'department', 'district', 'command', 'rank'])->findOrFail($id);
        return view('users.view', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'branch_id' => 'required|exists:branches,id',
            'designation' => 'required|string|max:255',
            'status' => 'required|string',
            'phone_number' => 'required|string|max:255|unique:users,phone_number,' . $user->id,
            'region_id' => 'required|exists:regions,id',
            'department_id' => 'required|exists:departments,id',
            'district_id' => 'required|exists:districts,id',
            'command_id' => 'required|exists:commands,id',
            'role' => 'required|exists:roles,name',
        ];

        $generateNewPassword = $request->has('change_password');
        $newPassword = null;
        $passwordToSend = 'Not changed during this update';

        if ($generateNewPassword) {
            $newPassword = Str::random(16);
            $rules['password'] = ['nullable', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()];
            $request->merge(['password' => $newPassword, 'password_confirmation' => $newPassword]); // Merge for validation
        } else {
            // If not generating, password fields are not required for update
            $rules['password'] = 'nullable|confirmed';
        }

        $validatedData = $request->validate($rules);

        if ($generateNewPassword) {
            $validatedData['password'] = Hash::make($newPassword);
            $passwordToSend = $newPassword;
        } else {
            unset($validatedData['password']); // Don't update password if not requested
        }

        try {
            $user->update($validatedData);
            $user->syncRoles($request->role);

            // Send SMS with update notification
            $message = "Hello " . $user->name . ", your URA-CRM account has been updated successfully. Username: " . $user->email;
            if ($generateNewPassword) {
                $message .= ", Password: " . $passwordToSend . ". Please log in and you may want to change it.";
            } else {
                $message .= ". Your password remains the same.";
            }
            $this->sendEnquiryapproveSMS($user->phone_number(), $message);

            return redirect()->route('users.index')->with('success', 'User updated successfully' . ($generateNewPassword ? ' and new password sent via SMS.' : '.'));
        } catch (\Exception $e) {
            Log::error('User update failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update user.');
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show the user profile.
     */
    public function profile()
    {
        $user = Auth::user()->load('rank', 'roles');
        return view('users.profile', compact('user'));
    }

    /**
     * Handle the password update request from the user profile.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match our records.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Password updated successfully.');
    }

    /**
     * Sends an SMS message using the provided API.
     *
     * @param string $phone
     * @param string $message
     * @return string|null
     */
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
}