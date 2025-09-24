<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LoanApplicationHistory;
use App\Models\Member; // Ensure the correct model is used

class LoanController extends Controller
{






    /**
     * Process a loan application.
     *
     * @param  LoanApplication $loanApplication
     * @return \Illuminate\Http\Response
     */
    
public function process(LoanApplication $loanApplication, Request $request)
{  // Display and stop execution with the entire request data





    // Validate the input with custom messages
    $validated = $request->validate([
        'loan_amount' => 'required|numeric',
        'loan_duration' => 'required|integer',
        'monthly_deduction' => 'required|numeric',
        'total_loan_with_interest' => 'required|numeric',
        'total_interest' => 'required|numeric',
        'processing_fee' => 'required|numeric',
        'insurance' => 'required|numeric',
        'disbursement_amount' => 'required|numeric',

]);
    try {
        // Check if the loan has already been processed
        if ($loanApplication->status == 'processed') {
            return redirect()->back()->with('error', 'This loan has already been processed.');
        }

        // Update the loan application with new values
        $loanApplication->update($validated + ['status' => 'processed']);

        // Log changes to history
        LoanApplicationHistory::create([
            'user_id' => auth()->id(), // Assuming you want to log the user who processed the loan
            'loan_application_id' => $loanApplication->id,
            'loan_amount' => $validated['loan_amount'],
            'loan_duration' => $validated['loan_duration'],
            'monthly_deduction' => $validated['monthly_deduction'],
            'total_loan_with_interest' => $validated['total_loan_with_interest'],
            'total_interest' => $validated['total_interest'],
            'processing_fee' => $validated['processing_fee'],
            'insurance' => $validated['insurance'],
            'disbursement_amount' => $validated['disbursement_amount'],
            'action_taken' => 'Processed',
        ]);

        // Prepare and send the SMS
        $enquiry = $loanApplication->enquiry;
        $message = "Hello " . $enquiry->full_name . ", your loan application for the requested amount of Tsh " . number_format($enquiry->loan_amount) . " has been processed. The processed amount is Tsh " . number_format($request->loan_amount) . ". For further information, please contact 0677 026301.";
        $this->sendEnquirySMS($enquiry->phone, $message);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Loan application has been processed and the member has been notified.');

    } catch (\Exception $e) {
        // Log the error
        \Log::error('Loan processing failed: ' . $e->getMessage());

        // Redirect back with an error message
        return redirect()->back()->with('error', 'Failed to process the loan due to an internal error.');
    }
}


// public function process(LoanApplication $loanApplication, Request $request)
// {
//     DB::beginTransaction();
//     try {
//         $validated = $request->validate([
//             'loan_amount' => 'required|numeric',
//             'loan_duration' => 'required|integer',
//             'monthly_deduction' => 'required|numeric',
//             'total_loan_with_interest' => 'required|numeric',
//             'total_interest' => 'required|numeric',
//             'processing_fee' => 'required|numeric',
//             'insurance' => 'required|numeric',
//             'disbursement_amount' => 'required|numeric',
//         ]);

//         Log::info('Validated data:', $validated);

//         if ($loanApplication->status === 'processed') {
//             DB::rollBack();
//             return redirect()->back()->with('error', 'This loan has already been processed.');
//         }

//         $loanApplication->update($validated + ['status' => 'processed']);

//         LoanApplicationHistory::create([
//             'user_id' => auth()->id(),
//             'loan_application_id' => $loanApplication->id, // Ensure this is correctly passed
//             'loan_amount' => $validated['loan_amount'],
//             'loan_duration' => $validated['loan_duration'],
//             'monthly_deduction' => $validated['monthly_deduction'],
//             'total_loan_with_interest' => $validated['total_loan_with_interest'],
//             'total_interest' => $validated['total_interest'],
//             'processing_fee' => $validated['processing_fee'],
//             'insurance' => $validated['insurance'],
//             'disbursement_amount' => $validated['disbursement_amount'],
//             'action_taken' => 'Processed',
//             'created_at' => now(),
//             'updated_at' => now()
//         ]);

//         DB::commit();
//         return redirect()->back()->with('success', 'Loan application has been processed.');
//     } catch (\Exception $e) {
//         DB::rollBack();
//         Log::error('Loan processing failed: ' . $e->getMessage());
//         return redirect()->back()->with('error', 'Failed to process the loan due to an internal error.');
//     }
// }

private function sendEnquirySMS($phone, $message)
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
     * Approve a loan application.
     *
     * @param  LoanApplication $loanApplication
     * @return \Illuminate\Http\Response
     */
    // public function approve(LoanApplication $loanApplication)
    // {
    //     // Logic to approve the loan
    //     $loanApplication->update(['status' => 'approved']);

