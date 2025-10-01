<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanApplication;
use App\Models\Member; // Ensure the correct model is used

class LoanController extends Controller
{






    /**
     * Process a loan application.
     *
     * @param  LoanApplication $loanApplication
     * @return \Illuminate\Http\Response
     */
    // public function process(LoanApplication $loanApplication)
    // {
    //     // Logic to process the loan
    //     $loanApplication->update(['status' => 'processed']);

    //     return redirect()->back()->with('success', 'Loan application has been processed.');
    // }

//     public function process(LoanApplication $loanApplication)
// {
//     // Logic to process the loan
//     $loanApplication->update(['status' => 'processed']);

//     // Retrieve the associated enquiry to get the member's phone number and other details
//     $enquiry = $loanApplication->enquiry;

//     // Prepare the SMS message
//     $message = "Hello " . $enquiry->full_name . ", your loan application for " . $loanApplication->loan_amount . " has been processed. Your loan details will be communicated to you shortly.";

//     // Send the SMS
//     $this->sendEnquirySMS($enquiry->phone, $message);

//     // Redirect back with a success message
//     return redirect()->back()->with('success', 'Loan application has been processed and the member has been notified.');
// }

// private function sendEnquirySMS($phone, $message)
// {
//     $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
//     $apiKey = 'xYz123#';

//     $client = new \GuzzleHttp\Client();
//     try {
//         $response = $client->request('POST', $url, [
//             'verify' => false,  // Keep SSL verification disabled as in your working script
//             'form_params' => [
//                 'msisdn' => $phone,
//                 'message' => $message,
//                 'key' => $apiKey,
//             ]
//         ]);

//         $responseBody = $response->getBody()->getContents();
//         \Log::info("SMS sent response: " . $responseBody);
//         return $responseBody;
//     } catch (\GuzzleHttp\Exception\GuzzleException $e) {
//         \Log::error("Failed to send SMS: " . $e->getMessage());
//         return null;
//     }
// }

public function process(LoanApplication $loanApplication)
{
    // Logic to process the loan
    $loanApplication->update(['status' => 'processed']);

    // Retrieve the associated enquiry to get the member's phone number and loan details
    $enquiry = $loanApplication->enquiry;

    // Retrieve the requested loan amount from the enquiry
    $requestedAmount = $enquiry->loan_amount;  // Assuming this field contains the requested amount
    $processedAmount = $loanApplication->loan_amount; // This might be a calculated value or the same as requested

    // Prepare the SMS message
    $message = "Hello " . $enquiry->full_name . ", your loan application for the requested amount of " . $requestedAmount . " has been processed. The processed amount is " . $processedAmount . ". Your loan details will be communicated to you shortly.";

    // Send the SMS
    $this->sendEnquirySMS($enquiry->phone, $message);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Loan application has been processed and the member has been notified.');
}

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
        $message = "Hello " . $enquiry->full_name . ", your loan application for the requested amount of " . $requestedAmount . " has been approved. The approved amount is " . $approvedAmount . ". Further details and the next steps will be communicated to you shortly.";

        // Send the SMS
        $this->sendEnquiryapproveSMS($enquiry->phone, $message);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Loan application has been approved and the member has been notified.');
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
    $message = "Hello " . $enquiry->full_name . ", unfortunately, your loan application for the amount of " . $requestedAmount . " has been rejected. For further details or to discuss alternative options, please contact us.";

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
}

