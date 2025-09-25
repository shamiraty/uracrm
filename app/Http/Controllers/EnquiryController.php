<?php

namespace App\Http\Controllers;
use App\Exports\LoanOfficerApplicationsExport;
//----------------EXPORT TO EXCEL  STARTS  HERE-----------------------------
use App\Exports\MembershipChangeExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CondolenceExport;
use App\Exports\DeductionExport;
use App\Exports\LoanApplicationExport;
use App\Exports\RefundExport;
use App\Exports\ResidentialDisasterExport;
use App\Exports\RetirementExport;
use App\Exports\ShareExport;
use App\Exports\injuryExport;
use App\Exports\JoinMembershipExport;
use App\Exports\SickLeaveExport;
use App\Exports\WithdrawalExport;
use App\Exports\AllEnquiryExport;
use App\Models\Command;
use App\Models\Branch;
use App\Models\District;
use Carbon\Carbon;


//----------------EXPORT TO EXCEL  ENDS  HERE-----------------------------

use Log;
use App\Models\User;
use App\Models\Region;
use GuzzleHttp\Client;
use App\Models\Enquiry;
use App\Models\File;
use App\Models\Payroll;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\LoanApplication;
use Illuminate\Support\Facades\Http;
use App\Models\LoanApplicationHistory;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Validator;
// use App\Models\Folio;

// use App\Models\Enquiry;
// use App\Models\LoanApplication;
use App\Models\Refund;
use App\Models\Share;
use App\Models\Retirement;
use App\Models\Deduction;
use App\Models\Withdrawal;
use App\Models\MembershipChange;
use App\Models\Benefit;
use App\Models\Condolence;
use App\Models\SickLeave;
use App\Models\Injury;
use App\Models\ResidentialDisaster;
use App\Models\Membership;
use App\Models\URAMobile;
use App\Models\Folio;
// use App\Models\Notification;
// use Illuminate\Http\Request;

class EnquiryController extends Controller
{





    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $type = $request->query('type');
        $status = $request->query('status');
        $search = $request->query('search');
        $perPage = $request->query('per_page', 15);

