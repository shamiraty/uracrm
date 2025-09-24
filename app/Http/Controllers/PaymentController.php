<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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



public function initiate(Request $request, $enquiryId)
{
    $request->validate([
        'amount' => 'required|numeric',
        'note' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png|max:2048',
        'file_id' => 'nullable|exists:files,id'
    ]);

    $enquiry = Enquiry::findOrFail($enquiryId);

    DB::beginTransaction();

    try {
        $payment = Payment::create([
            'enquiry_id' => $enquiryId,
            'amount' => $request->amount,
            'status' => 'initiated',
            'payment_date' => now(),
            'initiated_by' => auth()->id(),
            'branch_id' => auth()->user()->branch_id,
        ]);
      

        if ($request->hasFile('note') && $request->file('note')->isValid()) {
            $file = $request->file('note');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $destinationPath = public_path('dokezo');

            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the file to the public/dokezo directory
            $file->move($destinationPath, $fileName);
            $filePath = 'dokezo/' . $fileName;  // Path to be stored in the database

            $folio = $payment->folios()->create([
                'file_path' => $filePath,
                'description' => 'Payment Note',
                'file_id' => $request->file_id,
            ]);
        }

        $payment->logs()->create(['initiated_by' => auth()->id()]);

        $message = "Hello {$enquiry->full_name}, a payment of Tsh " . number_format($request->amount) . " has been initiated. For further information, please contact 0677 026301";
        $this->sendEnquirySMS($enquiry->phone, $message);

        DB::commit();

        return redirect()->route('enquiries.my')->with('success', 'Payment initiated successfully with note attached!');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Failed to initiate payment: ' . $e->getMessage());
    }
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

public function sendOtpApprove($paymentId)
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



public function verifyOtpApprove(Request $request, $paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $inputOtp = $request->input('otp');

    if ($payment->otp === $inputOtp && now()->lessThan($payment->otp_expires_at)) {
        // $payment->status = 'approved';
        // $payment->save();
        // return response()->json(['success' => true, 'message' => 'OTP verified successfully']);
        $payment->status = 'approved';
        $payment->approved_by = auth()->id();  // Set who is approving this payment
        $payment->save();
        $payment->logs()->create([
            'approved_by' => auth()->id()
        ]);

        // Retrieve the associated enquiry to get the member's phone number and payment details
        $enquiry = $payment->enquiry;

        // Prepare the SMS message
        $message = "Hello " . $enquiry->full_name . ", your payment of Tsh" . number_format($payment->amount) . " has been approved. The transaction details and next steps will be communicated to you shortly. For further information, please contact 0677 026301.";

        // Send the SMS
        $this->sendEnquiryapproveSMS($enquiry->phone, $message);

        return response()->json(['success' => true, 'message' => 'OTP verified and payment approved successfully. SMS notification sent.']);
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
    }
}



//     public function pay($paymentId)
// {
//     $payment = Payment::findOrFail($paymentId);
//     if ($payment->status === 'approved') {
//         $payment->status = 'paid';
//         $payment->paid_by = auth()->id();
//         $payment->payment_date = now();
//         $payment->save();

//         $payment->logs()->create([
//             'paid_by' => auth()->id()
//         ]);

//         // Retrieve the associated enquiry to get the member's phone number and payment details
//         $enquiry = $payment->enquiry;

//         // Prepare the SMS message
//         $message = "Hello " . $enquiry->full_name . ", your payment of " . $payment->amount . " has been successfully completed. Thank you for your prompt response. Please check your account for confirmation.";

//         // Send the SMS
//         $this->sendEnquirypaidSMS($enquiry->phone, $message);

//         return redirect()->route('enquiries.my')->with('success', 'Payment completed successfully and the member has been notified!');
//     }

//     return redirect()->route('enquiries.my')->with('error', 'Payment must be approved before paying.');
// }

// private function sendEnquirypaidSMS($phone, $message)
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

public function sendOtpPay($paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $otp = rand(100000, 999999);  // Generate a random 6-digit OTP
    $payment->otp = $otp;
    $payment->otp_expires_at = now()->addMinutes(10); // Set the OTP to expire in 10 minutes
    $payment->save();

    // Logic to send the OTP via SMS

    $this->sendEnquiryapproveSMS(auth()->user()->phone_number, "Your OTP for payment verification is: $otp");

    return response()->json(['success' => true, 'message' => 'OTP has been sent.']);
}




public function verifyOtpPay(Request $request, $paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $inputOtp = $request->input('otp');

    if ($payment->otp === $inputOtp && now()->lessThan($payment->otp_expires_at)) {
        $payment->status = 'paid';
        $payment->approved_by = auth()->id();  // Set who is approving this payment
        $payment->save();
        $payment->logs()->create([
            'approved_by' => auth()->id()
        ]);

        // Retrieve the associated enquiry to get the member's phone number and payment details
        $enquiry = $payment->enquiry;

        // Prepare the SMS message
        $message = "Hello " . $enquiry->full_name . ", your payment of Tsh " . number_format( $payment->amount) . " has been paid. The transaction details and next steps will be communicated to you shortly. For further information, please contact 0677 026301.";

        // Send the SMS
        $this->sendEnquiryapproveSMS($enquiry->phone, $message);

        return response()->json(['success' => true, 'message' => 'OTP verified and payment approved successfully. SMS notification sent.']);
    }

    return response()->json(['success' => false, 'message' => 'Invalid or expired OTP']);
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

