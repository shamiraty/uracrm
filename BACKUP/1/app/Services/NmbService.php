<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\LoanOffer;
use App\Models\NmbCallback;
use App\Models\LoanDisbursement;

/**
 * Refined NMB Service based on API Documentation v1.3
 * Implements bulk payments, status checking, and enhanced error handling
 */
class NmbService
{
    private $baseUrl;
    private $sharedSecret;
    private $userId;           // For JWT token (gateway auth)
    private $operationalUserId; // For payload (operational user)
    private $clientId;          // For payload (company ID)
    private $institution;
    private $accountCode;
    private $accountDescription;
    private $timeout;
    private $retryAttempts;
    private $batchSizeLimit;
    
    public function __construct()
    {
        $this->baseUrl = config('services.nmb.base_url', 'https://uat.nmbbank.co.tz');
        $this->sharedSecret = config('services.nmb.shared_secret');
        $this->userId = config('services.nmb.user_id', 'user.id'); // JWT auth user
        $this->operationalUserId = config('services.nmb.operational_user_id', 'URA.SACCOSS'); // Payload userid
        $this->clientId = config('services.nmb.client_id', 'urasaccoss'); // Payload clientId
        $this->institution = config('services.nmb.institution', 'URASACCOS');
        $this->accountCode = config('services.nmb.account_code');
        $this->accountDescription = config('services.nmb.account_description', 'URASACCOS Loan Disbursement');
        $this->timeout = config('services.nmb.timeout', 30);
        $this->retryAttempts = config('services.nmb.retry_attempts', 3);
        $this->batchSizeLimit = config('services.nmb.batch_size_limit', 100);
    }

    /**
     * Generate JWT token for API authentication
     * Token expires in 119 seconds as per API requirement
     */
    private function generateJWT(): string
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        
        $payload = json_encode([
            'iss' => 'GWX-APIGW',
            'iat' => time(),
            'exp' => time() + 119, // Max 120 seconds validity
            'userId' => $this->userId
        ]);
        
