<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Enquiry;

class PaymentController extends Controller
{



public function showByType($type)
{
    $payments = Payment::with(['enquiry', 'initiatedBy', 'approvedBy', 'rejectedBy',  'paidBy'])
        ->whereHas('enquiry', function ($query) use ($type) {
            $query->where('type', $type);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    $columnsToShow = ['initiated' => false, 'approved' => false, 'rejected' => false, 'paid' => false];

    foreach ($payments as $payment) {
        if ($payment->initiatedBy) $columnsToShow['initiated'] = true;
        if ($payment->approvedBy) $columnsToShow['approved'] = true;
        if ($payment->rejectedBy) $columnsToShow['rejected'] = true;

        if ($payment->paidBy) $columnsToShow['paid'] = true;
    }

    return view('payments.showByType', compact('payments', 'type', 'columnsToShow'));
}
public function showTimeline($paymentId)
{
    $payment = Payment::with(['logs.initiator', 'logs.approver', 'logs.payer', 'logs.rejector'])->findOrFail($paymentId);
    return view('payments.payment_timeline', compact('payment'));
}




// public function initiate(Request $request, $enquiryId)
// {
//     $request->validate([
//         'note' => 'file|mimes:pdf,doc,docx,jpeg,png|max:2048', // Validating the file type and size
//     ]);

//     $enquiry = Enquiry::findOrFail($enquiryId);
//     $notePath = null;

//     if ($request->hasFile('note') && $request->file('note')->isValid()) {
//         // Generate a unique file name to avoid conflicts
//         $originalFileName = $request->note->getClientOriginalName();
//         $extension = $request->note->getClientOriginalExtension();
//         $fileName = pathinfo($originalFileName, PATHINFO_FILENAME);
//         $timestamp = time();
//         $uniqueFileName = $fileName . '-' . $timestamp . '.' . $extension;

//         // Move the file to the appropriate directory
//         $request->note->move(public_path('dokezo'), $uniqueFileName);
//         $notePath = 'dokezo/' . $uniqueFileName;
//     }

//     $payment = new Payment([
//         'enquiry_id' => $enquiryId,
//         'amount' => $request->amount,
//         'status' => 'initiated',
//         'payment_date' => now(),
//         'note_path' => $notePath,
//         'initiated_by' => auth()->id(),  // Set who is initiating this payment
//     ]);
//     $payment->save();
//     $payment->logs()->create([
//         'initiated_by' => auth()->id()
//     ]);

//     // Prepare the SMS message
//     $message = "Hello " . $enquiry->full_name . ", a payment of " . $request->amount . " has been initiated on your behalf. Further details will be communicated to you shortly.";

//     // Send the SMS
//     $this->sendEnquirySMS($enquiry->phone, $message);

//     return redirect()->route('enquiries.my')->with('success', 'Payment initiated successfully with note attached!');
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




public function initiate(Request $request, $enquiryId)
{
    $request->validate([
        'note' => 'file|mimes:pdf,doc,docx,jpeg,png|max:2048', // Validating the file type and size
    ]);

    $enquiry = Enquiry::findOrFail($enquiryId);
    $notePath = null;

    if ($request->hasFile('note') && $request->file('note')->isValid()) {
        // Generate a unique file name to avoid conflicts
        $originalFileName = $request->note->getClientOriginalName();
        $extension = $request->note->getClientOriginalExtension();
        $fileName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $timestamp = time();
        $uniqueFileName = $fileName . '-' . $timestamp . '.' . $extension;

        // Move the file to the appropriate directory
        $request->note->move(public_path('dokezo'), $uniqueFileName);
        $notePath = 'dokezo/' . $uniqueFileName;
    }

    $payment = new Payment([
        'enquiry_id' => $enquiryId,
        'amount' => $request->amount,
        'status' => 'initiated',
        'payment_date' => now(),
        'note_path' => $notePath,
        'initiated_by' => auth()->id(),  // Set who is initiating this payment

    ]);
    $payment->save();
    $payment->logs()->create([
        'initiated_by' => auth()->id()
    ]);

    // Prepare the SMS message
    $message = "Hello " . $enquiry->full_name . ", a payment of " . $request->amount . " has been initiated on your behalf. Further details will be communicated to you shortly.";

    // Send the SMS
    $this->sendEnquirySMS($enquiry->phone, $message);

    return redirect()->route('enquiries.my')->with('success', 'Payment initiated successfully with note attached!');
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







public function sendOtp($paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $otp = rand(100000, 999999);  // Generate a 6-digit OTP
    $payment->otp = $otp;
    $payment->otp_expires_at = now()->addMinutes(10);
    $payment->save();

    // Logic to send OTP via SMS
    $this->sendEnquiryapproveSMS(auth()->user()->phone_number, "Your OTP for payment approval is: $otp");

    return response()->json(['success' => true, 'message' => 'OTP has been sent.']);
}




public function verifyOtp(Request $request, $paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $inputOtp = $request->input('otp');

    if ($payment->otp === $inputOtp && now()->lessThan($payment->otp_expires_at)) {
        // Update payment status
        $payment->status = 'approved';
        $payment->approved_by = auth()->id();  // Set who is approving this payment
        $payment->save();
        $payment->logs()->create([
            'approved_by' => auth()->id()
        ]);

        // Retrieve the associated enquiry to get the member's phone number and payment details
        $enquiry = $payment->enquiry;

        // Prepare the SMS message
        $message = "Hello " . $enquiry->full_name . ", your payment of " . $payment->amount . " has been approved. The transaction details and next steps will be communicated to you shortly.";

        // Send the SMS
        $this->sendEnquiryapproveSMS($enquiry->phone, $message);

        return response()->json(['success' => true, 'message' => 'OTP verified and payment approved successfully. SMS notification sent.']);
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
    }
}

private function sendEnquiryapproveSMS($phone, $message)
{
    $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
    $apiKey = 'xYz123#'; // Consider moving this to an environment variable

    $client = new \GuzzleHttp\Client();
    try {
        $response = $client->request('POST', $url, [
            'verify' => false,
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


    public function pay($paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    if ($payment->status === 'approved') {
        $payment->status = 'paid';
        $payment->paid_by = auth()->id();
        $payment->payment_date = now();
        $payment->save();

        $payment->logs()->create([
            'paid_by' => auth()->id()
        ]);

        // Retrieve the associated enquiry to get the member's phone number and payment details
        $enquiry = $payment->enquiry;

        // Prepare the SMS message
        $message = "Hello " . $enquiry->full_name . ", your payment of " . $payment->amount . " has been successfully completed. Thank you for your prompt response. Please check your account for confirmation.";

        // Send the SMS
        $this->sendEnquirypaidSMS($enquiry->phone, $message);

        return redirect()->route('enquiries.my')->with('success', 'Payment completed successfully and the member has been notified!');
    }

    return redirect()->route('enquiries.my')->with('error', 'Payment must be approved before paying.');
}

private function sendEnquirypaidSMS($phone, $message)
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

    public function reject(Request $request, $paymentId)
    {


        $payment = Payment::findOrFail($paymentId);
        $payment->status = 'rejected';
        $payment->rejected_by = auth()->id();
        $payment->remarks = $request->remarks;
        $payment->save();
        $payment->logs()->create([
            'rejected_by' => auth()->id()
        ]);

        return redirect()->route('enquiries.index')->with('success', 'Payment rejected successfully.');
    }
}