        $allowedRoles = ['registrar_hq', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin'];

        // Build query based on user roles
        $query = Enquiry::with(['response', 'region', 'district', 'registeredBy.district', 'registeredBy.region', 'users', 'branch']);

        if (!$currentUser->hasAnyRole($allowedRoles)) {
            $query->where('registered_by', $currentUser->id);
        }

        // Apply filters
        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            // Handle special statuses
            if ($status === 'pending_overdue') {
                $threeDaysAgo = Carbon::now()->subWeekdays(3);
                $query->where('status', 'pending')
                      ->where('created_at', '<', $threeDaysAgo);
            } else {
                $query->where('status', $status);
            }
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('check_number', 'like', "%{$search}%")
                  ->orWhere('force_no', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $enquiries = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Calculate analytics
        $analytics = $this->getEnquiryAnalytics($type, $currentUser);

        $branches = Branch::all();
        $districts = District::all();
        $commands = Command::all();
        // Get users based on enquiry type requirements
        $requiredRole = $type ? $this->getRoleForEnquiryType($type) : null;

        if ($requiredRole) {
            $users = User::whereHas('roles', function($query) use ($requiredRole) {
                $query->where('name', $requiredRole);
            })->get();
        } else {
            // Default to accountant and loanofficer if no specific role
            $users = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['accountant', 'loanofficer']);
            })->get();
        }

        return view('enquiries.index', compact(
            'enquiries', 'type', 'status', 'search', 'users', 'branches',
            'districts', 'commands', 'analytics', 'currentUser'
        ));
    }

    private function getEnquiryAnalytics($type = null, $currentUser = null)
    {
        $threeDaysAgo = Carbon::now()->subWeekdays(3);
        $query = Enquiry::query();

        // Apply type filter if specified
        if ($type) {
            $query->where('type', $type);
        }

        // Apply user filter - registrar_hq can see all, others see only their registered enquiries
        if ($currentUser && !$currentUser->hasRole('registrar_hq')) {
            $query->where('registered_by', $currentUser->id);
        }

        // Clone base query for different counts
        $baseQuery = clone $query;

        return [
            'total' => $baseQuery->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'assigned' => (clone $query)->where('status', 'assigned')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'pending_overdue' => (clone $query)->where('status', 'pending')
                                              ->where('created_at', '<', $threeDaysAgo)
                                              ->count(),
            'status_breakdown' => (clone $query)->selectRaw('status, COUNT(*) as count')
                                               ->groupBy('status')
                                               ->pluck('count', 'status')
                                               ->toArray(),
            'type_breakdown' => (clone $query)->selectRaw('type, COUNT(*) as count')
                                             ->groupBy('type')
                                             ->pluck('count', 'type')
                                             ->toArray()
        ];
    }

    // Bulk Operations Methods
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'enquiry_ids' => 'required|array',
            'user_id' => 'required|exists:users,id',
            'enquiry_types' => 'array'
        ]);

        // Check if user has permission (registrar_hq)
        if (!auth()->user()->hasRole('registrar_hq')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $enquiries = Enquiry::whereIn('id', $request->enquiry_ids)
                           ->whereIn('status', ['pending', 'pending_overdue'])
                           ->get();

        // Validate that the selected user can handle all enquiry types
        $user = User::find($request->user_id);
        $failedValidations = [];

        foreach ($enquiries as $enquiry) {
            if (!$this->validateUserRoles([$request->user_id], $enquiry->type)) {
                $requiredRole = $this->getRoleForEnquiryType($enquiry->type);
                $failedValidations[] = "Enquiry #{$enquiry->id} ({$enquiry->type}) requires " . ($requiredRole ?: 'accountant/loanofficer') . " role";
            }
        }

        if (!empty($failedValidations)) {
            return response()->json([
                'success' => false,
                'message' => 'Role validation failed: ' . implode(', ', $failedValidations)
            ]);
        }

        // All validations passed, proceed with assignments
        $currentUser = auth()->id();
        foreach ($enquiries as $enquiry) {
            $enquiry->users()->sync([$request->user_id => ['assigned_by' => $currentUser]]);
            $enquiry->update(['status' => 'assigned']);
        }

        return response()->json([
            'success' => true,
            'message' => count($enquiries) . ' enquiries assigned successfully'
        ]);
    }

    public function bulkReassign(Request $request)
    {
        $request->validate([
            'enquiry_ids' => 'required|array',
            'user_id' => 'required|exists:users,id',
            'enquiry_types' => 'array'
        ]);

        if (!auth()->user()->hasRole('registrar_hq')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $enquiries = Enquiry::whereIn('id', $request->enquiry_ids)
                           ->whereIn('status', ['assigned', 'pending_overdue'])
                           ->get();

        // Validate that the selected user can handle all enquiry types
        $user = User::find($request->user_id);
        $failedValidations = [];

        foreach ($enquiries as $enquiry) {
            if (!$this->validateUserRoles([$request->user_id], $enquiry->type)) {
                $requiredRole = $this->getRoleForEnquiryType($enquiry->type);
                $failedValidations[] = "Enquiry #{$enquiry->id} ({$enquiry->type}) requires " . ($requiredRole ?: 'accountant/loanofficer') . " role";
            }
        }

        if (!empty($failedValidations)) {
            return response()->json([
                'success' => false,
                'message' => 'Role validation failed: ' . implode(', ', $failedValidations)
            ]);
        }

        // All validations passed, proceed with reassignments
        $currentUser = auth()->id();
        foreach ($enquiries as $enquiry) {
            // Remove old assignments and assign to new user
            $enquiry->users()->sync([$request->user_id => ['assigned_by' => $currentUser]]);
            $enquiry->update(['status' => 'assigned']);
        }

        return response()->json([
            'success' => true,
            'message' => count($enquiries) . ' enquiries reassigned successfully'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'enquiry_ids' => 'required|array'
        ]);

        $currentUser = auth()->user();

        // Get enquiries that can be deleted (pending/rejected and owned by user)
        $enquiries = Enquiry::whereIn('id', $request->enquiry_ids)
                           ->where(function($query) use ($currentUser) {
                               $query->where('registered_by', $currentUser->id)
                                     ->whereIn('status', ['pending', 'rejected']);
                           })
                           ->get();

        $deletedCount = $enquiries->count();

        // Delete enquiries and their relationships
        foreach ($enquiries as $enquiry) {
            $enquiry->users()->detach();
            $enquiry->delete();
        }

        return response()->json([
            'success' => true,
            'message' => $deletedCount . ' enquiries deleted successfully'
        ]);
    }

public function fetchPayroll($check_number)
{
    $payroll = Payroll::where('check_number', $check_number)->first();

    if ($payroll) {
        // Assuming bank_name format is "BankName - Location"
        $bankNameParts = explode(' - ', $payroll->bank_name);
        $mainBankName = $bankNameParts[0]; // This will take "NMB" from "NMB - Zanzibar"

        // Modify the bank_name in the $payroll object before returning it
        $payroll->bank_name = $mainBankName;

        return response()->json($payroll);
    } else {
        return response()->json(null);
    }
}

public function create(Request $request, $check_number = null)
{
    $commands = Command::all(); // Fetch all commands for the admin/registrar_hq/superuser

    $payrollData = null;
    if (!is_null($check_number)) {
        $payrollData = Payroll::where('check_number', $check_number)->first();
    }
    $regions = Region::with('districts')->get();
     $files=File::all();
    $activeStep = 1;

    return view('enquiries.create', compact('payrollData', 'regions','activeStep','files','commands'));
}



//     public function store(Request $request)
// {


//         $rules = [
//             'date_received' => 'required|date',
//             'full_name' => 'required|string|max:255',
//             'force_no' => 'required|string|max:255',
//             'check_number' => 'required|string|max:255',
//             'account_number' => 'required|string|max:255',
//             'bank_name' => 'required|string|max:255',
//             'district_id' => 'required|string|max:255',
//             'phone' => 'required|string|max:255',
//             'region_id' => 'required|string|max:255',

//             'type' => 'required|in:loan_application,refund,share_enquiry,retirement,deduction_add,withdraw_savings,withdraw_deposit,unjoin_membership,benefit_from_disasters',
//             'basic_salary' => 'required|numeric',
//         'allowances' => 'required|numeric',
//         'take_home' => 'required|numeric'
//         ];

//         switch ($request->input('type')) {
//             case 'loan_application':
//                 $rules = array_merge($rules, [
//                     'loan_type' => 'required|string|max:255',
//                     'loan_amount' => 'required|numeric',
//                     'loan_duration' => 'required|integer',
//                     'loan_category' => 'required|string',
//                 ]);
//                 break;

//             case 'refund':
//                 $rules = array_merge($rules, [
//                     'refund_amount' => 'required|numeric',
//                     'refund_duration' => 'required|integer',
//                 ]);
//                 break;

//             case 'share_enquiry':
//                 $rules = array_merge($rules, [
//                     'share_amount' => 'required|numeric',
//                 ]);
//                 break;

//             case 'retirement':
//                 $rules = array_merge($rules, [
//                     'date_of_retirement' => 'required|date',

//                 ]);
//                 break;

//             case 'deduction_add':
//                 $rules = array_merge($rules, [
//                     'from_amount' => 'required|numeric',
//                     'to_amount' => 'required|numeric',
//                 ]);
//                 break;

//             case 'withdraw_savings':
//                 $rules = array_merge($rules, [
//                     'withdraw_saving_amount' => 'required|numeric',

//                 ]);
//                 break;

//             case 'withdraw_deposit':
//                 $rules = array_merge($rules, [
//                     'withdraw_deposit_amount' => 'required|numeric',

//                 ]);
//                 break;

//             case 'unjoin_membership':
//                 $rules = array_merge($rules, [

//                     'category' => 'required|in:normal,job_termination',
//                 ]);
//                 break;

//             case 'benefit_from_disasters':
//                 $rules = array_merge($rules, [
//                     'benefit_amount' => 'required|numeric',
//                     'benefit_description' => 'required|string|max:1000',
//                     'benefit_remarks' => 'nullable|string|max:1000',
//                 ]);
//                 break;

//             default:
//                 break;
//         }



//         $validated = $request->validate($rules);
//         $enquiryData = array_merge($validated, [
//             'branch_id' => auth()->user()->branch_id,
//             'registered_by' => auth()->id(),
//         ]);

//         // Create the Enquiry
//         $enquiry = Enquiry::create($enquiryData);

//         if ($request->hasFile('file_path')) {
//             $file = $request->file('file_path');
//             if (!$file->isValid()) {
//                 \Log::error('File upload error', ['errors' => $file->getError()]);
//                 return back()->withErrors('File upload failed! Please try again.');
//             }

//             $filename = time() . '.' . $file->getClientOriginalExtension();
//             $destinationPath = 'attachments';  // This should be relative to the public directory
//             $fullPath = public_path($destinationPath);

//             // Check if directory exists, if not, create it
//             if (!file_exists($fullPath)) {
//                 mkdir($fullPath, 0777, true);
//             }


// // Move the file from temporary to permanent location
// $file->move($fullPath, $filename);
// $filePath= $destinationPath . '/' . $filename;  // Save this path to store in the database
// $folio = new Folio([
//     'file_path' => $filePath,
//     'folioable_id' => $enquiry->id,
//     'folioable_type' => 'App\Models\Enquiry',
//    'file_id' => $request->file_id
// ]);
// $folio->save();

//         }
//         $fileRecord = File::find($request->file_id);
//         if (!$fileRecord) {
//             return back()->withErrors('File record not found.');
//         }


//         if (!$enquiry) {
//             return back()->withErrors('Failed to create the enquiry.');
//         }
//         if ($enquiry) {
//             // Construct a custom message based on the type of enquiry
//             $message = "Hello " . $validated['full_name'] . ", ";
//             switch ($validated['type']) {
//                 case 'loan_application':
//                     $message .= "Your loan application for Tsh " . number_format($validated['loan_amount']) . " has been received and is under review. For further information, please contact 0677 026301";
//                     break;
//                 case 'refund':
//                     $message .= "Your refund request for Tsh " . number_format( $validated['refund_amount']) . " has been submitted. For further information, please contact 0677 026301";
//                     break;
//                 case 'share_enquiry':
//                     $message .= "Your share enquiry for Tsh " . number_format( $validated['share_amount']) . " has been recorded. For further information, please contact 0677 026301";
//                     break;
//                 case 'retirement':
//                     $message .= "Your retirement application set for " . $validated['date_of_retirement'] . " has been processed. For further information, please contact 0677 026301";
//                     break;
//                 case 'deduction_add':
//                     $message .= "Your deduction adjustment from Tsh " . number_format( $validated['from_amount']) . " to " . number_format($validated['to_amount']) . " has been updated. For further information, please contact 0677 026301";
//                     break;
//                 case 'withdraw_savings':
//                     $message .= "Your request to withdraw savings amounting to Tsh " . number_format( $validated['withdraw_saving_amount']) . " has been noted. For further information, please contact 0677 026301";
//                     break;
//                 case 'withdraw_deposit':
//                     $message .= "Your request to withdraw a deposit of Tsh " . number_format( $validated['withdraw_deposit_amount']) . " has been received. For further information, please contact 0677 026301";
//                     break;
//                 case 'unjoin_membership':
//                     $message .= "Your membership cancellation request under " . $validated['category'] . " category has been processed. For further information, please contact 0677 026301";
//                     break;
//                 case 'benefit_from_disasters':
//                     $message .= "Your disaster benefit claim for Tsh " . number_format( $validated['benefit_amount']) . " due to " . $validated['benefit_description'] . " is under review. For further information, please contact 0677 026301." ;
//                     break;
//                 default:
//                     $message .= "Your enquiry has been received. We will contact you shortly. For further information, please contact 0677 026301.";
//                     break;
//             }
//             $phone = $validated['phone']; // Ensure 'phone' is the correct field name
//             $this->sendEnquirySMS($phone, $message);
//         }
//  // Create a notification
//  Notification::create([
//     'type' => 'enquiry_registered',
//     'message' => "A new enquiry for {$validated['full_name']} has been registered. For further information, please contact 0677 026301",
// ]);
//         return redirect()->route('enquiries.index', ['type' => $request->input('type')])
//                          ->with([
//                             'message' => 'Enquiry submitted successfully!',
//                             'alert-type' => 'success'
//                         ]);
//     }
public function store(Request $request)
{
    // Step 1: Define and validate the request data
    $validated = $request->validate($this->getValidationRules($request->input('type')));

    // Step 2: Prepare the enquiry data
    $enquiryData = $this->prepareEnquiryData($validated);
    $enquiry = Enquiry::create($enquiryData);

    // Step 3: Create and associate the specific model for the enquiry
    $this->createAssociatedModel($enquiry, $validated['type'], $validated);

    // Step 4: Handle file upload if applicable
    if ($request->hasFile('file_path')) {
        $this->handleFileUpload($request, $enquiry);
    }
// // Step 5: Process loan application if applicable
// if ($validated['type'] === 'loan_application') {
//     $this->processSalaryLoan($enquiry);
// }
     // Step 6: Send SMS notification
     $phone = $validated['phone']; // Ensure 'phone' is the correct field name
     $message = $this->constructMessageBasedOnType($validated); // Construct the message
     $this->sendEnquirySMS($phone, $message); // Send the SMS

     // Step 7: Create a notification
     Notification::create([
         'type' => 'enquiry_registered',
         'message' => "A new enquiry for {$validated['full_name']} has been registered. For further information, please contact 0677 026301",
     ]);
    return redirect()->route('enquiries.index', ['type' => $request->input('type')])
                     ->with('message', 'Enquiry submitted successfully!')
                     ->with('alert-type', 'success');
}

private function getValidationRules($type)
{
    // Base validation rules
    $baseRules = [
        'date_received' => 'required|date',
        'full_name' => 'required|string|max:255',
        'force_no' => 'required|string|max:255',
        'check_number' => 'required|string|max:255',
        'account_number' => 'required|string|max:255',
        'bank_name' => 'required|string|max:255',
        'district_id' => 'required|string|max:255',
        'phone' => 'required|string|max:255',
        'region_id' => 'required|string|max:255',
        'type' => 'required|in:loan_application,refund,share_enquiry,retirement,deduction_add,withdraw_savings,withdraw_deposit,unjoin_membership,sick_for_30_days,residential_disaster,condolences,injured_at_work,join_membership,ura_mobile',
        'basic_salary' => 'required|numeric',
        'allowances' => 'required|numeric',
        'take_home' => 'required|numeric',
        //this takes command from template if login user is registrar_hq
        'command_id' => 'nullable|exists:commands,id',  // Validation for command_id
    ];

    return array_merge($baseRules, $this->getTypeSpecificRules($type));
}

private function getTypeSpecificRules($type)
{
    switch ($type) {
        case 'loan_application':
            return [
                'loan_type' => 'required|string|max:255',
                'loan_amount' => 'required|numeric',
                'loan_duration' => 'required|integer',
                'loan_category' => 'required|string',
            ];
        case 'refund':
            return [
                'refund_amount' => 'required|numeric',
                'refund_duration' => 'required|integer',
            ];
        case 'share_enquiry':
            return [
                'share_amount' => 'required|numeric',
            ];
        case 'retirement':
            return [
                'date_of_retirement' => 'required|date',
            ];
        case 'deduction_add':
            return [
                'from_amount' => 'required|numeric',
                'to_amount' => 'required|numeric',
            ];
        case 'withdraw_savings':
            return [
                'withdraw_saving_amount' => 'required|numeric',
            ];
        case 'withdraw_deposit':
            return [
                'withdraw_deposit_amount' => 'required|numeric',
            ];
        case 'unjoin_membership':
            return [
                'category' => 'required|in:normal,job_termination',
            ];
        case 'sick_for_30_days':
            return [
                'startdate' => 'required|date',
                'enddate' => 'required|date',
            ];
        case 'residential_disaster':
            return [
                'disaster_type' => 'required|string|max:1000',
            ];
        case 'condolences':
            return [
                'dependent_member_type' => 'required|string|max:1000',
                'gender' => 'required|string|max:1000',
            ];
        case 'injured_at_work':
            return [
                'description' => 'required|string|max:1000',
            ];
        case 'join_membership':
            return [
                'membership_status' => 'required|string|max:1000',
            ];
        case 'ura_mobile':
            return [
                'mobile_number' => 'nullable|string|max:15' // Example validation for mobile number
            ];
        default:
            return [];
    }
}

/*
private function prepareEnquiryData($validated)
{
    return array_merge($validated, [
        'branch_id' => auth()->user()->branch_id,
        'command_id' => auth()->user()->command_id,
        'registered_by' => auth()->id(),
    ]);
}
*/

private function prepareEnquiryData($validated)
{
    // Check if the command_id was selected in the form, otherwise use the current user's command_id
    //this takes command from template if login user is registrar_hq
    //if command not selected  from form,  it takes  current login user command
    $commandId = $validated['command_id'] ?? auth()->user()->command_id;

    return array_merge($validated, [
        'branch_id' => auth()->user()->branch_id,
        'command_id' => $commandId,
        'registered_by' => auth()->id(),
    ]);
}

private function createAssociatedModel(Enquiry $enquiry, $type, $data)
{
    $model = null;
    switch ($type) {
        case 'loan_application':
            // $model = new LoanApplication($data);
              // Create loan application and calculate details
               // Calculate loan details
               $loanDetails = $this->calculateLoanableAmount($enquiry);
               // Include loan_type and loan_category from the data
               $loanDetails['loan_type'] = $data['loan_type'] ?? null;
               $loanDetails['loan_category'] = $data['loan_category'] ?? null;
               $loanDetails['enquiry_id'] = $enquiry->id;
               // Create a new LoanApplication model with the details
               $model = new LoanApplication($loanDetails);
               break;
        case 'refund':
            $model = new Refund(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
        case 'share_enquiry':
            $model = new Share(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
        case 'retirement':
            $model = new Retirement(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
        case 'deduction_add':
            $model = new Deduction(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
        case 'withdraw_savings':
            $model = new Withdrawal([
                'enquiry_id' => $enquiry->id,
                'amount' => $data['withdraw_saving_amount'] ?? 0,
                'type' => 'savings',
                'reason' => $data['withdraw_saving_reason'] ?? 'None',
            ]);
            break;
        case 'withdraw_deposit':
            $model = new Withdrawal([
                'enquiry_id' => $enquiry->id,
                'amount' => $data['withdraw_deposit_amount'] ?? 0,
                'type' => 'deposit',
                'reason' => $data['withdraw_deposit_reason'] ?? 'None',
            ]);
            break;
        case 'unjoin_membership':
            $model = new MembershipChange([
                'enquiry_id' => $enquiry->id,
                'category' => $data['category'] ?? 'normal',
                'action' => 'unjoin',
            ]);
            break;
        case 'benefit_from_disasters':
            $model = new Benefit(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
        case 'sick_for_30_days':
            $model = new SickLeave(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
        case 'residential_disaster':
            $model = new ResidentialDisaster(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
        case 'condolences':
            $model = new Condolence(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
        case 'injured_at_work':
            $model = new Injury(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
        case 'join_membership':
            $model = new MembershipChange([
                'enquiry_id' => $enquiry->id,
                'membership_status' => $data['membership_status'] ?? 'civilian',
                'action' => 'join',
            ]);
            break;
        case 'ura_mobile':
            $model = new URAMobile(array_merge($data, ['enquiry_id' => $enquiry->id]));
            break;
    }

    if ($model) {
        // Ensure all models have enquiry_id set
        if (!isset($model->enquiry_id)) {
            $model->enquiry_id = $enquiry->id;
        }
        $model->save();
    }
}

private function updateOrCreateAssociatedModel(Enquiry $enquiry, $type, $data)
{
    switch ($type) {
        case 'loan_application':
            $enquiry->loanApplication()->updateOrCreate(
                ['enquiry_id' => $enquiry->id],
                [
                    'loan_type' => $data['loan_type'] ?? null,
                    'loan_amount' => $data['loan_amount'] ?? 0,
                    'loan_duration' => $data['loan_duration'] ?? 0,
                    'loan_category' => $data['loan_category'] ?? null,
                ]
            );
            break;

        case 'withdraw_savings':
            $enquiry->withdrawals()->updateOrCreate(
                ['enquiry_id' => $enquiry->id, 'type' => 'savings'],
                [
                    'amount' => $data['withdraw_saving_amount'] ?? 0,
                    'reason' => $data['withdraw_saving_reason'] ?? 'None',
                ]
            );
            break;

        case 'withdraw_deposit':
            $enquiry->withdrawals()->updateOrCreate(
                ['enquiry_id' => $enquiry->id, 'type' => 'deposit'],
                [
                    'amount' => $data['withdraw_deposit_amount'] ?? 0,
                    'reason' => $data['withdraw_deposit_reason'] ?? 'None',
                ]
            );
            break;

        case 'refund':
            $enquiry->refund()->updateOrCreate(
                ['enquiry_id' => $enquiry->id],
                [
                    'refund_amount' => $data['refund_amount'] ?? 0,
                    'refund_duration' => $data['refund_duration'] ?? 0,
                ]
            );
            break;

        case 'share_enquiry':
            $enquiry->share()->updateOrCreate(
                ['enquiry_id' => $enquiry->id],
                [
                    'share_amount' => $data['share_amount'] ?? 0,
                ]
            );
            break;

        case 'retirement':
            $enquiry->retirement()->updateOrCreate(
                ['enquiry_id' => $enquiry->id],
                [
                    'date_of_retirement' => $data['date_of_retirement'] ?? null,
                ]
            );
            break;

        case 'deduction_add':
            $enquiry->deduction()->updateOrCreate(
                ['enquiry_id' => $enquiry->id],
                [
                    'from_amount' => $data['from_amount'] ?? 0,
                    'to_amount' => $data['to_amount'] ?? 0,
                ]
            );
            break;

        case 'condolences':
            $enquiry->condolence()->updateOrCreate(
                ['enquiry_id' => $enquiry->id],
                [
                    'dependent_member_type' => $data['dependent_member_type'] ?? null,
                    'gender' => $data['gender'] ?? null,
                ]
            );
            break;

        case 'injured_at_work':
            $enquiry->injury()->updateOrCreate(
                ['enquiry_id' => $enquiry->id],
                [
                    'description' => $data['description'] ?? null,
                ]
            );
            break;

        case 'sick_for_30_days':
            $enquiry->sickLeave()->updateOrCreate(
                ['enquiry_id' => $enquiry->id],
                [
                    'startdate' => $data['startdate'] ?? null,
                    'enddate' => $data['enddate'] ?? null,
                ]
            );
            break;

        case 'unjoin_membership':
            $enquiry->membershipChanges()->updateOrCreate(
                ['enquiry_id' => $enquiry->id, 'action' => 'unjoin'],
                [
                    'reason' => $data['unjoin_reason'] ?? null,
                    'category' => $data['unjoin_category'] ?? null,
                ]
            );
            break;

        case 'join_membership':
            $enquiry->membershipChanges()->updateOrCreate(
                ['enquiry_id' => $enquiry->id, 'action' => 'join'],
                [
                    'reason' => $data['join_reason'] ?? null,
                    'category' => $data['join_category'] ?? null,
                ]
            );
            break;

        case 'residential_disaster':
            $enquiry->residentialDisaster()->updateOrCreate(
                ['enquiry_id' => $enquiry->id],
                [
                    'disaster_type' => $data['disaster_type'] ?? null,
                ]
            );
            break;
    }
}

/**
 * Clean up old child table data when enquiry type changes
 */
private function cleanupOldChildTableData(Enquiry $enquiry, $oldType)
{
    switch ($oldType) {
        case 'loan_application':
            $enquiry->loanApplication()->delete();
            break;

        case 'withdraw_savings':
            $enquiry->withdrawals()->where('type', 'savings')->delete();
            break;

        case 'withdraw_deposit':
            $enquiry->withdrawals()->where('type', 'deposit')->delete();
            break;

        case 'refund':
            $enquiry->refund()->delete();
            break;

        case 'share_enquiry':
            $enquiry->share()->delete();
            break;

        case 'retirement':
            $enquiry->retirement()->delete();
            break;

        case 'deduction_add':
            $enquiry->deduction()->delete();
            break;

        case 'condolences':
            $enquiry->condolence()->delete();
            break;

        case 'injured_at_work':
            $enquiry->injury()->delete();
            break;

        case 'sick_for_30_days':
            $enquiry->sickLeave()->delete();
            break;

        case 'unjoin_membership':
            $enquiry->membershipChanges()->where('action', 'unjoin')->delete();
            break;

        case 'join_membership':
            $enquiry->membershipChanges()->where('action', 'join')->delete();
            break;

        case 'residential_disaster':
            $enquiry->residentialDisaster()->delete();
            break;

        case 'benefit_from_disasters':
            $enquiry->benefit()->delete();
            break;

        case 'ura_mobile':
            $enquiry->uraMobile()->delete();
            break;

        default:
            // For any other types, try to clean up common relationships
            break;
    }
}

// private function handleFileUpload(Request $request, Enquiry $enquiry)
// {
//     if (!$request->hasFile('file_path')) {
//         return; // If no file, nothing to do
//     }

//     $file = $request->file('file_path');

//     if (!$file->isValid()) {
//         \Log::error('File upload error', ['errors' => $file->getError()]);
//         throw new \Exception('File upload failed! Please try again.'); // Throwing an exception instead of redirecting
//     }

//     $filename = time() . '.' . $file->getClientOriginalExtension();
//     $destinationPath = 'attachments'; // This should be relative to the public directory

//     // Use Laravel's Storage facade to handle file saving
//     $path = $file->storeAs($destinationPath, $filename, 'public'); // Ensure the 'public' disk is configured in your filesystems.php

//     // Create a new Folio entry linked to the enquiry
//     $enquiry->folios()->create([
//         'file_path' => $path, // Store the path returned by storeAs
//         'folioable_id' => $enquiry->id,
//         'folioable_type' => 'App\Models\Enquiry',
//         'file_id' => $request->file_id // Ensure this field is managed correctly
//     ]);
// }
private function handleFileUpload(Request $request, Enquiry $enquiry)
{
    if (!$request->hasFile('file_path')) {
        return; // If no file, nothing to do
    }

    $file = $request->file('file_path');

    if (!$file->isValid()) {
        \Log::error('File upload error', ['errors' => $file->getError()]);
        throw new \Exception('File upload failed! Please try again.');
    }

    $filename = time() . '.' . $file->getClientOriginalExtension();
    $destinationPath = public_path('attachments');

    // Ensure the directory exists
    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }

    // Move the file to the public/attachments directory
    $file->move($destinationPath, $filename);

    // Save the path relative to the public directory
    $path = 'attachments/' . $filename;

    // Create a new Folio entry linked to the enquiry
    $enquiry->folios()->create([
        'file_path' => $path,
        'folioable_id' => $enquiry->id,
        'folioable_type' => 'App\Models\Enquiry',
        'file_id' => $request->file_id // Ensure this field is managed correctly
    ]);
}


private function sendNotifications(Enquiry $enquiry, $validated)
{
    $message = $this->constructMessageBasedOnType($validated);

    Notification::create([
        'type' => 'enquiry_registered',
        'message' => "A new enquiry for {$validated['full_name']} has been registered. Further information will follow."
    ]);
}

private function constructMessageBasedOnType($data)
{
    $message = "Hello " . $data['full_name'] . ", your enquiry has been received. ";
    switch ($data['type']) {
        case 'loan_application':
            $message .= "Your loan application for Tsh " . number_format($data['loan_amount']) . " is under review. For further information, please contact 0677 026301";
            break;
        case 'refund':
            $message .= "Your refund request for Tsh " . number_format( $data['refund_amount']) . " is under review. For further information, please contact 0677 026301";
            break;
        case 'share_enquiry':
            $message .= "Your share enquiry for Tsh " . number_format( $data['share_amount']) . " is under review. For further information, please contact 0677 026301";
            break;
        case 'retirement':
            $message .= "Your retirement application set for " . $data['date_of_retirement'] . " is under review. For further information, please contact 0677 026301";
            break;
        case 'deduction_add':
            $message .= "Your deduction adjustment from Tsh " . number_format( $data['from_amount']) . " to " . number_format($data['to_amount']) . " is under review. For further information, please contact 0677 026301";
            break;
        case 'withdraw_savings':
            $message .= "Your request to withdraw savings amounting to Tsh " . number_format( $data['withdraw_saving_amount']) . " is under review. For further information, please contact 0677 026301";
            break;
        case 'withdraw_deposit':
            $message .= "Your request to withdraw a deposit of Tsh " . number_format($data['withdraw_deposit_amount']) . " is under review.";
            break;
        case 'unjoin_membership':
            $message .= "Your membership cancellation request under " . $data['category'] . " is under review. For further information, please contact 0677 026301";
            break;
        case 'sick_for_30_days':
            $message .= "We have received your request for 30-day sick leave at URA SACCOS LTD. Please call us at 0677 026301 for any assistance.";
            break;
        case 'condolences':
            $message .= "Your condolence request with URA SACCOS LTD is under review. For support, contact us at 0677 026301.";
            break;
        case 'injured_at_work':
            $message .= "We've received your injury report at URA SACCOS LTD. For further help, please reach out to us at 0677 026301.";
            break;
        case 'residential_disaster':
            $message .= "Your request for " . $data['disaster_type'] . " Disaster is under review. For further information, please contact 0677 026301." ;
            break;
        case 'join_membership':
            $message .= "Thank you for your interest in joining URA SACCOS LTD membership. For more details, please call us at 0677 026301.";
            break;
        case 'ura_mobile':
            $message .= "Your request to register in the URA Mobile app is under review. For assistance, please contact us at 0677 026301.";
            break;
        default:
            $message .= "Thank you for reaching out. We will contact you shortly.";
            break;
    }
    return $message;
}

    private function sendEnquirySMS($phone, $message)
    {
        $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
        $apiKey = 'xYz123#';  // Use the non-encoded key as it worked in your script

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

    public function show(Enquiry $enquiry)
    {
        // Eager load related data
        $enquiry->load(['region', 'district', 'users', 'folios', 'assignedUsers', 'registeredBy.district', 'registeredBy.region','registeredBy.command',]);
        $users = User::all();
        return view('enquiries.show', compact('enquiry', 'users'));
    }
    


    // Show the form for editing the specified resource
    public function edit(Enquiry $enquiry)
    {
        // Load all possible relationships to get existing data
        $enquiry->load([
            'loanApplication',
            'share',
            'retirement',
            'deduction',
            'refund',
            'withdrawals',
            'withdrawal', // Add singular withdrawal relationship
            'membershipChanges',
            'condolence',
            'injury',
            'residentialDisaster',
            'sickLeave',
            'uraMobile',
            'benefit' // Add benefit relationship
        ]);

        $files = File::all();
        $regions = Region::with('districts')->get();

        return view('enquiries.edit', compact('enquiry', 'files', 'regions'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $rules = [
            'date_received' => 'sometimes|date',
            'full_name' => 'sometimes|string|max:255',
            'force_no' => 'sometimes|string|max:255',
            'check_number' => 'sometimes|string|max:255',
            'account_number' => 'sometimes|string|max:255',
            'bank_name' => 'sometimes|string|max:255',
            'district_id' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'region_id' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:loan_application,refund,share_enquiry,retirement,deduction_add,withdraw_savings,withdraw_deposit,unjoin_membership,sick_for_30_days,residential_disaster,condolences,injured_at_work,join_membership,ura_mobile',
        ];

        // Add conditional rules based on the type
        switch ($request->input('type')) {
            case 'loan_application':
                $rules = array_merge($rules, [
                    'loan_type' => 'sometimes|string|max:255',
                    'loan_amount' => 'sometimes|numeric',
                    'loan_duration' => 'sometimes|integer',
                    'loan_category' => 'sometimes|string',
                ]);
                break;

            case 'refund':
                $rules = array_merge($rules, [
                    'refund_amount' => 'sometimes|numeric',
                    'refund_duration' => 'sometimes|integer',
                ]);
                break;

            case 'share_enquiry':
                $rules = array_merge($rules, [
                    'share_amount' => 'sometimes|numeric',
                ]);
                break;

            case 'retirement':
                $rules = array_merge($rules, [
                    'date_of_retirement' => 'sometimes|date',
                    'retirement_amount' => 'sometimes|numeric',
                ]);
                break;

            case 'deduction_add':
                $rules = array_merge($rules, [
                    'from_amount' => 'sometimes|numeric',
                    'to_amount' => 'sometimes|numeric',
                ]);
                break;

            case 'withdraw_savings':
                $rules = array_merge($rules, [
                    'withdraw_saving_amount' => 'sometimes|numeric',
                    'withdraw_saving_reason' => 'sometimes|string|max:255',
                ]);
                break;

            case 'withdraw_deposit':
                $rules = array_merge($rules, [
                    'withdraw_deposit_amount' => 'sometimes|numeric',
                    'withdraw_deposit_reason' => 'sometimes|string|max:255',
                ]);
                break;

            case 'unjoin_membership':
                $rules = array_merge($rules, [
                    'unjoin_reason' => 'sometimes|string|max:255',
                    'unjoin_category' => 'sometimes|in:normal,job_termination',
                ]);
                break;

            case 'benefit_from_disasters':
                $rules = array_merge($rules, [
                    'benefit_amount' => 'sometimes|numeric',
                    'benefit_description' => 'sometimes|string|max:1000',
                    'benefit_remarks' => 'nullable|string|max:1000',
                ]);
                break;

            default:
                break;
        }

        // Validate the request
        $validated = $request->validate($rules);

        // Check if enquiry type has changed
        $oldType = $enquiry->type;
        $newType = $validated['type'] ?? $oldType;

        // Update the enquiry with validated data
        $enquiry->update($validated);

        // If enquiry type changed, clean up old child table data
        if ($oldType !== $newType) {
            $this->cleanupOldChildTableData($enquiry, $oldType);
        }

        // Update associated models if type-specific data is provided
        if (isset($validated['type'])) {
            $this->updateOrCreateAssociatedModel($enquiry, $validated['type'], $validated);
        }

        // Handle file upload if applicable
        if ($request->hasFile('file_path')) {
            $this->handleFileUpload($request, $enquiry);
        }

        // Redirect with success message
        return Redirect::route('enquiries.index')->with('success', 'Enquiry updated successfully!');
    }

    // Remove the specified resource from storage
    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return Redirect::back()->with('success', 'Enquiry deleted successfully!');
    }

    // Send SMS to the given phone number
    private function sendSMS($to, $message)
    {
        try {
            $apiKey = 'YOUR_API_KEY';
            $apiUrl = 'https://api.smsprovider.com/send';

            $response = Http::post($apiUrl, [
                'apiKey' => $apiKey,
                'to' => $to,
                'message' => $message
            ]);

            // Log the response from SMS provider
            Log::info('SMS sent: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to send SMS: ' . $e->getMessage());
        }
    }
public function changeStatus(Request $request, Enquiry $enquiry)
{
    $action = $request->action;

    if ($action == 'approve') {
        $enquiry->approve();
        Notification::create([
            'type' => 'enquiry_approved',
            'message' => "Enquiry #{$enquiry->id} has been approved.",
        ]);
    } elseif ($action == 'reject') {
        $enquiry->reject();
        Notification::create([
            'type' => 'enquiry_rejected',
            'message' => "Enquiry #{$enquiry->id} has been rejected.",
        ]);
    } elseif ($action == 'assign') {
        $enquiry->assign();
        Notification::create([
            'type' => 'enquiry_assigned',
            'message' => "Enquiry #{$enquiry->id} has been assigned.",
        ]);
    }

    return redirect()->back()->with('status', 'Enquiry status updated');
}

// public function assignUsersToEnquiry(Request $request, $enquiryId)
// {
//     $request->validate([
//         'user_ids' => 'required|array',
//         'user_ids.*' => 'exists:users,id',
//     ]);

//     $enquiry = Enquiry::with('users')->findOrFail($enquiryId);

//     if (!$this->validateUserRoles($request->user_ids, $enquiry->type)) {
//         return back()->with([
//             'message' => 'One or more users are not authorized to handle this type of enquiry.',
//             'alert-type' => 'error'
//         ]);
//     }

//     $currentUser = auth()->id();
//     $syncData = [];
//     foreach ($request->user_ids as $userId) {
//         $syncData[$userId] = ['assigned_by' => $currentUser];
//     }

//     $enquiry->users()->sync($syncData);

//     if ($enquiry->type === 'loan_application' && $enquiry->loan_category === 'salary_loan') {
//         // $this->processSalaryLoan($enquiry);
//         // $this->logLoanApplicationHistory($loanApplication, 'Assigned');
//         $loanApplication = $this->processSalaryLoan($enquiry);  // Ensure this method returns the LoanApplication instance
//         $this->logLoanApplicationHistory($loanApplication, 'Assigned');
//     }

//     $enquiry->update(['status' => 'assigned']);
//     return back()->with([
//         'message' => 'Users have been successfully assigned to the enquiry and any special processing has been completed.',
//         'alert-type' => 'success'
//     ]);
// }
//     private function validateUserRoles($userIds, $enquiryType)
// {
//     $requiredRole = $this->getRoleForEnquiryType($enquiryType);
//     $users = User::whereIn('id', $userIds)->get();

//     return $users->every(function ($user) use ($requiredRole) {
//         return $user->hasRole($requiredRole); // Assuming you're using Spatie's Permission package
//     });
// }
// private function getRoleForEnquiryType($enquiryType)
// {
//     $roleMap = [
//         'loan_application' => 'loanofficer', // Only loan officers can process loan enquiries
//         'refund' => 'accountant', // Accountants handle refunds and other financial transactions
//         // Add other roles and enquiry types as needed
//     ];

//     return $roleMap[$enquiryType] ?? null;
// }


public function assignUsersToEnquiry(Request $request, $enquiryId)
{
    $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'exists:users,id',
    ]);

    $enquiry = Enquiry::with(['users', 'enquirable'])->findOrFail($enquiryId);

    if (!$this->validateUserRoles($request->user_ids, $enquiry->type)) {
        return back()->with([
            'message' => 'One or more users are not authorized to handle this type of enquiry.',
            'alert-type' => 'error'
        ]);
    }

    $currentUser = auth()->id();
    $syncData = [];
    foreach ($request->user_ids as $userId) {
        $syncData[$userId] = ['assigned_by' => $currentUser];
    }

    $enquiry->users()->sync($syncData);

    // Assuming the LoanApplication is already created, simply log the assignment if it's a salary loan
    // if ($enquiry->type === 'loan_application' && $enquiry->enquirable->loan_category === 'salary_loan') {
    //     $this->logLoanApplicationHistory($enquiry->enquirable, 'Assigned');
    // }
    if ($enquiry->type === 'loan_application' && $enquiry->enquirable && $enquiry->enquirable instanceof LoanApplication && $enquiry->enquirable->loan_category === 'salary_loan') {
        $this->logLoanApplicationHistory($enquiry->enquirable, 'Assigned');
    }

    $enquiry->update(['status' => 'assigned']);

    return back()->with([
        'message' => 'Users have been successfully assigned to the enquiry and any special processing has been completed.',
        'alert-type' => 'success'
    ]);
}

public function reassignUsersToEnquiry(Request $request, $enquiryId)
{
    $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'exists:users,id',
    ]);

    $enquiry = Enquiry::with(['users', 'enquirable'])->findOrFail($enquiryId);

    if (!$this->validateUserRoles($request->user_ids, $enquiry->type)) {
        return back()->with([
            'message' => 'One or more users are not authorized to handle this type of enquiry.',
            'alert-type' => 'error'
        ]);
    }

    $currentUser = auth()->id();

    // Use sync to replace assignments (this removes old and adds new)
    $syncData = [];
    foreach ($request->user_ids as $userId) {
        $syncData[$userId] = ['assigned_by' => $currentUser];
    }
    $enquiry->users()->sync($syncData);

    // Log if it's a salary loan - same logic as assign
    if ($enquiry->type === 'loan_application' && $enquiry->enquirable && $enquiry->enquirable instanceof LoanApplication && $enquiry->enquirable->loan_category === 'salary_loan') {
        $this->logLoanApplicationHistory($enquiry->enquirable, 'Reassigned');
    }

    $enquiry->update(['status' => 'assigned']);

    return back()->with([
        'message' => 'Users have been successfully reassigned to the enquiry and any special processing has been completed.',
        'alert-type' => 'success'
    ]);
}

private function logLoanApplicationHistory(LoanApplication $loanApplication, $action)
{
    // Log the action, such as assignment, for the loan application
    // This function would need to implement the logic to log the action in your system
}

private function validateUserRoles($userIds, $enquiryType)
{
    $requiredRole = $this->getRoleForEnquiryType($enquiryType);

    // If no specific role required, allow accountant and loanofficer
    if (!$requiredRole) {
        $allowedRoles = ['accountant', 'loanofficer'];
        $users = User::whereIn('id', $userIds)->get();
        return $users->every(function ($user) use ($allowedRoles) {
            return $user->hasAnyRole($allowedRoles);
        });
    }

    // Check specific role requirement
    $users = User::whereIn('id', $userIds)->get();
    return $users->every(function ($user) use ($requiredRole) {
        return $user->hasRole($requiredRole);
    });
}

private function getRoleForEnquiryType($enquiryType)
{
    $roleMap = [
        'loan_application' => 'loanofficer',
        'refund' => 'accountant',
        'share_enquiry' => 'accountant',
        'retirement' => 'accountant',
        'deduction_add' => 'accountant',
        'withdraw_savings' => 'accountant',
        'withdraw_deposit' => 'accountant',
        'unjoin_membership' => 'accountant',
        'condolences' => 'loanofficer',
        'injured_at_work' => 'loanofficer',
        'sick_for_30_days' => 'loanofficer',
        'benefit_from_disasters' => 'loanofficer',
        'join_membership' => 'accountant',
        'ura_mobile' => 'superadmin',
        // Default to null for other types (allows accountant/loanofficer)
    ];

    return $roleMap[$enquiryType] ?? null;
}


// private function processSalaryLoan(Enquiry $enquiry)
// {
//     $loanDetails = $this->calculateLoanableAmount($enquiry); // Calculate loanable amount

//     // Create the LoanApplication model
//     $loanApplication = new LoanApplication($loanDetails);

//     // Associate the loan application with the enquiry using polymorphic relationship
//     $enquiry->enquirable()->associate($loanApplication); // Assuming the polymorphic relation is defined
//     $enquiry->save(); // Save the enquiry with the associated loan application

//     // Save the LoanApplication model
//     $loanApplication->save();

//     return $loanApplication; // Return the created or updated loan application
// }

private function processSalaryLoan(Enquiry $enquiry)
{
    $loanDetails = $this->calculateLoanableAmount($enquiry); // Calculate loanable amount

    // Create the LoanApplication model
    $loanApplication = new LoanApplication($loanDetails);
    $loanApplication->save(); // Save the LoanApplication model first to get an ID

    // Associate the loan application with the enquiry
    $enquiry->enquirable()->associate($loanApplication);
    $enquiry->save(); // Save the enquiry with the associated loan application

    return $loanApplication; // Return the created or updated loan application
}

    private function calculateLoanableAmount($enquiry)
    {
        $basicSalary = $enquiry->basic_salary;
        $allowances = [$enquiry->allowances];
        $takeHome = $enquiry->take_home;



        $numberOfMonths = 48; // You can set this dynamically if needed

$oneThirdSalary = $basicSalary / 3;
$totalAllowances = array_sum($allowances);
$loanableTakeHome = $takeHome - ($oneThirdSalary + $totalAllowances);
$annualInterestRate = 12;
$monthlyInterestRate = $annualInterestRate / 100 / 12;

$loanApplicable = ($loanableTakeHome * (pow(1 + $monthlyInterestRate, $numberOfMonths) - 1)) / ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfMonths));

// Corrected formula for monthly deduction
$monthlyDeduction = $loanApplicable * ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfMonths)) / (pow(1 + $monthlyInterestRate, $numberOfMonths) - 1);

$totalLoanWithInterest = $monthlyDeduction * $numberOfMonths;
$totalInterest = $totalLoanWithInterest - $loanApplicable;
$processingFee = $loanApplicable * 0.0025;
$insurance = $loanApplicable * 0.01;
$disbursementAmount = $loanApplicable - ($processingFee + $insurance);

        return [
            'loan_amount' => $loanApplicable,
            'loan_duration' => $numberOfMonths,
            'interest_rate' => 12, // Annual percentage rate
            'monthly_deduction' => $monthlyDeduction,
            'total_loan_with_interest' => $totalLoanWithInterest,
            'total_interest' => $totalInterest,
            'processing_fee' => $processingFee,
            'insurance' => $insurance,
            'disbursement_amount' => $disbursementAmount,
            'status' => 'pending' // Assuming default status
        ];
    }


    // private function logLoanApplicationHistory(LoanApplication $loanApplication, $action)
    // {
    //     LoanApplicationHistory::create([
    //         'user_id' => auth()->id(),
    //         'loan_application_id' => $loanApplication->id,
    //         'loan_amount' => $loanApplication->loan_amount,
    //         'loan_duration' => $loanApplication->loan_duration,
    //         'monthly_deduction' => $loanApplication->monthly_deduction,
    //         'total_loan_with_interest' => $loanApplication->total_loan_with_interest,
    //         'total_interest' => $loanApplication->total_interest,
    //         'processing_fee' => $loanApplication->processing_fee,
    //         'insurance' => $loanApplication->insurance,
    //         'disbursement_amount' => $loanApplication->disbursement_amount,
    //         'action_taken' => $action,
    //     ]);
    // }

public function unassignUserFromEnquiry($enquiryId, $userId)
{
    $enquiry = Enquiry::findOrFail($enquiryId);
    $enquiry->users()->detach($userId); // Remove specific user assignment

    return back()->with('success', 'User unassigned from enquiry successfully.');
}

// public function myAssignedEnquiries()
// {
//     $userId = auth()->id();
//     $enquiries = Enquiry::whereHas('assignedUsers', function ($query) use ($userId) {
//         $query->where('users.id', $userId);
//     })
//     ->with(['loanApplication', 'payment', 'assignedUsers', 'region', 'district'])
//     ->get();

//     // Add a log here to check if loan applications are being loaded
//     \Log::info('Enquiries with loan applications:', $enquiries->toArray());

//     return view('enquiries.my_enquiries', compact('enquiries'));
// }
// public function myAssignedEnquiries()
// {
//     $userId = auth()->id(); // Ensure the user is authenticated
//     if (!$userId) {
//         return redirect()->route('login')->with('error', 'Please log in to view your assignments.');
//     }

//     $enquiries = Enquiry::whereHas('assignedUsers', function ($query) use ($userId) {
//         $query->where('user_id', $userId); // Corrected from 'users.id' to 'user_id'
//     })
//     ->with([
//         'loanApplication', // Ensure this is a correct relationship if it's polymorphic
//         'payment',         // Assuming this is a direct relationship
//         'assignedUsers',   // Loads all users assigned to each enquiry
//         'region',          // Direct relationship
//         'district'         // Direct relationship
//     ])
//     ->get();

//     return view('enquiries.my_enquiries', compact('enquiries'));
// }
// public function myAssignedEnquiries()
// {
//     $userId = auth()->id();
//     if (!$userId) {
//         return redirect()->route('login')->with('error', 'Please log in to view your assignments.');
//     }

//     $enquiries = Enquiry::whereHas('assignedUsers', function ($query) use ($userId) {
//         $query->where('user_id', $userId);
//     })
//     ->with(['enquirable' => function ($query) {
//         $query->where('enquirable_type', LoanApplication::class);  // Filter to include only LoanApplications
//     }, 'payment', 'assignedUsers', 'region', 'district'])
//     ->get();

//     return view('enquiries.my_enquiries', compact('enquiries'));
// }

// public function myAssignedEnquiries()
// {
//     $userId = auth()->id();
//     if (!$userId) {
//         return redirect()->route('login')->with('error', 'Please log in to view your assignments.');
//     }

//     // Retrieve all enquiries assigned to the user
//     $enquiries = Enquiry::whereHas('assignedUsers', function ($query) use ($userId) {
//         $query->where('user_id', $userId);
//     })
//     ->with(['enquirable', 'payment', 'assignedUsers', 'region', 'district']) // Load all related models
//     ->get();

//     // Optionally filter the collection to include only those with LoanApplication
//     $enquiries = $enquiries->filter(function ($enquiry) {
//         return $enquiry->enquirable_type === LoanApplication::class;
//     });

//     return view('enquiries.my_enquiries', compact('enquiries'));
// }
public function myAssignedEnquiries()
{
    $userId = auth()->id();
    $enquiries = Enquiry::whereHas('assignedUsers', function ($query) use ($userId) {
        $query->where('users.id', $userId);
    })
    ->with(['enquirable', 'assignedUsers', 'region', 'district'])
    ->get();

    // Log each enquiry with its associated enquirable type for debugging
    foreach ($enquiries as $enquiry) {
        \Log::info('Enquiry Type:', [
            'id' => $enquiry->id,
            'type' => $enquiry->type,
            'enquirable_type' => optional($enquiry->enquirable)->getTable(), // Safely get the table name of the enquirable
            'enquirable_details' => optional($enquiry->enquirable)->toArray() // Safely log details of the enquirable
        ]);
    }
    $files=File::all();
    return view('enquiries.my_enquiries', compact('enquiries','files'));
}

//----------------EXPORT TO EXCEL  STARTS  HERE-----------------------------
public function exportMembershipChanges(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }

    $status = $request->input('status'); // Get the status from the request
    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID
    
    return Excel::download(new MembershipChangeExport($startDate, $endDate,$status,$branchId, $commandId), 'membership_changes.xlsx');
}

public function exportCondolences(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }
    $status = $request->input('status'); // Get the status from the request
    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID

    return Excel::download(new CondolenceExport($startDate, $endDate,$status,$branchId, $commandId), 'condolences.xlsx');
}


public function export(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }

    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID
    return Excel::download(new DeductionExport($startDate, $endDate,$branchId, $commandId), 'deductions.xlsx');
}

