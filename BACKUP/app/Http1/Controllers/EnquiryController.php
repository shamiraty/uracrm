<?php

namespace App\Http\Controllers;


use Log;
use App\Models\User;
use App\Models\Region;
use GuzzleHttp\Client;
use App\Models\Enquiry;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\LoanApplication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Validator;



class EnquiryController extends Controller
{


    public function index(Request $request)
    {
        $type = $request->query('type');

        if ($type) {
            $enquiries = Enquiry::with('response')->where('type', $type)->get();
        } else {
            $enquiries = Enquiry::with('response')->get();
        }
        $users = User::all();
        return view('enquiries.index', compact('enquiries', 'type','users'));
    }


    // Show the form for creating a new resource
    public function create()
 {
    $regions = Region::with('districts')->get();
     return view('enquiries.create',compact('regions'));
 }


    public function store(Request $request)
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
            'file_path'=> 'required|file|mimes:pdf|max:2048',
            'type' => 'required|in:loan_application,refund,share_enquiry,retirement,deduction_add,withdraw_savings,withdraw_deposit,unjoin_membership,benefit_from_disasters',
            'basic_salary' => 'required|numeric',
        'allowances' => 'required|numeric',
        'take_home' => 'required|numeric'
        ];

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

                ]);
                break;

            case 'withdraw_deposit':
                $rules = array_merge($rules, [
                    'withdraw_deposit_amount' => 'required|numeric',

                ]);
                break;

            case 'unjoin_membership':
                $rules = array_merge($rules, [

                    'category' => 'required|in:normal,job_termination',
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



        $validated = $request->validate($rules);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            if (!$file->isValid()) {
                \Log::error('File upload error', ['errors' => $file->getError()]);
                return back()->withErrors('File upload failed! Please try again.');
            }

            $filename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'attachments';  // This should be relative to the public directory
            $fullPath = public_path($destinationPath);

            // Check if directory exists, if not, create it
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            // Move the file from temporary to permanent location
            $file->move($fullPath, $filename);
            $validated['file_path'] = $destinationPath . '/' . $filename;  // Save this path to store in the database
        }


        $enquiry = Enquiry::create($validated);

        if ($enquiry) {
            // Construct a custom message based on the type of enquiry
            $message = "Hello " . $validated['full_name'] . ", ";

            switch ($validated['type']) {
                case 'loan_application':
                    $message .= "Your loan application for " . $validated['loan_amount'] . " has been received and is under review.";
                    break;
                case 'refund':
                    $message .= "Your refund request for " . $validated['refund_amount'] . " has been submitted.";
                    break;
                case 'share_enquiry':
                    $message .= "Your share enquiry for " . $validated['share_amount'] . " has been recorded.";
                    break;
                case 'retirement':
                    $message .= "Your retirement application set for " . $validated['date_of_retirement'] . " has been processed.";
                    break;
                case 'deduction_add':
                    $message .= "Your deduction adjustment from " . $validated['from_amount'] . " to " . $validated['to_amount'] . " has been updated.";
                    break;
                case 'withdraw_savings':
                    $message .= "Your request to withdraw savings amounting to " . $validated['withdraw_saving_amount'] . " has been noted.";
                    break;
                case 'withdraw_deposit':
                    $message .= "Your request to withdraw a deposit of " . $validated['withdraw_deposit_amount'] . " has been received.";
                    break;
                case 'unjoin_membership':
                    $message .= "Your membership cancellation request under " . $validated['category'] . " category has been processed.";
                    break;
                case 'benefit_from_disasters':
                    $message .= "Your disaster benefit claim for " . $validated['benefit_amount'] . " due to " . $validated['benefit_description'] . " is under review. " ;
                    break;
                default:
                    $message .= "Your enquiry has been received. We will contact you shortly.";
                    break;
            }

            $phone = $validated['phone']; // Ensure 'phone' is the correct field name
            $this->sendEnquirySMS($phone, $message);
        }
 // Create a notification
 Notification::create([
    'type' => 'enquiry_registered',
    'message' => "A new enquiry for {$validated['full_name']} has been registered.",
]);
        return redirect()->route('enquiries.index', ['type' => $request->input('type')])
                         ->with('success', 'Enquiry submitted successfully!');
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





    // Display the specified resource
    public function show(Enquiry $enquiry)
    {
        return view('enquiries.show', compact('enquiry'));
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




public function assignUsersToEnquiry(Request $request, $enquiryId)
    {

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',

        ]);

        // $enquiry = Enquiry::findOrFail($enquiryId);
        $enquiry = Enquiry::with('users')->findOrFail($enquiryId);
         // Validate that all users have the appropriate role for the enquiry type
    if (!$this->validateUserRoles($request->user_ids, $enquiry->type)) {
        return back()->with('error', 'One or more users are not authorized to handle this type of enquiry.');
    }
        $enquiry->users()->sync($request->user_ids);


        // if ($enquiry->loan_category === 'salary_loan') {
        //     $this->processSalaryLoan($enquiry);
        // }
        // Process special conditions like salary loans
    if ($enquiry->type === 'loan_application' && $enquiry->loan_category === 'salary_loan') {
        $this->processSalaryLoan($enquiry);
    }
        $enquiry->update(['status' => 'assigned']);
        return back()->with('success', 'Users have been successfully assigned to the enquiry and any special processing has been completed.');
    }
    private function validateUserRoles($userIds, $enquiryType)
{
    $requiredRole = $this->getRoleForEnquiryType($enquiryType);
    $users = User::whereIn('id', $userIds)->get();

    return $users->every(function ($user) use ($requiredRole) {
        return $user->hasRole($requiredRole); // Assuming you're using Spatie's Permission package
    });
}
private function getRoleForEnquiryType($enquiryType)
{
    $roleMap = [
        'loan_application' => 'loanofficer', // Only loan officers can process loan enquiries
        'refund' => 'accountant', // Accountants handle refunds and other financial transactions
        // Add other roles and enquiry types as needed
    ];

    return $roleMap[$enquiryType] ?? null;
}
    private function processSalaryLoan($enquiry)
    {
        $loanDetails = $this->calculateLoanableAmount($enquiry);

        LoanApplication::updateOrCreate(
            ['enquiry_id' => $enquiry->id],
            $loanDetails
        );
    }

    private function calculateLoanableAmount($enquiry)
    {
        $basicSalary = $enquiry->basic_salary;
        $allowances = [$enquiry->allowances];
        $takeHome = $enquiry->take_home;



        $oneThirdSalary = $basicSalary / 3;
        $totalAllowances = array_sum($allowances);
        $loanableTakeHome = $takeHome - ($oneThirdSalary + $totalAllowances);
        $monthlyInterestRate = 12 / 100 / 12; // 12% annual rate

        $numberOfMonths = 48; // Loan duration in months
        $loanApplicable = ($loanableTakeHome * (pow(1 + $monthlyInterestRate, $numberOfMonths) - 1)) / ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfMonths));

        $monthlyDeduction = $loanApplicable / $numberOfMonths;
        $totalLoanWithInterest = $monthlyDeduction * $numberOfMonths;
        $totalInterest = $totalLoanWithInterest - $loanApplicable;
        $processingFee = $loanApplicable * 0.0025; // 0.25% of the loan amount
        $insurance = $loanApplicable * 0.01; // 1% of the loan amount
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

public function unassignUserFromEnquiry($enquiryId, $userId)
{
    $enquiry = Enquiry::findOrFail($enquiryId);
    $enquiry->users()->detach($userId); // Remove specific user assignment

    return back()->with('success', 'User unassigned from enquiry successfully.');
}

public function myAssignedEnquiries()
{
    $user = auth()->user();  // Get the currently authenticated user
    // $enquiries = $user->enquiries()->get();  // Retrieve all enquiries assigned to this user
    $enquiries = $user->enquiries()->with(['loanApplication', 'payment', 'users'])->get();
    return view('enquiries.my_enquiries', compact('enquiries'));
}


}
