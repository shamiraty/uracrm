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

// public function initiate(Request $request, $enquiryId)
// {
//     $request->validate([
//         'amount' => 'required|numeric',
//         'note' => 'file|mimes:pdf,doc,docx,jpeg,png|max:2048', // Validating the file type and size
//     ]);

//     $enquiry = Enquiry::findOrFail($enquiryId);

//     // Begin a database transaction
//     \DB::beginTransaction();

//     try {
//         // Create the Payment instance
//         $payment = new Payment([
//             'enquiry_id' => $enquiryId,
//             'amount' => $request->amount,
//             'status' => 'initiated',
//             'payment_date' => now(),
//             'initiated_by' => auth()->id(),  // Set who is initiating this payment
//         ]);
//         $payment->save();

//         // Create a payment log
//         $payment->logs()->create([
//             'initiated_by' => auth()->id()
//         ]);

//         // Handle file upload and store in folios associated with the Payment
//         if ($request->hasFile('note') && $request->file('note')->isValid()) {
//             $file = $request->file('note');
//             $originalFileName = $file->getClientOriginalName();
//             $extension = $file->getClientOriginalExtension();
//             $fileName = pathinfo($originalFileName, PATHINFO_FILENAME);
//             $timestamp = time();
//             $uniqueFileName = $fileName . '-' . $timestamp . '.' . $extension;

//             // Store the file in public/attachments
//             $destinationPath = public_path('attachments');

//             // Ensure the directory exists
//             if (!\File::exists($destinationPath)) {
//                 \File::makeDirectory($destinationPath, 0755, true);
//             }

//             $file->move($destinationPath, $uniqueFileName);

//             // Save the file path relative to the public directory
//             $path = 'attachments/' . $uniqueFileName;

//             // Create a new folio entry linked to the Payment
//             $payment->folios()->create([
//                 'file_path' => $path,
//                 'folioable_id' => $payment->id,
//                 'folioable_type' => 'App\Models\Payment',
//                 'description' => 'Payment Note', // Optionally add a description
//             ]);
//         }

//         // Commit the transaction
//         \DB::commit();

//         // Prepare the SMS message
//         $message = "Hello " . $enquiry->full_name . ", a payment of Tsh " . number_format($request->amount, 2) . " has been initiated on your behalf. Further details will be communicated to you shortly.";

//         // Send the SMS
//         $this->sendEnquirySMS($enquiry->phone, $message);

//         return redirect()->route('enquiries.my')->with('success', 'Payment initiated successfully with note attached!');
//     } catch (\Exception $e) {
//         // Rollback the transaction if something goes wrong
//         \DB::rollback();

//         \Log::error('Payment initiation failed:', ['error' => $e->getMessage()]);
//         return redirect()->back()->withErrors('An error occurred while initiating the payment. Please try again.');
//     }
// }

//
// public function initiate(Request $request, $enquiryId)
// {
//     $request->validate([
//         'amount' => 'required|numeric',
//         'note' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png|max:2048',
//         'file_id' => 'nullable|integer',
//     ]);

//     $enquiry = Enquiry::findOrFail($enquiryId);

//     \DB::beginTransaction();

//     try {
//         $payment = Payment::create([
//             'enquiry_id' => $enquiryId,
//             'amount' => $request->amount,
//             'status' => 'initiated',
//             'payment_date' => now(),
//             'initiated_by' => auth()->id(),
//         ]);

//         $payment->logs()->create([
//             'initiated_by' => auth()->id()
//         ]);

//         if ($request->hasFile('note') && $request->file('note')->isValid()) {
//             $file = $request->file('note');
//             $uniqueFileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
//             $destinationPath = public_path('attachments');

//             if (!\File::exists($destinationPath)) {
//                 \File::makeDirectory($destinationPath, 0755, true);
//             }

//             $file->move($destinationPath, $uniqueFileName);
//             $path = 'attachments/' . $uniqueFileName;

//             $payment->folios()->create([
//                 'file_path' => $path,
//                 'file_id' => $request->file_id,
//                 'description' => 'Payment Note',
//             ]);

//             \Log::info('Folio created for payment ID: ' . $payment->id);
//         }

//         \DB::commit();

//         $message = "Hello " . $enquiry->full_name . ", a payment of Tsh " . number_format($request->amount, 2) . " has been initiated on your behalf. Further details will be communicated to you shortly.";
//         $this->sendEnquirySMS($enquiry->phone, $message);

