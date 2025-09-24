<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function create(Enquiry $enquiry)
    {
        return view('responses.create', compact('enquiry'));
    }

    public function store(Request $request, Enquiry $enquiry)
    {
        $response = new Response();
        $response->enquiry_id = $enquiry->id;
        $response->user_id = Auth::id();
        $response->amount = $request->amount;
        $response->interest = $request->interest;
        $response->remarks = $request->remarks;
        $response->description = $request->description;
        $response->from_amount = $request->from_amount;
        $response->to_amount = $request->to_amount;
        $response->duration = $request->duration;
        $response->date_of_retirement = $request->date_of_retirement;
        $response->save();

        // Send SMS to the member
        // $sid = env('TWILIO_SID');
        // $token = env('TWILIO_TOKEN');
        // $client = new \Twilio\Rest\Client($sid, $token);

        // $message = "Response received for your enquiry: Amount: {$response->amount}, Interest: {$response->interest}, Remarks: {$response->remarks}";

        // $client->messages->create(
        //     $enquiry->phone, // Member's phone number
        //     [
        //         'from' => env('TWILIO_FROM'), // Your Twilio number
        //         'body' => $message
        //     ]
        // );

        return redirect()->route('enquiries.show', $enquiry->id)->with('success', 'Response submitted successfully');
    }
}