public function exportLoanApplication(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'quarterly_q1':
                    $startDate = Carbon::create($now->year, 1, 1)->toDateString();
                    $endDate = Carbon::create($now->year, 3, 31)->toDateString();
                    break;
                case 'quarterly_q2':
                    $startDate = Carbon::create($now->year, 4, 1)->toDateString();
                    $endDate = Carbon::create($now->year, 6, 30)->toDateString();
                    break;
                case 'quarterly_q3':
                    $startDate = Carbon::create($now->year, 7, 1)->toDateString();
                    $endDate = Carbon::create($now->year, 9, 30)->toDateString();
                    break;
                case 'quarterly_q4':
                    $startDate = Carbon::create($now->year, 10, 1)->toDateString();
                    $endDate = Carbon::create($now->year, 12, 31)->toDateString();
                    break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
                
        }
    }
    $status = $request->input('status'); // Get the status from the request
    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID

    return Excel::download(new LoanApplicationExport($startDate, $endDate, $status,$branchId, $commandId), 'loan_applications.xlsx');
}


//error
public function exportRefund(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'quarterly_q1':
                    $startDate = Carbon::create($now->year, 1, 1)->toDateString();
                    $endDate = Carbon::create($now->year, 3, 31)->toDateString();
                    break;
                case 'quarterly_q2':
                    $startDate = Carbon::create($now->year, 4, 1)->toDateString();
                    $endDate = Carbon::create($now->year, 6, 30)->toDateString();
                    break;
                case 'quarterly_q3':
                    $startDate = Carbon::create($now->year, 7, 1)->toDateString();
                    $endDate = Carbon::create($now->year, 9, 30)->toDateString();
                    break;
                case 'quarterly_q4':
                    $startDate = Carbon::create($now->year, 10, 1)->toDateString();
                    $endDate = Carbon::create($now->year, 12, 31)->toDateString();
                    break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
                
        }
    }
    $status = $request->input('status'); // Get the status from the request
    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID

    return Excel::download(new RefundExport($startDate, $endDate,$status,$branchId, $commandId), 'refunds.xlsx');
}