        // Debug JWT payload
        Log::debug('JWT Payload', [
            'userId' => $this->userId,
            'institution' => $this->institution,
            'iat' => time(),
            'exp' => time() + 119
        ]);
        
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $this->sharedSecret, true);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }

    /**
     * Get headers for API requests
     */
    private function getHeaders(): array
    {
        $jwt = $this->generateJWT();
        
        // Debug logging
        Log::info('NMB API Configuration', [
            'jwt_user_id' => $this->userId,              // JWT token user
            'operational_user_id' => $this->operationalUserId, // Payload userid
            'client_id' => $this->clientId,              // Payload clientId
            'institution' => $this->institution,
            'base_url' => $this->baseUrl,
            'account_code' => $this->accountCode,
            'jwt_token' => substr($jwt, 0, 50) . '...' // Log partial token for security
        ]);
        
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'GWX-Authorization' => 'Bearer ' . $jwt
        ];
    }

    /**
     * Disburse a single loan
     */
    public function disburseLoan(LoanOffer $loanOffer): array
    {
        try {
            // Ensure bank relationship is loaded
            if (!$loanOffer->relationLoaded('bank')) {
                $loanOffer->load('bank');
            }
            
            $channelIdentifier = $this->getChannelIdentifier($loanOffer);
            $destinationCode = $this->getDestinationCode($loanOffer);
            
            // Log channel selection for debugging
            Log::info('NMB Channel Selection', [
                'loan_offer_id' => $loanOffer->id,
                'bank_name' => $loanOffer->bank->name ?? 'N/A',
                'bank_short_name' => $loanOffer->bank->short_name ?? 'N/A',
                'swift_code' => $loanOffer->swift_code ?? $loanOffer->bank->swift_code ?? 'N/A',
                'channel_identifier' => $channelIdentifier,
                'destination_code' => $destinationCode,
                'amount' => $loanOffer->take_home_amount ?? $loanOffer->net_loan_amount ?? $loanOffer->requested_amount
            ]);
            
            $beneficiaries = [[
                'ROWNUM' => '1',
                'NAME' => $this->formatBeneficiaryName($loanOffer),
                'CURRENCY' => 'TZS',
                'amount' => number_format($loanOffer->take_home_amount ?? $loanOffer->net_loan_amount ?? $loanOffer->requested_amount, 2, '.', ''),
                'ACCOUNT' => $loanOffer->bank_account_number,
                'channelIdentifier' => $channelIdentifier,
                'destinationCode' => $destinationCode,
                'endToEndId' => $loanOffer->application_number,
                'OWNREFERENCE' => substr($loanOffer->application_number, 0, 15),
                'NARRATION' => $this->generateNarration($loanOffer)
            ]];

            return $this->processBulkPayment($beneficiaries, $loanOffer->take_home_amount);
            
        } catch (\Exception $e) {
            Log::error('NMB disbursement failed', [
                'loan_id' => $loanOffer->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Disburse multiple loans in a single batch
     */
    public function disburseBulkLoans(Collection $loanOffers): array
    {
        try {
            // Validate batch size
            if ($loanOffers->count() > $this->batchSizeLimit) {
                throw new \Exception("Batch size exceeds limit of {$this->batchSizeLimit}");
            }

            $beneficiaries = [];
            $totalAmount = 0;
            $rowNum = 1;

            foreach ($loanOffers as $loan) {
                $amount = $loan->take_home_amount ?? $loan->net_loan_amount ?? $loan->requested_amount;
                $totalAmount += $amount;

                $beneficiaries[] = [
                    'ROWNUM' => (string)$rowNum,
                    'NAME' => $this->formatBeneficiaryName($loan),
                    'CURRENCY' => 'TZS',
                    'amount' => number_format($amount, 2, '.', ''),
                    'ACCOUNT' => $loan->bank_account_number,
                    'channelIdentifier' => $this->getChannelIdentifier($loan),
                    'destinationCode' => $this->getDestinationCode($loan),
                    'endToEndId' => $loan->application_number,
                    'OWNREFERENCE' => substr($loan->application_number, 0, 15),
                    'NARRATION' => $this->generateNarration($loan)
                ];
                
                $rowNum++;
            }

            return $this->processBulkPayment($beneficiaries, $totalAmount);
            
        } catch (\Exception $e) {
            Log::error('NMB bulk disbursement failed', [
                'count' => $loanOffers->count(),
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process bulk payment to NMB API
     */
    private function processBulkPayment(array $beneficiaries, float $totalAmount): array
    {
        // Use configured callback URL or fallback to route
        $callbackUrl = config('services.nmb.callback_url') ?: route('nmb.callback');
        
        $attempts = 0;
        $lastError = null;
        $lastBatchId = null;

        while ($attempts < $this->retryAttempts) {
            // Generate a new batch ID for each attempt to avoid duplicates
            $batchId = $this->generateBatchId();
            $lastBatchId = $batchId;
            
            $payload = [
                'batchId' => $batchId,
                'consoleReq' => 'Y',
                'batchDate' => Carbon::now()->format('Ymd'),
                'institution' => $this->institution,
                'accountCode' => $this->accountCode,
                'accountDescription' => $this->accountDescription,
                'totalAmount' => number_format($totalAmount, 2, '.', ''),
                'userid' => $this->operationalUserId,  // Use operational user ID (URA.SACCOSS)
                'clientId' => $this->clientId,         // Add client ID (urasaccoss)
                'callbackURL' => $callbackUrl,
                'beneficiaries' => [
                    'beneficiary' => $beneficiaries
                ]
            ];
            try {
                $response = Http::withHeaders($this->getHeaders())
                    ->timeout($this->timeout)
                    ->post($this->baseUrl . '/host-to-host-adapter/v1.0/pay', $payload);

                $responseData = $response->json();
                
                // Enhanced logging for debugging
                Log::info('NMB API Response', [
                    'batch_id' => $batchId,
                    'status_code' => $response->status(),
                    'response' => $responseData,
                    'request_payload' => [
                        'batchId' => $batchId,
                        'institution' => $this->institution,
                        'userid' => $this->operationalUserId,
                        'clientId' => $this->clientId,
                        'accountCode' => $this->accountCode,
                        'beneficiaries_count' => count($beneficiaries)
                    ]
                ]);

                if ($response->successful() && 
                    isset($responseData['payload']['respStatus']) && 
                    $responseData['payload']['respStatus'] === 'Success') {
                    
                    return [
                        'success' => true,
                        'batch_id' => $responseData['payload']['respHeader']['BatchId'] ?? $batchId,
                        'file_number' => $responseData['payload']['respHeader']['FileReferenceNumber'] ?? null,
                        'message' => $responseData['payload']['respBody']['message'] ?? 'Batch submitted successfully',
                        'body' => $responseData
                    ];
                }

                $lastError = $responseData['payload']['respHeader']['ErrorDesc'] ?? 
                            $responseData['payload']['respBody']['ErrorDesc'] ?? 
                            'Unknown error';
                
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                Log::warning("NMB API attempt {$attempts} failed", ['error' => $lastError]);
            }
            
            $attempts++;
            
            if ($attempts < $this->retryAttempts) {
                sleep(pow(2, $attempts)); // Exponential backoff
            }
        }

        return [
            'success' => false,
            'error' => $lastError,
            'batch_id' => $lastBatchId
        ];
    }

    /**
     * Check payment status from NMB
     */
    public function checkPaymentStatus(string $batchId, int $pageNumber = 1, int $pageSize = 20): array
    {
        try {
            $payload = [
                'userId' => $this->userId,
                'batchId' => $batchId,
                'pageNumber' => $pageNumber,
                'pageSize' => $pageSize
            ];

            $response = Http::withHeaders($this->getHeaders())
                ->timeout($this->timeout)
                ->post($this->baseUrl . '/host-to-host-adapter/v1.0/status_inquiry', $payload);

            $responseData = $response->json();
            
            Log::info('NMB Status Check Response', [
                'batch_id' => $batchId,
                'response' => $responseData
            ]);

            if ($response->successful() && 
                isset($responseData['payload']['RespStatus']) && 
                $responseData['payload']['RespStatus'] === 'Success') {
                
                return [
                    'success' => true,
                    'data' => $responseData['payload'],
                    'records' => $responseData['payload']['RespBody']['body']['fileRecords'] ?? []
                ];
            }

            return [
                'success' => false,
                'error' => $responseData['payload']['RespBody']['ErrorDesc'] ?? 'Status check failed'
            ];
            
        } catch (\Exception $e) {
            Log::error('NMB status check failed', [
                'batch_id' => $batchId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get simple payment status (new endpoint in v1.3)
     */
    public function getPaymentStatus(string $batchId): array
    {
        try {
            $payload = [
                'batchId' => $batchId
            ];

            $response = Http::withHeaders($this->getHeaders())
                ->timeout($this->timeout)
                ->post($this->baseUrl . '/host-to-host-adapter/v1.0/payment_status', $payload);

            $responseData = $response->json();
            
            if ($response->successful() && 
                isset($responseData['payload']['RespStatus']) && 
                $responseData['payload']['RespStatus'] === 'SUCCESS') {
                
                $fileRecords = $responseData['payload']['RespBody']['fileRecords'] ?? [];
                
                return [
                    'success' => true,
                    'records' => $fileRecords
                ];
            }

            return [
                'success' => false,
                'error' => $responseData['payload']['RespStatus'] ?? 'Payment status check failed'
            ];
            
        } catch (\Exception $e) {
            Log::error('NMB payment status failed', [
                'batch_id' => $batchId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process callback from NMB and update loan statuses
     */
    public function processCallback(array $callbackData): bool
    {
        try {
            $batchId = $callbackData['paymentStatus']['respHeader']['batchId'] ?? null;
            
            if (!$batchId) {
                Log::warning('NMB callback without batch ID', ['data' => $callbackData]);
                return false;
            }

            // Check for duplicate callback
            $existingCallback = NmbCallback::where('batch_id', $batchId)
                ->where('callback_data', json_encode($callbackData))
                ->first();
                
            if ($existingCallback) {
                Log::info('Duplicate NMB callback ignored', ['batch_id' => $batchId]);
                return true;
            }

            // Store callback
            $callback = NmbCallback::create([
                'batch_id' => $batchId,
                'callback_data' => $callbackData,
                'status' => $callbackData['paymentStatus']['respHeader']['status'] ?? 'Unknown',
                'status_code' => $callbackData['paymentStatus']['respHeader']['statusCode'] ?? null,
                'total_records' => $callbackData['paymentStatus']['respHeader']['totalRecords'] ?? 0,
                'success_records' => $callbackData['paymentStatus']['respHeader']['successRecords'] ?? 0,
                'failed_records' => $callbackData['paymentStatus']['respHeader']['failedRecords'] ?? 0,
                'status_description' => $callbackData['paymentStatus']['respHeader']['failureReason'] ?? null
            ]);

            // Process individual records
            $fileRecords = $callbackData['paymentStatus']['respBody']['fileRecords'] ?? [];
            
            foreach ($fileRecords as $record) {
                $this->processCallbackRecord($record, $batchId);
            }

            return true;
            
        } catch (\Exception $e) {
            Log::error('NMB callback processing failed', [
                'error' => $e->getMessage(),
                'data' => $callbackData
            ]);
            
            return false;
        }
    }

    /**
     * Process individual callback record
     */
    private function processCallbackRecord(array $record, string $batchId): void
    {
        $endToEndId = $record['endToEndId'] ?? null;
        $status = strtolower($record['status'] ?? '');
        $statusDesc = $record['statusDesc'] ?? null;
        
        if (!$endToEndId) {
            Log::warning('Callback record without endToEndId', ['record' => $record]);
            return;
        }

        // Find loan by application number
        $loanOffer = LoanOffer::where('application_number', $endToEndId)
            ->orWhere('nmb_batch_id', $batchId)
            ->first();
            
        if (!$loanOffer) {
            Log::warning('Loan not found for callback', [
                'endToEndId' => $endToEndId,
                'batch_id' => $batchId
            ]);
            return;
        }

        // Update loan status based on NMB response
        if ($status === 'success' || $status === 'completed') {
            $loanOffer->update([
                'status' => 'disbursed',
                'state' => 'disbursed',
                'nmb_batch_id' => $batchId
            ]);
            
            // Update disbursement record
            LoanDisbursement::where('loan_offer_id', $loanOffer->id)
                ->update([
                    'status' => 'success',
                    'disbursement_date' => Carbon::now(),
                    'reference_number' => $record['fileRefId'] ?? $batchId
                ]);
                
            Log::info('Loan disbursement confirmed', [
                'loan_id' => $loanOffer->id,
                'batch_id' => $batchId
            ]);
            
        } elseif ($status === 'failed' || $status === 'error') {
            // Truncate reason to 150 chars for API compliance
            $failureReason = mb_substr($statusDesc ?? 'Transaction failed at NMB', 0, 150);
            
            $loanOffer->update([
                'status' => 'DISBURSEMENT_FAILED',
                'state' => 'Disbursement Failure',
                'reason' => $failureReason,
                'nmb_batch_id' => $batchId
            ]);
            
            // Update disbursement record
            LoanDisbursement::where('loan_offer_id', $loanOffer->id)
                ->update([
                    'status' => 'failed',
                    'failure_reason' => $failureReason,
                    'reference_number' => $record['fileRefId'] ?? $batchId
                ]);
                
            Log::error('Loan disbursement failed', [
                'loan_id' => $loanOffer->id,
                'batch_id' => $batchId,
                'reason' => $failureReason
            ]);
        }
    }

    /**
     * Reconcile transactions for a given date
     */
    public function reconcileTransactions(Carbon $date): array
    {
        $reconciledCount = 0;
        $discrepancies = [];
        
        // Get all loans that should have been disbursed on this date
        $loans = LoanOffer::whereDate('updated_at', $date)
            ->whereIn('status', ['disbursement_pending', 'disbursed', 'DISBURSEMENT_FAILED'])
            ->whereNotNull('nmb_batch_id')
            ->get();
            
        foreach ($loans as $loan) {
            $statusResult = $this->getPaymentStatus($loan->nmb_batch_id);
            
            if ($statusResult['success']) {
                foreach ($statusResult['records'] as $record) {
                    if ($record['endToEndId'] === $loan->application_number) {
                        $nmbStatus = strtolower($record['status']);
                        $expectedStatus = $loan->status === 'disbursed' ? 'success' : 'failed';
                        
                        if (($nmbStatus === 'success' && $loan->status !== 'disbursed') ||
                            ($nmbStatus === 'failed' && $loan->status === 'disbursed')) {
                            $discrepancies[] = [
                                'loan_id' => $loan->id,
                                'application_number' => $loan->application_number,
                                'local_status' => $loan->status,
                                'nmb_status' => $record['status']
                            ];
                        }
                        
                        $reconciledCount++;
                        break;
                    }
                }
            }
        }
        
        return [
            'reconciled' => $reconciledCount,
            'discrepancies' => $discrepancies,
            'total_checked' => $loans->count()
        ];
    }

    /**
     * Helper method to format beneficiary name
     */
    private function formatBeneficiaryName(LoanOffer $loan): string
    {
        $name = trim(($loan->first_name ?? '') . ' ' . ($loan->last_name ?? ''));
        // NMB has 35 character limit for names
        return mb_substr($name ?: 'CUSTOMER', 0, 35);
    }

    /**
     * Helper method to get channel identifier
     */
    private function getChannelIdentifier(LoanOffer $loan): string
    {
        $swiftCode = strtoupper($loan->swift_code ?? $loan->bank->swift_code ?? '');
        $bankName = strtoupper($loan->bank->name ?? '');
        $bankShortName = strtoupper($loan->bank->short_name ?? '');
        
        // Check if it's NMB - use INTERNAL for NMB to NMB transfers
        if (strpos($swiftCode, 'NMB') !== false || 
            strpos($swiftCode, 'NMIBTZTZ') !== false ||
            strpos($bankName, 'NMB') !== false ||
            $bankShortName === 'NMB') {
            return 'INTERNAL';
        }
        
        // Check if it's a mobile money transfer
        $mobileProviders = ['MPESA', 'TIGOPESA', 'HALOPESA', 'AIRTEL'];
        foreach ($mobileProviders as $provider) {
            if (strpos($swiftCode, $provider) !== false ||
                strpos($bankName, $provider) !== false) {
                return 'MNO';
            }
        }
        
        // Check for TISS banks (large value transfers above 10 million)
        $tissAmount = 10000000; // 10 million TZS
        $amount = $loan->take_home_amount ?? $loan->net_loan_amount ?? 0;
        if ($amount >= $tissAmount) {
            return 'TISS';
        }
        
        // Default to DOMESTIC for other banks
        return 'DOMESTIC';
    }

    /**
     * Helper method to get destination code
     */
    private function getDestinationCode(LoanOffer $loan): string
    {
        $swiftCode = strtoupper($loan->swift_code ?? $loan->bank->swift_code ?? '');
        $bankShortName = strtoupper($loan->bank->short_name ?? '');
        
        // If bank short name is available, use it directly
        if (!empty($bankShortName)) {
            return $bankShortName;
        }
        
        // Map SWIFT codes to NMB destination codes
        $destinationMap = [
            'NMIB' => 'NMB',
            'NMBTZ' => 'NMB',
            'NMIBTZTZ' => 'NMB',
            'NBCTZ' => 'NBC',
            'CRDBTZ' => 'CRDB',
            'STANBICTZ' => 'STANBIC',
            'STANCHARTTZ' => 'STANCHART',
            'BARBTZ' => 'BARCLAYS',
            'DTBTZ' => 'DTB',
            'KCBTZ' => 'KCB',
            'EXIM' => 'EXIM',
            'AKIBA' => 'AKIBA',
            'BOA' => 'BOA',
            'CITI' => 'CITI',
            'PBZ' => 'PBZ',
            'BOT' => 'BOT'
        ];
        
        foreach ($destinationMap as $code => $destination) {
            if (strpos(strtoupper($swiftCode), $code) !== false) {
                return $destination;
            }
        }
        
        // Default to NMB for unknown codes
        return 'NMB';
    }

    /**
     * Generate narration for transaction
     */
    private function generateNarration(LoanOffer $loan): string
    {
        $type = ($loan->loan_type === 'topup') ? 'TOP-UP' : 'NEW';
        $narration = "URASACCOS {$type} LOAN {$loan->application_number}";
        // NMB has 50 character limit for narration
        return mb_substr($narration, 0, 50);
    }

    /**
     * Generate unique batch ID
     */
    private function generateBatchId(): string
    {
        // Format: URA + timestamp + random number
        // Must be 5-15 characters total
        // Using microseconds and random to ensure uniqueness
        $timestamp = substr(str_replace('.', '', microtime(true)), -9);
        $random = mt_rand(10, 99);
        return 'URA' . $timestamp . $random;
    }
}