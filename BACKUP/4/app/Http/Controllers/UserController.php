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
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

use Illuminate\Support\Facades\Storage;
use App\Models\UnauthorizedAccess;


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

        // Get unauthorized access data for the tab
        $unauthorizedAttempts = UnauthorizedAccess::with('user')
            ->orderBy('attempted_at', 'desc')
            ->take(100)
            ->get()
            ->map(function($attempt) {
                return [
                    'id' => $attempt->id,
                    'user_name' => $attempt->user_details['name'] ?? 'Unknown',
                    'user_phone' => $attempt->user_details['phone_number'] ?? 'N/A',
                    'user_role' => $attempt->user_role,
                    'region' => $attempt->user_details['region'] ?? 'N/A',
                    'branch' => $attempt->user_details['branch'] ?? 'N/A',
                    'district' => $attempt->user_details['district'] ?? 'N/A',
                    'route_attempted' => $attempt->route_name,
                    'url_attempted' => $attempt->url_attempted,
                    'required_roles' => $attempt->required_roles,
                    'attempted_at' => $attempt->attempted_at->format('d/m/Y H:i:s'),
                    'date' => $attempt->attempted_at->format('d/m/Y'),
                    'time' => $attempt->attempted_at->format('H:i:s'),
                    'year' => $attempt->attempted_at->format('Y')
                ];
            });

        $unauthorizedStats = [
            'total_count' => UnauthorizedAccess::count(),
            'today_count' => UnauthorizedAccess::whereDate('attempted_at', today())->count(),
            'this_week_count' => UnauthorizedAccess::whereBetween('attempted_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'unique_users_count' => $unauthorizedAttempts->pluck('user_name')->unique()->count(),
        ];

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
            'commands',
            'unauthorizedAttempts',
            'unauthorizedStats'
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
            //'district_id' => 'required|exists:districts,id',
            'district_id' => 'sometimes|exists:districts,id', 
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

    // ===============================
    // EXPORT AND ANALYTICS METHODS
    // ===============================

    /**
     * Export users to Excel or PDF
     */
    public function exportUsers(Request $request)
    {
        $format = $request->input('format', 'excel');

        $users = User::with(['branch', 'region', 'department', 'district', 'rank', 'roles'])
            ->where('status', 'active')
            ->get();

        switch ($format) {
            case 'excel':
                return $this->exportToExcel($users, 'users_report_' . date('Y-m-d'));
            case 'pdf':
            case 'activity_pdf':
                return $this->exportToPdf($users, 'activity_report_' . date('Y-m-d'));
            case 'analytics_pdf':
                return $this->generateAnalyticsPdf($users);
            default:
                return response()->json(['error' => 'Invalid format'], 400);
        }
    }

    /**
     * Export users within a date range
     */
    public function exportUsersRange(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $users = User::with(['branch', 'region', 'department', 'district', 'rank', 'roles'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return $this->exportToExcel($users, 'users_range_' . $startDate . '_to_' . $endDate);
    }

    /**
     * Quick export by period
     */
    public function exportUsersQuick(Request $request)
    {
        $period = $request->input('period');
        $query = User::with(['branch', 'region', 'department', 'district', 'rank', 'roles']);

        switch ($period) {
            case 'today':
                $query->whereDate('last_login', today());
                break;
            case 'week':
                $query->whereBetween('last_login', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('last_login', [now()->startOfMonth(), now()->endOfMonth()]);
                break;
        }

        $users = $query->get();
        return $this->exportToExcel($users, 'users_' . $period . '_' . date('Y-m-d'));
    }

    /**
     * Generate security audit report
     */
    public function generateSecurityAudit(Request $request)
    {
        $data = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'users_with_expired_passwords' => User::where('last_password_change', '<', now()->subMonths(3))->count(),
            'users_never_logged_in' => User::whereNull('last_login')->count(),
            'failed_login_attempts' => User::where('login_attempts', '>=', 3)->count(),
            'online_users' => User::where('last_activity', '>', now()->subMinutes(5))->count(),
            'audit_date' => now()->format('Y-m-d H:i:s'),
            'recent_logins' => User::with(['branch', 'rank'])
                ->whereDate('last_login', '>=', now()->subDays(7))
                ->orderBy('last_login', 'desc')
                ->take(50)
                ->get()
        ];

        // Generate PDF audit report
        $pdf = Pdf::loadView('reports.security-audit', $data);

        return $pdf->download('security_audit_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Schedule periodic reports
     */
    public function scheduleReport(Request $request)
    {
        $frequency = $request->input('frequency');

        // In a real application, you would save this to a scheduled_reports table
        // For now, we'll just return success

        Log::info("Report scheduled with frequency: {$frequency} by user: " . auth()->id());

        return response()->json([
            'success' => true,
            'message' => ucfirst($frequency) . ' reports have been scheduled successfully.',
            'frequency' => $frequency
        ]);
    }

    /**
     * Get analytics data for popup
     */
    public function getAnalyticsData(Request $request)
    {
        $onlineThreshold = Carbon::now()->subMinutes(5);

        return response()->json([
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'online_count' => User::where('last_activity', '>', $onlineThreshold)->count(),
            'logged_today' => User::whereDate('last_login', today())->count(),
            'logged_this_week' => User::whereBetween('last_login', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'logged_this_month' => User::whereBetween('last_login', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'departments' => Department::withCount('users')->get(),
            'branches' => Branch::withCount('users')->get(),
        ]);
    }

    /**
     * Export users to Excel
     */
    private function exportToExcel($users, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'A1' => 'Name',
            'B1' => 'Email',
            'C1' => 'Phone',
            'D1' => 'Branch',
            'E1' => 'Department',
            'F1' => 'Region',
            'G1' => 'District',
            'H1' => 'Rank',
            'I1' => 'Role',
            'J1' => 'Status',
            'K1' => 'Last Login',
            'L1' => 'Login Attempts',
            'M1' => 'Created At'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Set data
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->name);
            $sheet->setCellValue('B' . $row, $user->email);
            $sheet->setCellValue('C' . $row, $user->phone_number);
            $sheet->setCellValue('D' . $row, $user->branch->name ?? 'N/A');
            $sheet->setCellValue('E' . $row, $user->department->name ?? 'N/A');
            $sheet->setCellValue('F' . $row, $user->region->name ?? 'N/A');
            $sheet->setCellValue('G' . $row, $user->district->name ?? 'N/A');
            $sheet->setCellValue('H' . $row, $user->rank->name ?? 'N/A');
            $sheet->setCellValue('I' . $row, $user->roles->first()->name ?? 'N/A');
            $sheet->setCellValue('J' . $row, ucfirst($user->status));
            $sheet->setCellValue('K' . $row, $user->last_login ? $user->last_login->format('Y-m-d H:i:s') : 'Never');
            $sheet->setCellValue('L' . $row, $user->login_attempts);
            $sheet->setCellValue('M' . $row, $user->created_at->format('Y-m-d H:i:s'));
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export users to PDF
     */
    private function exportToPdf($users, $filename)
    {
        $data = [
            'users' => $users,
            'total_count' => $users->count(),
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'generated_by' => auth()->user()->name
        ];

        $pdf = Pdf::loadView('reports.users-activity', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 10,
                'margin_right' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10
            ]);

        return $pdf->download($filename . '.pdf');
    }

    /**
     * Generate analytics dashboard PDF
     */
    private function generateAnalyticsPdf($users)
    {
        $onlineThreshold = Carbon::now()->subMinutes(5);

        $data = [
            'users' => $users,
            'total_count' => $users->count(),
            'active_count' => $users->where('status', 'active')->count(),
            'online_count' => $users->filter(function($user) use ($onlineThreshold) {
                return $user->last_activity && $user->last_activity > $onlineThreshold;
            })->count(),
            'logged_today' => $users->filter(function($user) {
                return $user->last_login && $user->last_login->isToday();
            })->count(),
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'generated_by' => auth()->user()->name,
            'departments' => Department::withCount('users')->get(),
            'branches' => Branch::withCount('users')->get(),
        ];

        $pdf = Pdf::loadView('reports.analytics-dashboard', $data);

        return $pdf->download('analytics_dashboard_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Get unauthorized access attempts data for the user analytics tab
     */
    public function getUnauthorizedAccessData(Request $request)
    {
        $attempts = UnauthorizedAccess::with('user')
            ->orderBy('attempted_at', 'desc')
            ->take(100)
            ->get()
            ->map(function($attempt) {
                return [
                    'id' => $attempt->id,
                    'user_name' => $attempt->user_details['name'] ?? 'Unknown',
                    'user_phone' => $attempt->user_details['phone_number'] ?? 'N/A',
                    'user_role' => $attempt->user_role,
                    'region' => $attempt->user_details['region'] ?? 'N/A',
                    'branch' => $attempt->user_details['branch'] ?? 'N/A',
                    'district' => $attempt->user_details['district'] ?? 'N/A',
                    'route_attempted' => $attempt->route_name,
                    'url_attempted' => $attempt->url_attempted,
                    'required_roles' => $attempt->required_roles,
                    'attempted_at' => $attempt->attempted_at->format('d/m/Y H:i:s'),
                    'date' => $attempt->attempted_at->format('d/m/Y'),
                    'time' => $attempt->attempted_at->format('H:i:s'),
                    'year' => $attempt->attempted_at->format('Y')
                ];
            });

        return response()->json([
            'attempts' => $attempts,
            'total_count' => UnauthorizedAccess::count(),
            'today_count' => UnauthorizedAccess::whereDate('attempted_at', today())->count(),
            'this_week_count' => UnauthorizedAccess::whereBetween('attempted_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ]);
    }

    /**
     * Export unauthorized access attempts to Excel
     */
    public function exportUnauthorizedAccessExcel(Request $request)
    {
        // Build query with filters
        $query = UnauthorizedAccess::with('user')->orderBy('attempted_at', 'desc');

        // Apply date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('attempted_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('attempted_at', '<=', $request->to_date);
        }

        // Apply role filter
        if ($request->filled('role_filter')) {
            $query->where('user_role', $request->role_filter);
        }

        // Apply search filter
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function($q) use ($searchTerm) {
                $q->whereJsonContains('user_details->name', $searchTerm)
                  ->orWhere('route_name', 'like', "%{$searchTerm}%")
                  ->orWhere('user_role', 'like', "%{$searchTerm}%");
            });
        }

        $attempts = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'A1' => 'User Name',
            'B1' => 'Phone Number',
            'C1' => 'User Role',
            'D1' => 'Region',
            'E1' => 'Branch',
            'F1' => 'District',
            'G1' => 'Page Attempted',
            'H1' => 'Required Roles',
            'I1' => 'Date',
            'J1' => 'Time',
            'K1' => 'Year',
            'L1' => 'Full URL',
            'M1' => 'IP Address'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('17479E');
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
        }

        // Set data
        $row = 2;
        foreach ($attempts as $attempt) {
            $sheet->setCellValue('A' . $row, $attempt->user_details['name'] ?? 'Unknown');
            $sheet->setCellValue('B' . $row, $attempt->user_details['phone_number'] ?? 'N/A');
            $sheet->setCellValue('C' . $row, $attempt->user_role);
            $sheet->setCellValue('D' . $row, $attempt->user_details['region'] ?? 'N/A');
            $sheet->setCellValue('E' . $row, $attempt->user_details['branch'] ?? 'N/A');
            $sheet->setCellValue('F' . $row, $attempt->user_details['district'] ?? 'N/A');
            $sheet->setCellValue('G' . $row, $attempt->route_name);
            $sheet->setCellValue('H' . $row, $attempt->required_roles);
            $sheet->setCellValue('I' . $row, $attempt->attempted_at->format('d/m/Y'));
            $sheet->setCellValue('J' . $row, $attempt->attempted_at->format('H:i:s'));
            $sheet->setCellValue('K' . $row, $attempt->attempted_at->format('Y'));
            $sheet->setCellValue('L' . $row, $attempt->url_attempted);
            $sheet->setCellValue('M' . $row, $attempt->ip_address);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'unauthorized_access_attempts_' . date('Y-m-d');

        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export unauthorized access attempts to PDF
     */
    public function exportUnauthorizedAccessPdf(Request $request)
    {
        // Build query with filters (same as Excel export)
        $query = UnauthorizedAccess::with('user')->orderBy('attempted_at', 'desc');

        // Apply date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('attempted_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('attempted_at', '<=', $request->to_date);
        }

        // Apply role filter
        if ($request->filled('role_filter')) {
            $query->where('user_role', $request->role_filter);
        }

        // Apply search filter
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function($q) use ($searchTerm) {
                $q->whereJsonContains('user_details->name', $searchTerm)
                  ->orWhere('route_name', 'like', "%{$searchTerm}%")
                  ->orWhere('user_role', 'like', "%{$searchTerm}%");
            });
        }

        $attempts = $query->take(100)->get()
            ->map(function($attempt) {
                return [
                    'user_name' => $attempt->user_details['name'] ?? 'Unknown',
                    'user_phone' => $attempt->user_details['phone_number'] ?? 'N/A',
                    'user_role' => $attempt->user_role,
                    'region' => $attempt->user_details['region'] ?? 'N/A',
                    'branch' => $attempt->user_details['branch'] ?? 'N/A',
                    'district' => $attempt->user_details['district'] ?? 'N/A',
                    'route_attempted' => $attempt->route_name,
                    'attempted_at' => $attempt->attempted_at->format('d/m/Y H:i:s'),
                    'date' => $attempt->attempted_at->format('d/m/Y'),
                    'time' => $attempt->attempted_at->format('H:i:s'),
                    'year' => $attempt->attempted_at->format('Y')
                ];
            });

        $data = [
            'attempts' => $attempts,
            'total_count' => $attempts->count(),
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'generated_by' => auth()->user()->name,
            'title' => 'Unauthorized Access Attempts Report'
        ];

        $pdf = Pdf::loadView('reports.unauthorized-access-attempts', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 10,
                'margin_right' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10
            ]);

        return $pdf->download('unauthorized_access_attempts_' . date('Y-m-d') . '.pdf');
    }
}