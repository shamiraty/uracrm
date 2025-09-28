<?php

namespace App\Services;

use App\Models\LoanOffer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NmbDisbursementService
{
    // ... your __construct() and generateJwtToken() methods remain the same ...

    public function __construct()
    {
        $this->baseUrl = config('services.nmb.base_url');
        $this->sharedSecret = config('services.nmb.shared_secret');
        $this->userId = config('services.nmb.user_id');
        $this->institution = config('services.nmb.institution');
        $this->accountCode = config('services.nmb.account_code');
        $this->accountDescription = config('services.nmb.account_description');
    }

    protected function generateJwtToken(): string
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'iss' => 'GWX-APIGW',
            'exp' => time() + 119,
            'iat' => time(),
            'userId' => $this->userId,
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->sharedSecret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }


    /**
     * FINALIZED disburseLoan method using the correct table columns.
     */
   



// public function disburseLoan(LoanOffer $loanOffer): array
// {
//     // This will now correctly generate the JWT for "urasaccoss"
//     $token = $this->generateJwtToken();

//     $totalAmountString = number_format($loanOffer->total_amount_to_pay, 2, '.', '');
//     $beneficiaryAmountFloat = (float)$totalAmountString;

//     $payload = [
//         "batchId" => 'URA' . $loanOffer->id . time(),
//         "batchDate" => now()->toIso8601String(),
//         "institution" => $this->institution,
//         "accountCode" => $this->accountCode,
//         "accountDescription" => $this->accountDescription,
//         "callbackURL" => route('nmb.callback'),
//         "totalAmount" => $totalAmountString,
        
//         // ===================================================================
//         // V-- THIS IS THE FINAL CORRECTION --V

//         // The userid IN THE PAYLOAD must be the test user NMB provided.
//         "userid" => "URA.SACCOSS",
        
//         // The clientId is your main company ID.
//         "clientId" => "urasaccoss",

//         // ^-- THIS IS THE FINAL CORRECTION --^
//         // ===================================================================

//         "gwxReference" => 'GWX' . time() . $loanOffer->id,
//         "consoleReq" => "Y",
//         "beneficiaries" => [
//             "beneficiary" => [
//                 [
//                     "ROWNUM" => "1",
//                     "NAME" => implode(' ', array_filter([$loanOffer->first_name, $loanOffer->middle_name, $loanOffer->last_name])),
//                     "CURRENCY" => "TZS",
//                     "amount" => $beneficiaryAmountFloat,
//                     "ACCOUNT" => $loanOffer->bank_account_number,
//                     "channelIdentifier" => "INTERNAL",
//                     "destinationCode" => "NMB",
//                     "OWNREFERENCE" => 'LOAN' . $loanOffer->id,
//                     "NARRATION" => 'Loan Disbursement Ref ' . $loanOffer->application_number,
//                 ]
//             ]
//         ]
//     ];

//     // The rest of the function remains the same...
//     Log::info('NMB Disbursement Request Payload (Final Definitive Format):', ['payload' => $payload]);
//     try {
//         $response = Http::withHeaders([
//             'Content-Type' => 'application/json',
//             'Accept' => 'application/json',
//             'GWX-Authorization' => 'Bearer ' . $token,
//         ])->post($this->baseUrl . '/host-to-host-adapter/v1.0/pay', $payload);
//         Log::info('NMB Disbursement Response:', ['loan_offer_id' => $loanOffer->id, 'status' => $response->status(), 'body' => $response->json()]);
//         return $response->json();
//     } catch (\Exception $e) {
//         Log::error('NMB Disbursement HTTP Error:', ['loan_offer_id' => $loanOffer->id, 'error' => $e->getMessage()]);
//         return ['payload' => ['RespStatus' => 'Failure', 'RespBody' => ['ErrorDesc' => $e->getMessage()]]];
//     }
// }