//         return redirect()->route('enquiries.my')->with('success', 'Payment initiated successfully with note attached!');
//     } catch (\Exception $e) {
//         \DB::rollback();
//         \Log::error('Payment initiation failed:', ['error' => $e->getMessage()]);
//         return redirect()->back()->withErrors('An error occurred while initiating the payment. Please try again.');
//     }
// }
public function initiate(Request $request, $enquiryId)
{
    \Log::info('Initiate method called.');

    $request->validate([
        'amount' => 'required|numeric',
        'note' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png|max:2048',
        'file_id' => 'nullable|integer',
    ]);

    \Log::info('Validation passed.');

    $enquiry = Enquiry::findOrFail($enquiryId);
    \Log::info('Enquiry found with ID: ' . $enquiryId);

    \DB::beginTransaction();

    try {
        $payment = Payment::create([
            'enquiry_id' => $enquiryId,
            'amount' => $request->amount,
            'status' => 'initiated',
            'payment_date' => now(),
            'initiated_by' => auth()->id(),
        ]);

        \Log::info('Payment created with ID: ' . $payment->id);

        $payment->logs()->create([
            'initiated_by' => auth()->id()
        ]);

        \Log::info('Payment log created.');

        if ($request->hasFile('note') && $request->file('note')->isValid()) {
            \Log::info('File upload detected.');

            $file = $request->file('note');
            $uniqueFileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('attachments');

            if (!\File::exists($destinationPath)) {
                \File::makeDirectory($destinationPath, 0755, true);
                \Log::info('Attachments directory created.');
            }

            $file->move($destinationPath, $uniqueFileName);
            \Log::info('File moved to ' . $destinationPath . '/' . $uniqueFileName);

            $path = 'attachments/' . $uniqueFileName;

            \Log::info('About to create folio for payment ID: ' . $payment->id);

            $folio = $payment->folios()->create([
                'file_path' => $path,
                'file_id' => $request->file_id,
                'description' => 'Payment Note',
            ]);

            \Log::info('Folio created:', $folio->toArray());
        } else {
            \Log::info('No valid file uploaded.');
        }

        \DB::commit();
        \Log::info('Transaction committed.');

        // Send SMS
        $message = "Hello " . $enquiry->full_name . ", a payment of Tsh " . number_format($request->amount, 2) . " has been initiated on your behalf. Further details will be communicated to you shortly.";
        $this->sendEnquirySMS($enquiry->phone, $message);
        \Log::info('SMS sent to ' . $enquiry->phone);

        return redirect()->route('enquiries.my')->with('success', 'Payment initiated successfully with note attached!');
    } catch (\Exception $e) {
        \DB::rollback();
        \Log::error('Payment initiation failed:', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors('An error occurred while initiating the payment. Please try again.');
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




// public function verifyOtp(Request $request, $paymentId)
// {
//     $payment = Payment::findOrFail($paymentId);
//     $inputOtp = $request->input('otp');

//     if ($payment->otp === $inputOtp && now()->lessThan($payment->otp_expires_at)) {
//         // Update payment status
//         $payment->status = 'approved';
//         $payment->approved_by = auth()->id();  // Set who is approving this payment
//         $payment->save();
//         $payment->logs()->create([
//             'approved_by' => auth()->id()
//         ]);

//         // Retrieve the associated enquiry to get the member's phone number and payment details
//         $enquiry = $payment->enquiry;

//         // Prepare the SMS message
//         $message = "Hello " . $enquiry->full_name . ", your payment of " . $payment->amount . " has been approved. The transaction details and next steps will be communicated to you shortly.";

//         // Send the SMS
//         $this->sendEnquiryapproveSMS($enquiry->phone, $message);

//         return response()->json(['success' => true, 'message' => 'OTP verified and payment approved successfully. SMS notification sent.']);
//     } else {
//         return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
//     }
// }

public function verifyOtp(Request $request, $paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $inputOtp = $request->input('otp');
    \Log::info("Received OTP: " . $inputOtp . " for Payment ID: " . $paymentId);

    if ($payment->otp === $inputOtp && now()->lessThan($payment->otp_expires_at)) {
        // Update payment status
        $payment->status = 'approved';
        $payment->approved_by = auth()->id();  // Set who is approving this payment
        $payment->save();

        // Send SMS notification, etc.
        return response()->json(['success' => true, 'message' => 'OTP verified and payment approved successfully.']);
    } else {
        \Log::info("Received OTP: " . $inputOtp . " for Payment ID: " . $paymentId . "; Stored OTP: " . $payment->otp . "; Expiry: " . $payment->otp_expires_at);

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


public function dispatchOtpForPayment($paymentId)
{
    \Log::info("Dispatching OTP for Payment ID: " . $paymentId);
    try {
        $payment = Payment::findOrFail($paymentId);
        $otp = rand(100000, 999999);
        $payment->otp = $otp;
        $payment->otp_expires_at = now()->addMinutes(10); // OTP expires in 10 minutes
        $payment->save();

        // Send OTP to user's phone
        $message = "Your OTP for payment approval is: $otp";
        if (!$this->sendEnquirypaidSMS(auth()->user()->phone_number, $message)) {
            throw new Exception("Failed to send OTP SMS.");
        }

        return response()->json(['success' => true, 'message' => 'OTP sent to your phone.']);
    } catch (\Exception $e) {
        // Log the error or handle it as needed
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}






public function verifyOtpAndPay(Request $request, $paymentId)
{
    \Log::info("Verifying OTP for Payment ID: " . $paymentId);
    // Find the payment record and force refresh from the database to get the latest values
    $payment = Payment::findOrFail($paymentId)->refresh();
    $inputOtp = $request->input('otp');

    // Logging for debugging
    \Log::info('Attempting to verify OTP', [
        'inputOtp' => $inputOtp,
        'storedOtp' => $payment->otp,  // Checking if OTP is retrieved correctly
        'expiresAt' => $payment->otp_expires_at  // Checking if expiry is retrieved
    ]);

    // Ensure the OTP is correct and not expired
    if ($payment->otp === $inputOtp && now()->lessThan($payment->otp_expires_at)) {
        // OTP is valid, mark payment as paid
        $payment->status = 'paid';
        $payment->paid_by = auth()->id();
        $payment->payment_date = now();
        $payment->save();

        // Log the payment and send SMS
        $payment->logs()->create(['paid_by' => auth()->id()]);
        $message = "Hello " . $payment->enquiry->full_name . ", your payment of " . $payment->amount . " has been successfully completed.";
        $this->sendEnquirypaidSMS($payment->enquiry->phone, $message);

        return response()->json(['success' => true, 'message' => 'Payment completed successfully.']);
    } else {
        \Log::warning('Invalid or expired OTP', [
            'inputOtp' => $inputOtp,
            'storedOtp' => $payment->otp,
            'expiresAt' => $payment->otp_expires_at
        ]);
        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
    }
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

