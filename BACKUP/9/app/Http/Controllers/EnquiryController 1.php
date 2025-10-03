<?php

namespace App\Http\Controllers;

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
use App\Models\Command;
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
    
        $allowedRoles = ['general_manager', 'assistant_general_manager', 'superadmin', 'system_admin'];
    
        if ($currentUser->hasAnyRole($allowedRoles)) {
            $enquiries = Enquiry::with(['response', 'region', 'district', 'registeredBy.district', 'registeredBy.region'])
                ->when($type, function ($query) use ($type) {
                    return $query->where('type', $type);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $enquiries = Enquiry::with(['response', 'region', 'district', 'registeredBy.district', 'registeredBy.region'])
                ->where('registered_by', $currentUser->id)
                ->when($type, function ($query) use ($type) {
                    return $query->where('type', $type);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }
    
        $users = User::all();
        return view('enquiries.index', compact('enquiries', 'type', 'users'));
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
               $loanDetails['loan_type'] = $data['loan_type'];
               $loanDetails['loan_category'] = $data['loan_category'];
               // Create a new LoanApplication model with the details
               $model = new LoanApplication($loanDetails);
               break;
        case 'refund':
            $model = new Refund($data);
            break;
        case 'share_enquiry':
            $model = new Share($data);
            break;
        case 'retirement':
            $model = new Retirement($data);
            break;
        case 'deduction_add':
            $model = new Deduction($data);
            break;
        case 'withdraw_savings':
            $model = new Withdrawal([
                'amount' => $data['withdraw_saving_amount'],
                'type' => 'savings',
            ]);
            break;
        case 'withdraw_deposit':
            $model = new Withdrawal([
                'amount' => $data['withdraw_deposit_amount'],
                'type' => 'deposit',
            ]);
            break;
        case 'unjoin_membership':
            $model = new MembershipChange([
                'category' => $data['category'],
                'action' => 'unjoin',
            ]);
            break;
        case 'benefit_from_disasters':
            $model = new Benefit($data);
            break;
        case 'sick_for_30_days':
            $model = new SickLeave($data);
            break;
        case 'residential_disaster':
            $model = new ResidentialDisaster($data);
            break;
        case 'condolences':
            $model = new Condolence($data);
            break;
        case 'injured_at_work':
            $model = new Injury($data);
            break;
        case 'join_membership':
            $model = new Membership($data);
            break;
        case 'ura_mobile':
            $model = new URAMobile($data);
            break;
    }

    if ($model) {
        $model->save();
        $enquiry->enquirable()->associate($model);
        $enquiry->save();
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
            $message .= "Your loan application for Tsh " . number_format($data['loan_amount']) . " is under review.";
            break;
        case 'refund':
            $message .= "Your refund request for Tsh " . number_format($data['refund_amount']) . " has been submitted and is being processed.";
            break;
        case 'share_enquiry':
            $message .= "Your share enquiry for Tsh " . number_format($data['share_amount']) . " has been recorded.";
            break;
        case 'retirement':
            $message .= "Your retirement application set for " . $data['date_of_retirement'] . " has been received.";
            break;
        case 'deduction_add':
            $message .= "Your deduction adjustment from Tsh " . number_format($data['from_amount']) . " to Tsh " . number_format($data['to_amount']) . " has been noted.";
            break;
        case 'withdraw_savings':
            $message .= "Your request to withdraw savings amounting to Tsh " . number_format($data['withdraw_saving_amount']) . " has been noted.";
            break;
        case 'withdraw_deposit':
            $message .= "Your request to withdraw a deposit of Tsh " . number_format($data['withdraw_deposit_amount']) . " has been received.";
            break;
        case 'unjoin_membership':
            $message .= "Your membership cancellation request under the category of " . $data['category'] . " has been processed.";
            break;
        case 'sick_for_30_days':
            $message .= "We have received your request for 30-day sick leave at URA SACCOS LTD. Please call us at 0677 026301 for any assistance.";
            break;
        case 'condolences':
            $message .= "Your condolence request with URA SACCOS LTD has been received. For support, contact us at 0677 026301.";
            break;
        case 'injured_at_work':
            $message .= "We've received your injury report at URA SACCOS LTD. For further help, please reach out to us at 0677 026301.";
            break;
        case 'residential_disaster':
            $message .= "Your request for residential disaster assistance with URA SACCOS LTD is being received. Contact 0677 026301 for more information.";
            break;
        case 'join_membership':
            $message .= "Thank you for your interest in joining URA SACCOS LTD membership. For more details, please call us at 0677 026301.";
            break;
        case 'ura_mobile':
            $message .= "Your request to register in the URA Mobile app has been received. For assistance, please contact us at 0677 026301.";
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
        return view('enquiries.edit', compact('enquiry'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $rules = [
            'date_received' => 'required|date',
            'full_name' => 'required|string|max:255',
            'force_no' => 'required|string|max:255',
            'check_number' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'type' => 'required|in:loan_application,refund,share_enquiry,retirement,deduction_add,withdraw_savings,withdraw_deposit,unjoin_membership,benefit_from_disasters',
        ];

        // Add conditional rules based on the type
        switch ($request->input('type')) {
            case 'loan_application':
                $rules = array_merge($rules, [
                    'loan_type' => 'required|string|max:255',
                    'loan_amount' => 'required|numeric',
                    'loan_duration' => 'required|integer',
                    'loan_category' => 'required|string',
                ]);
                break;

            case 'refund':
                $rules = array_merge($rules, [
                    'refund_amount' => 'required|numeric',
                    'refund_duration' => 'required|integer',
                ]);
                break;

            case 'share_enquiry':
                $rules = array_merge($rules, [
                    'share_amount' => 'required|numeric',
                ]);
                break;

            case 'retirement':
                $rules = array_merge($rules, [
                    'date_of_retirement' => 'required|date',
                    'retirement_amount' => 'required|numeric',
                ]);
                break;

            case 'deduction_add':
                $rules = array_merge($rules, [
                    'from_amount' => 'required|numeric',
                    'to_amount' => 'required|numeric',
                ]);
                break;

            case 'withdraw_savings':
                $rules = array_merge($rules, [
                    'withdraw_saving_amount' => 'required|numeric',
                    'withdraw_saving_reason' => 'required|string|max:255',
                ]);
                break;

            case 'withdraw_deposit':
                $rules = array_merge($rules, [
                    'withdraw_deposit_amount' => 'required|numeric',
                    'withdraw_deposit_reason' => 'required|string|max:255',
                ]);
                break;

            case 'unjoin_membership':
                $rules = array_merge($rules, [
                    'unjoin_reason' => 'required|string|max:255',
                    'unjoin_category' => 'required|in:normal,job_termination',
                ]);
                break;

            case 'benefit_from_disasters':
                $rules = array_merge($rules, [
                    'benefit_amount' => 'required|numeric',
                    'benefit_description' => 'required|string|max:1000',
                    'benefit_remarks' => 'nullable|string|max:1000',
                ]);
                break;

            default:
                break;
        }

        // Validate the request
        $validated = $request->validate($rules);

        // Update the enquiry with validated data
        $enquiry->update($validated);

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

private function logLoanApplicationHistory(LoanApplication $loanApplication, $action)
{
    // Log the action, such as assignment, for the loan application
    // This function would need to implement the logic to log the action in your system
}

private function validateUserRoles($userIds, $enquiryType)
{
    $requiredRole = $this->getRoleForEnquiryType($enquiryType);
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
        // Add other roles and enquiry types as needed
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


    return Excel::download(new MembershipChangeExport($startDate, $endDate), 'membership_changes.xlsx');
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


    return Excel::download(new CondolenceExport($startDate, $endDate), 'condolences.xlsx');
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


    return Excel::download(new DeductionExport($startDate, $endDate), 'deductions.xlsx');
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

    return Excel::download(new LoanApplicationExport($startDate, $endDate), 'loan_applications.xlsx');
}


//error
public function exportRefund(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    return Excel::download(new RefundExport($startDate, $endDate), 'refunds.xlsx');
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

    // Trigger export to Excel with filtered data
    return Excel::download(new ResidentialDisasterExport($startDate, $endDate), 'disasters.xlsx');
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

    // Trigger export to Excel with filtered data
    return Excel::download(new RetirementExport($startDate, $endDate), 'retirement_data.xlsx');
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

    // Trigger the export with the selected date range
    return Excel::download(new ShareExport($startDate, $endDate), 'share_data.xlsx');
}

public function exportSickLeave(Request $request)
{
    // Get the date range from the request
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Trigger the export with the selected date range
    return Excel::download(new SickLeaveExport($startDate, $endDate), 'sickleave_data.xlsx');
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


    // Trigger the export with the selected date range
    return Excel::download(new WithdrawalExport($startDate, $endDate), 'withdrawals_data.xlsx');
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


    // Trigger the export with the selected date range
    return Excel::download(new injuryExport($startDate, $endDate), 'injury_data.xlsx');
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


    // Trigger the export with the selected date range
    return Excel::download(new JoinMembershipExport($startDate, $endDate), 'new_memberships.xlsx');
}
//----------------EXPORT TO EXCEL  ENDS  HERE-----------------------------
}