public function disburseLoan(LoanOffer $loanOffer): array
{
    // Eager load the bank details
    $loanOffer->load('bank');

    // 1. --- VALIDATION ---
    // Get SWIFT code from bank relationship or loan offer
    $swiftCode = null;
    $bankShortName = null;
    
    if ($loanOffer->bank) {
        $swiftCode = strtoupper($loanOffer->bank->swift_code ?? '');
        $bankShortName = strtoupper($loanOffer->bank->short_name ?? '');
    } elseif ($loanOffer->swift_code) {
        $swiftCode = strtoupper($loanOffer->swift_code);
    }
    
    // Ensure we have bank information for disbursement
    if (!$swiftCode && !$bankShortName) {
        throw new \Exception('Bank information (SWIFT code) has not been set for this loan offer.');
    }

    // Use take_home_amount for disbursement (actual amount to be paid to customer)
    // If take_home_amount is not set, fallback to net_loan_amount or requested_amount
    $amount = (float)($loanOffer->take_home_amount ?: ($loanOffer->net_loan_amount ?: $loanOffer->requested_amount));
    
    $channelIdentifier = '';
    $destinationCode = '';

    // 2. --- DYNAMIC CHANNEL SELECTION LOGIC USING BANK SWIFT CODE ---
    // Determine destination code from bank short name or swift code
    if ($bankShortName) {
        $destinationCode = $bankShortName;
    } else {
        // Extract bank code from SWIFT code (usually first 3-4 characters)
        $destinationCode = substr($swiftCode, 0, 4);
    }
    
    // Determine channel based on bank and amount
    if ($swiftCode === 'NMIBTZTZ' || $bankShortName === 'NMB') {
        // Case 1: Payment is to another NMB account
        $channelIdentifier = 'INTERNAL';
        $destinationCode = 'NMB';
    } else {
        // Case 2: Payment is to an external bank
        // Use improved threshold (20 million TZS)
        if ($amount < 20000000) {
            $channelIdentifier = 'DOMESTIC'; // Under 20 Million
        } else {
            $channelIdentifier = 'TISS'; // 20 Million or more
        }
    }

    // 3. --- PAYLOAD CONSTRUCTION ---
    $token = $this->generateJwtToken();
    $totalAmountString = number_format($amount, 2, '.', '');
    $beneficiaryAmountFloat = $amount;

    // Generate IDs for tracking
    $batchId = 'URA' . $loanOffer->id . time();
    $gwxReference = 'GWX' . time() . $loanOffer->id;
    
    $payload = [
        "batchId" => $batchId,
        "batchDate" => now()->toIso8601String(),
        "institution" => $this->institution,
        "accountCode" => $this->accountCode,
        "accountDescription" => $this->accountDescription,
        "callbackURL" => route('nmb.callback'),
        "totalAmount" => $totalAmountString,
        "userid" => "URA.SACCOSS", // Your operational user ID
        "clientId" => "urasaccoss", // Your main company ID for authentication
        "gwxReference" => $gwxReference,
        "consoleReq" => "Y",
        "beneficiaries" => [
            "beneficiary" => [
                [
                    "ROWNUM" => "1",
                    "NAME" => implode(' ', array_filter([$loanOffer->first_name, $loanOffer->middle_name, $loanOffer->last_name])),
                    "CURRENCY" => "TZS",
                    "amount" => $beneficiaryAmountFloat,
                    "ACCOUNT" => $loanOffer->bank_account_number,
                    "channelIdentifier" => $channelIdentifier, // Using the new dynamic value
                    "destinationCode" => $destinationCode,   // Using the new dynamic value
                    "OWNREFERENCE" => 'LOAN' . $loanOffer->id,
                    "NARRATION" => 'Loan Disbursement Ref ' . $loanOffer->application_number,
                ]
            ]
        ]
    ];

    // 4. --- API CALL ---
    // Log channel and destination information
    Log::info('NMB Disbursement Channel Selection:', [
        'loan_offer_id' => $loanOffer->id,
        'swift_code' => $swiftCode,
        'channel_identifier' => $channelIdentifier,
        'destination_code' => $destinationCode,
        'amount' => $amount,
        'bank_account' => $loanOffer->bank_account_number
    ]);
    
    // The rest of the function remains the same...
    Log::info('NMB Disbursement Request Payload (Final Definitive Format):', ['payload' => $payload]);
    try {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'GWX-Authorization' => 'Bearer ' . $token,
        ])->post($this->baseUrl . '/host-to-host-adapter/v1.0/pay', $payload);
        Log::info('NMB Disbursement Response:', ['loan_offer_id' => $loanOffer->id, 'status' => $response->status(), 'body' => $response->json()]);
        
        // Return the response with additional metadata
        $responseData = $response->json() ?: [];
        $responseData['channel_metadata'] = [
            'channel_identifier' => $channelIdentifier,
            'destination_code' => $destinationCode,
            'swift_code' => $swiftCode,
            'batch_id' => $batchId,
            'gwx_reference' => $gwxReference
        ];
        
        return $responseData;
    } catch (\Exception $e) {
        Log::error('NMB Disbursement HTTP Error:', ['loan_offer_id' => $loanOffer->id, 'error' => $e->getMessage()]);
        
        // Return error with metadata
        return [
            'payload' => ['RespStatus' => 'Failure', 'RespBody' => ['ErrorDesc' => $e->getMessage()]],
            'channel_metadata' => [
                'channel_identifier' => $channelIdentifier,
                'destination_code' => $destinationCode,
                'swift_code' => $swiftCode,
                'batch_id' => $batchId,
                'gwx_reference' => $gwxReference
            ]
        ];
    }
}


  /**
     * Sends a pre-built, raw payload to the NMB API.
     * This is used for hard-coded control tests.
     */
    public function sendRawPayload(array $payload)
    {
        $token = $this->generateJwtToken();

        Log::info('Sending HARD-CODED NMB Test Payload:', ['payload' => $payload]);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'GWX-Authorization' => 'Bearer ' . $token,
            ])->post($this->baseUrl . '/host-to-host-adapter/v1.0/pay', $payload);

            Log::info('NMB Response from HARD-CODED Test:', ['status' => $response->status(), 'body' => $response->json()]);
            return $response->json();

        } catch (\Exception $e) {
            Log::error('NMB HARD-CODED Test HTTP Error:', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }

}