public function ResidentialDisasterExport(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }
    $status = $request->input('status'); // Get the status from the request
    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID
    // Trigger export to Excel with filtered data
    return Excel::download(new ResidentialDisasterExport($startDate, $endDate,$status,$branchId, $commandId), 'disasters.xlsx');
}
public function exportRetirement(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
        
    }
    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID
    // Trigger export to Excel with filtered data
    return Excel::download(new RetirementExport($startDate, $endDate,$branchId, $commandId), 'retirement_data.xlsx');
}
public function exportShare(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }

    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID
    
    
    // Trigger the export with the selected date range
    return Excel::download(new ShareExport($startDate, $endDate,$branchId, $commandId), 'share_data.xlsx');
}

public function exportSickLeave(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }

    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID

    // Trigger the export with the selected date range
    return Excel::download(new SickLeaveExport($startDate, $endDate,$branchId, $commandId), 'sickleave_data.xlsx');
}
public function WithdrawalExport(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }

    $status = $request->input('status'); // Get the status from the request
    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID
    // Trigger the export with the selected date range
    return Excel::download(new WithdrawalExport($startDate, $endDate,$status,$branchId, $commandId), 'withdrawals_data.xlsx');
}

























public function InjuryExport(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }

    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID
    
    // Trigger the export with the selected date range
    return Excel::download(new injuryExport($startDate, $endDate,$branchId, $commandId), 'injury_data.xlsx');
}



