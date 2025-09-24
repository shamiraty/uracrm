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
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Start with a base query for users, eager loading necessary relationships
        $query = User::with(['branch', 'role', 'region', 'department', 'district', 'command', 'rank']);

        // --- Apply Filters ---
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->role_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('command_id')) {
            $query->where('command_id', $request->command_id);
        }

        // Get the filtered users
        $users = $query->get();

        // --- Basic Statistics (applied to the filtered $users collection) ---
        $totalUsers = $users->count();
        $activeUsers = $users->where('status', 'active')->count();

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        // Online users (last activity within 5 minutes)
        $onlineThreshold = Carbon::now()->subMinutes(5);
        $onlineUsers = $users->filter(function ($user) use ($onlineThreshold) {
            return $user->last_activity && Carbon::parse($user->last_activity)->greaterThan($onlineThreshold);
        });
        $onlineUsersCount = $onlineUsers->count();

        $loggedInYesterday = $users->filter(function ($user) use ($yesterday) {
            return $user->last_login && Carbon::parse($user->last_login)->isSameDay($yesterday);
        })->count();

        $loggedInToday = $users->filter(function ($user) use ($today) {
            return $user->last_login && Carbon::parse($user->last_login)->isSameDay($today);
        })->count();

        $loggedInThisWeek = $users->filter(function ($user) use ($startOfWeek) {
            return $user->last_login && Carbon::parse($user->last_login)->greaterThanOrEqualTo($startOfWeek);
        })->count();

        $loggedInThisMonth = $users->filter(function ($user) use ($startOfMonth) {
            return $user->last_login && Carbon::parse($user->last_login)->greaterThanOrEqualTo($startOfMonth);
        })->count();

        $loggedInThisYear = $users->filter(function ($user) use ($startOfYear) {
            return $user->last_login && Carbon::parse($user->last_login)->greaterThanOrEqualTo($startOfYear);
        })->count();

        // --- Set Policy Days ---
        $loginDeactivationDays = 90;
        $passwordChangeDeactivationDays = 90;

        // --- Compute Each User's Status ---
        $usersWithStatus = $users->map(function ($user) use ($loginDeactivationDays, $passwordChangeDeactivationDays, $onlineThreshold) {
            $now = Carbon::now();

            // Check if user is online
            $user->is_online = $user->last_activity && Carbon::parse($user->last_activity)->greaterThan($onlineThreshold);

            // LAST LOGIN
            if ($user->last_login) {
                $lastLogin = Carbon::parse($user->last_login);
                $daysSinceLastLogin = $lastLogin->diffInDays($now);
                $expiryDaysLogin = $loginDeactivationDays - $daysSinceLastLogin;
            } else {
                $daysSinceLastLogin = null;
                $expiryDaysLogin = null;
            }

            // LAST PASSWORD CHANGE
            if ($user->last_password_change) {
                $lastPasswordChange = Carbon::parse($user->last_password_change);
                $daysSinceLastPasswordChange = $lastPasswordChange->diffInDays($now);
                $passwordChangeStatusDays = $passwordChangeDeactivationDays - $daysSinceLastPasswordChange;
            } else {
                $daysSinceLastPasswordChange = null;
                $passwordChangeStatusDays = 0;
            }

            // Attach computed values
            $user->days_since_last_login = $daysSinceLastLogin;
            $user->expiry_login_days = $expiryDaysLogin;
            $user->days_since_last_password_change = $daysSinceLastPasswordChange;
            $user->password_change_status_days = $passwordChangeStatusDays;

            return $user;
        });

        // Fetch data for dropdown filters
        $branches = Branch::all();
        $roles = Role::all();
        $regions = Region::all();
        $departments = Department::all();
        $districts = District::all();
        $commands = Command::all();

        return view('users.index', compact(
            'usersWithStatus',
            'totalUsers',
            'activeUsers',
            'onlineUsersCount',
            'onlineUsers',
            'loggedInYesterday',
            'loggedInToday',
            'loggedInThisWeek',
            'loggedInThisMonth',
            'loggedInThisYear',
            'users',
            'branches',
            'roles',
            'regions',
            'departments',
            'districts',
            'commands'
        ));
    }

    /**
     * Generate a secure password with mixed case, numbers, and symbols
     * 
     * @param int $length
     * @return string
     */
    private function generateSecurePassword($length = 8)
    {
        // Define character sets
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%&*';
        
        // Guarantee at least one character from each set
        $password = '';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];
        
        // Fill the rest with random characters from all sets
        $allChars = $lowercase . $uppercase . $numbers . $symbols;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password to randomize positions
        return str_shuffle($password);
    }

    /**
     * Update user's last activity timestamp
     */
    public function updateActivity()
    {
        if (Auth::check()) {
            Auth::user()->update(['last_activity' => now()]);
        }
        return response()->json(['status' => 'success']);
    }

    public function clearOnlineStatus()
    {
        if (Auth::check()) {
            Auth::user()->update(['last_activity' => null]);
        }
        return response()->json(['status' => 'cleared']);
    }
    
    /**
     * Get online users for real-time updates
     */
    public function getOnlineUsers()
    {
        $onlineThreshold = Carbon::now()->subMinutes(5);
        $onlineUsers = User::with(['branch', 'rank'])
            ->where('last_activity', '>', $onlineThreshold)
            ->where('status', 'active')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'branch' => $user->branch->name ?? 'N/A',
                    'rank' => $user->rank->name ?? 'N/A',
                    'last_activity' => $user->last_activity ? Carbon::parse($user->last_activity)->diffForHumans() : 'Never'
                ];
            });

        return response()->json($onlineUsers);
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
            'status' => 'required|in:active,inactive',
            'phone_number' => 'required|string|max:255|unique:users,phone_number',
            'region_id' => 'required|exists:regions,id',
            'department_id' => 'required|exists:departments,id',
            'district_id' => 'required|exists:districts,id',
            'command_id' => 'required|exists:commands,id',
            'role' => 'required|exists:roles,name',
        ]);

        // Generate secure password
        $randomPassword = $this->generateSecurePassword(8);
        $validatedData['password'] = Hash::make($randomPassword);
        
        // Set last_password_change to null since this is a newly generated password
        $validatedData['last_password_change'] = null;

        $user = User::create($validatedData);
        $user->assignRole($request->role);

        // Send SMS with improved message (password only)
        $message = "Habari " . $validatedData['name'] . "! Umepewa akaunti ya URA-CRM. Neno la siri lako ni: " . $randomPassword . ". Tafadhali badilisha neno la siri baada ya kuingia kwa mara ya kwanza. Ahsante.";
        $this->sendEnquiryapproveSMS($validatedData['phone_number'], $message);

        return redirect()->route('users.index')->with('success', 'User created successfully. Password sent via SMS.');
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
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['branch', 'roles', 'region', 'department', 'district', 'command', 'rank'])->findOrFail($id);
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'branch_id' => 'required|exists:branches,id',
            'designation' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'phone_number' => 'required|string|max:255|unique:users,phone_number,' . $user->id,
            'region_id' => 'required|exists:regions,id',
            'department_id' => 'required|exists:departments,id',
            'district_id' => 'required|exists:districts,id',
            'command_id' => 'required|exists:commands,id',
            'role' => 'required|exists:roles,name',
            'generate_password' => 'nullable|boolean',
        ]);

        if (isset($validatedData['generate_password']) && $validatedData['generate_password']) {
            // Generate new secure password
            $randomPassword = $this->generateSecurePassword(8);
            $validatedData['password'] = Hash::make($randomPassword);
            
            // Set last_password_change to null since this is a newly generated password
            $validatedData['last_password_change'] = null;
            
            $user->update($validatedData);
            $user->syncRoles($request->role);
            
            // Send SMS with improved message (password only)
            $message = "Habari " . $validatedData['name'] . "! Neno la siri lako la URA-CRM limebadilishwa. Neno jipya ni: " . $randomPassword . ". Tafadhali badilisha neno hili baada ya kuingia. Ahsante.";
            $this->sendEnquiryapproveSMS($validatedData['phone_number'], $message);
            
            return redirect()->route('users.index')->with('success', 'User updated successfully and new password sent via SMS.');
        } else {
            // Remove password and generate_password from validation data if not generating new password
            unset($validatedData['password']);
            unset($validatedData['generate_password']);
            
            $user->update($validatedData);
            $user->syncRoles($request->role);
            
            // Check if any field was actually updated
            $originalData = $user->getOriginal();
            $updated = false;
            foreach ($validatedData as $key => $value) {
                if (isset($originalData[$key]) && $originalData[$key] != $value) {
                    $updated = true;
                    break;
                }
            }
            
            if ($updated) {
                $message = "Habari " . $validatedData['name'] . "! Taarifa za akaunti yako ya URA-CRM zimebadilishwa. Neno la siri halijabadilika. Ahsante.";
                $this->sendEnquiryapproveSMS($validatedData['phone_number'], $message);
                return redirect()->route('users.index')->with('success', 'User updated successfully. Notification of changes sent via SMS.');
            } else {
                return redirect()->route('users.index')->with('success', 'User updated successfully.');
            }
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
     * Handle the password update request for the logged-in user.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match our records.']);
        }

        // Update password and set last_password_change to current timestamp
        $user->password = Hash::make($request->new_password);
        $user->last_password_change = now();
        $user->save();

        return redirect()->route('profile')->with('success', 'Password updated successfully.');
    }

    /**
     * Send SMS using the provided API.
     *
     * @param string $phone
     * @param string $message
     * @return string|null
     */
    private function sendEnquiryapproveSMS($phone, $message)
    {
        $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
        $apiKey = 'xYz123#';

        $client = new Client();
        try {
            $response = $client->request('POST', $url, [
                'verify' => false,
                'form_params' => [
                    'msisdn' => $phone,
                    'message' => $message,
                    'key' => $apiKey,
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            Log::info("SMS sent response: " . $responseBody);
            return $responseBody;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            Log::error("Failed to send SMS: " . $e->getMessage());
            return null;
        }
    }

    public function getOnlineUsersHtml()
    {
        $onlineThreshold = Carbon::now()->subMinutes(5);
        $onlineUsers = User::with(['branch', 'rank'])
            ->where('last_activity', '>', $onlineThreshold)
            ->where('status', 'active')
            ->get();

        $html = view('users.index', compact('onlineUsers'))->render();
        
        return response()->json([
            'html' => $html,
            'count' => $onlineUsers->count()
        ]);
    }
}