    //     return redirect()->back()->with('success', 'Loan application has been approved.');
    // }

    public function approve(LoanApplication $loanApplication)
    {
        // Logic to approve the loan
        $loanApplication->update(['status' => 'approved']);

        // Retrieve the associated enquiry to get the member's phone number and loan details
        $enquiry = $loanApplication->enquiry;

        // Retrieve the requested loan amount from the enquiry
        $requestedAmount = $enquiry->loan_amount;  // Assuming this field contains the requested amount
        $approvedAmount = $loanApplication->loan_amount; // This might be a final approved amount, possibly adjusted during processing

        // Prepare the SMS message
        $message = "Hello " . $enquiry->full_name . ", your loan application for the requested amount of Tsh" . number_format( $requestedAmount) . " has been approved. The approved amount is " . number_format($approvedAmount) . ".For further information, please contact 0677 026301";

        // Send the SMS
        $this->sendEnquiryapproveSMS($enquiry->phone, $message);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Loan application has been approved and the member has been notified.');
    }

    public function sendOtpApproveLoan(LoanApplication $loanApplication)
{
    $otp = rand(100000, 999999);  // Generate a random 6-digit OTP
    $loanApplication->otp = $otp;
    $loanApplication->otp_expires_at = now()->addMinutes(10); // Set OTP to expire in 10 minutes
    $loanApplication->save();

    // // Send OTP via SMS
    // $this->sendSms($loanApplication->enquiry->phone, "Your OTP for loan approval is: $otp");

    // Logic to send OTP via SMS
    $this->sendEnquiryapproveSMS(auth()->user()->phone_number, "Your OTP for loan approval is: $otp");

    return response()->json(['success' => true, 'message' => 'OTP has been sent to your phone.']);
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


    public function verifyOtpApproveLoan(Request $request, LoanApplication $loanApplication)
    {
        $inputOtp = $request->input('otp');
        if ($loanApplication->otp === $inputOtp && now()->lessThan($loanApplication->otp_expires_at)) {
            $loanApplication->update(['status' => 'approved']); // Update loan status to approved

            // Optional: Send confirmation SMS or perform additional actions as needed
            $this->sendEnquiryapproveSMS($loanApplication->enquiry->phone, "Your loan has been approved.");

            return response()->json(['success' => true, 'message' => 'OTP verified successfully, loan approved.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP']);
    }





    /**
     * Reject a loan application.
     *
     * @param  LoanApplication $loanApplication
     * @return \Illuminate\Http\Response
     */
    // public function reject(LoanApplication $loanApplication)
    // {
    //     // Logic to reject the loan
    //     $loanApplication->update(['status' => 'rejected']);

    //     return redirect()->back()->with('success', 'Loan application has been rejected.');
    // }

    public function reject(LoanApplication $loanApplication)
{
    // Logic to reject the loan
    $loanApplication->update(['status' => 'rejected']);

    // Retrieve the associated enquiry to get the member's phone number and loan details
    $enquiry = $loanApplication->enquiry;

    // Retrieve the requested loan amount from the enquiry
    $requestedAmount = $enquiry->loan_amount;  // Assuming this field contains the requested amount

    // Prepare the SMS message
    $message = "Hello " . $enquiry->full_name . ", unfortunately, your loan application for the amount of Tsh " . number_format( $requestedAmount). " has been rejected. For further information, please contact 0677 026301.";

    // Send the SMS
    $this->sendEnquiryrejectSMS($enquiry->phone, $message);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Loan application has been rejected and the member has been notified.');
}

private function sendEnquiryrejectSMS($phone, $message)
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

    // Display the amortization form
    public function showAmortizationForm($memberId)
    {
        $member = Member::findOrFail($memberId); // Find the member or fail
        return view('loans.amortization_form', ['member' => $member]);
    }

    // Process the form submission and calculate the amortization
    public function calculateAmortization(Request $request, $memberId)
    {
        $member = Member::findOrFail($memberId);

        $loanAmount = $member->loanableAmount; // Assuming this field exists in your Member model
        $annualInterestRate = $request->input('interestRate');
        $totalPeriods = $request->input('totalPeriods');

        $amortizationSchedule = $this->generateAmortizationSchedule($loanAmount, $annualInterestRate, $totalPeriods);

        return view('loans.amortization_schedule', [
            'amortizationSchedule' => $amortizationSchedule,
            'member' => $member
        ]);
    }

    // Helper function to calculate amortization schedule
    protected function generateAmortizationSchedule($loanAmount, $annualInterestRate, $totalPeriods)
    {
        $monthlyInterestRate = $annualInterestRate / 12 / 100;
        $emi = $loanAmount * ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $totalPeriods)) / (pow(1 + $monthlyInterestRate, $totalPeriods) - 1);

        $balance = $loanAmount;
        $amortizationSchedule = [];

        for ($period = 1; $period <= $totalPeriods; $period++) {
            $interestPayment = $balance * $monthlyInterestRate;
            $principalPayment = $emi - $interestPayment;
            $balance -= $principalPayment;

            $amortizationSchedule[] = [
                'Period' => $period,
                'EMI' => round($emi, 2),
                'Interest' => round($interestPayment, 2),
                'Principal' => round($principalPayment, 2),
                'Balance' => round($balance, 2)
            ];
        }

        return $amortizationSchedule;
    }



public function calculateLoan(Request $request, $loanApplicationId)
{
    // Retrieve the necessary inputs from the request
    $basicSalary = $request->input('basic_salary');
    $allowances = $request->input('allowances');  // This should be a single value or an array sum if multiple
    $loanAmount = $request->input('loan_amount');
    $loanDuration = $request->input('loan_duration');
    $takeHome = $request->input('take_home');

    // Calculate one-third of the basic salary (common practice in loan assessments)
    $oneThirdSalary = $basicSalary / 3;

    // Calculate the loanable take-home amount, deducting one-third of the basic salary and allowances
    $loanableTakeHome = $takeHome - ($oneThirdSalary + $allowances);

    // Calculate the maximum monthly payment that can be made
    $annualInterestRate = 12; // Yearly interest rate of 12%
    $monthlyInterestRate = $annualInterestRate / 100 / 12;
    $loanApplicable = ($loanableTakeHome * (pow(1 + $monthlyInterestRate, $loanDuration) - 1)) / ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $loanDuration));

    // Use the lesser of the loan amount requested or the maximum loan applicable
    $LoanAmount = min($loanApplicable, $loanAmount);

    // Calculate the actual loan details
    $monthlyDeduction = $LoanAmount * $monthlyInterestRate / (1 - pow(1 + $monthlyInterestRate, -$loanDuration));
    $totalLoanWithInterest = $monthlyDeduction * $loanDuration;
    $totalInterest = $totalLoanWithInterest - $LoanAmount;
    $processingFee = $LoanAmount * 0.01;  // 1% processing fee
    $insurance = $LoanAmount * 0.02;  // 2% insurance fee
    $disbursementAmount = $LoanAmount - ($processingFee + $insurance);

    // Return the calculated values as a JSON response
    return response()->json([
        'monthly_deduction' => $monthlyDeduction,
        'total_loan_with_interest' => $totalLoanWithInterest,
        'total_interest' => $totalInterest,
        'processing_fee' => $processingFee,
        'insurance' => $insurance,
        'disbursement_amount' => $disbursementAmount,
        'loan_amount' => $LoanAmount  ]);
}


}