public function JoinMembershipExport(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }
    $status = $request->input('status'); // Get the status from the request
    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID
    

    // Trigger the export with the selected date range
    return Excel::download(new JoinMembershipExport($startDate, $endDate,$status,$branchId, $commandId), 'new_memberships.xlsx');
}




public function allEnquiriesExport(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $frequency = $request->input('frequency');

    // Calculate start and end dates based on frequency
    if ($frequency) {
        $now = Carbon::now();

        switch ($frequency) {
            case 'weekly':
                $startDate = $now->startOfWeek()->toDateString();
                $endDate = $now->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $now->startOfMonth()->toDateString();
                $endDate = $now->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $startDate = $now->startOfYear()->toDateString();
                $endDate = $now->endOfYear()->toDateString();
                break;
            case 'quarterly':
                $startDate = $now->startOfQuarter()->toDateString();
                $endDate = $now->endOfQuarter()->toDateString();
                break;
                case 'half_year_1_6':
                    // First half of the year (1-6 months)
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->month(6)->endOfMonth()->toDateString(); // End of June
                    break;
                case 'half_year_6_12':
                    // Second half of the year (7-12 months)
                    $startDate = $now->month(7)->startOfMonth()->toDateString(); // Start of July
                    $endDate = $now->endOfYear()->toDateString(); // End of December
                    break;
                
            case 'half_year':
                if ($now->month <= 6) {
                    $startDate = $now->startOfYear()->toDateString();
                    $endDate = $now->endOfMonth()->toDateString();
                } else {
                    $startDate = $now->startOfMonth()->toDateString();
                    $endDate = $now->endOfYear()->toDateString();
                }
                break;
                
        }
    }
    $status = $request->input('status'); // Get the status from the request
    $branchId = $request->input('branch'); // Get branch ID
    $commandId = $request->input('command'); // Get command ID
    

    // Trigger the export with the selected date range
    return Excel::download(new AllEnquiryExport($startDate, $endDate,$status,$branchId, $commandId), 'allEnquiries.xlsx');
}
//----------------EXPORT TO EXCEL  ENDS  HERE-----------------------------







 

public function exportLoanOfficerApplications()
{
    return Excel::download(new LoanOfficerApplicationsExport, 'loan_officer_applications.csv');
}







}
