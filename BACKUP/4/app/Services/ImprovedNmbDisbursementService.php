<?php

namespace App\Services;

use App\Models\LoanOffer;
use App\Models\Bank;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImprovedNmbDisbursementService
{
    protected $baseUrl;
    protected $sharedSecret;
    protected $userId;
    protected $institution;
    protected $accountCode;
    protected $accountDescription;
    
    /**
     * Mapping of SWIFT codes to NMB destination codes
     * Based on bank and swift code.txt file
     */
    protected $swiftCodeMapping = [
        'NMIBTZTZ' => 'NMB',           // NMB Bank PLC (Internal)
        'NLCBTZTX' => 'NBC',           // National Bank of Commerce
        'CORUTZTZ' => 'CRDB',          // CRDB Bank Limited
        'BARCTZTZ' => 'ABSA',          // ABSA Bank Tanzania (formerly Barclays)
        'SBICTZTX' => 'STANBIC',       // Stanbic Bank Tanzania
        'SCBLTZTX' => 'SCB',           // Standard Chartered Bank
        'DTKETZTZ' => 'DTB',           // Diamond Trust Bank
        'IMBLTZTZ' => 'IM',            // I&M Bank
        'EQBLTZTZ' => 'EQUITY',        // Equity Bank Tanzania
        'KCBLTZTZ' => 'KCB',           // KCB Bank Tanzania
        'EUAFTZTZ' => 'BOA',           // Bank of Africa Tanzania
        'EXTNTZTZ' => 'EXIM',          // EXIM Bank Tanzania
        'GTBITZTZ' => 'GTB',           // Guaranty Trust Bank
        'AKCOTZTZ' => 'AKIBA',         // Akiba Commercial Bank
        'AMNNTZTZ' => 'AMANA',         // Amana Bank
        'AZANTZTZ' => 'AZANIA',        // Azania Bank
        'CBAFTZTZ' => 'CBA',           // Commercial Bank of Africa
        'CITITZTZ' => 'CITI',          // Citibank Tanzania
        'ECOCTZTZ' => 'ECOBANK',       // Ecobank Tanzania
        'TAPBTZTZ' => 'TCB',           // Tanzania Commercial Bank
        'UNAFTZTZ' => 'UBA',           // United Bank for Africa
        'ACTZTZTZ' => 'ACCESS',        // Access Bank Tanzania
        'ADVBTZTZ' => 'ADVANS',        // Advans Bank Tanzania
        'BARBTZTZ' => 'BOB',           // Bank of Baroda Tanzania
        'BKIDTZTZ' => 'BOI',           // Bank of India Tanzania
        'BKMYTZTZ' => 'ICB',           // International Commercial Bank
        'CDSHTZTZ' => 'CDB',           // China Dasheng Bank
        'CNRBTZTZ' => 'CANARA',        // Canara Bank Tanzania
        'DASUTZTZ' => 'DCB',           // Dar es Salaam Community Bank
        'FIRNTZTX' => 'FNB',           // First National Bank
        'FMBZTZTX' => 'ABC',           // African Banking Corporation
        'FNMITZTZ' => 'FINCA',         // FINCA Microfinance Bank
        'HABLTZTZ' => 'HABIB',         // Habib African Bank
        'MBTLTZTZ' => 'MAENDELEO',     // Maendeleo Bank
        'MKCBTZTZ' => 'MKOMBOZI',      // Mkombozi Commercial Bank
        'MUOBTZTZ' => 'MUCOBA',        // Mucoba Bank
        'MWCBTZTZ' => 'MWANGA',        // Mwanga Community Bank
        'MWCOTZTZ' => 'MWALIMU',       // Mwalimu Commercial Bank
        'PBZATZTZ' => 'PBZ',           // People's Bank of Zanzibar
        'TZADTZTZ' => 'TADB',          // Tanzania Agricultural Development Bank
        'UCCTTZTZ' => 'UCHUMI',        // Uchumi Commercial Bank
        'YETMTZTZ' => 'YETU',          // Yetu Microfinance Bank
    ];
    
    /**
     * Mobile Network Operator codes
     */
    protected $mnoMapping = [
        'VODACOM' => 'MPESA',
        'TIGO' => 'TIGOPESA',
        'AIRTEL' => 'AIRTEL',
        'HALOTEL' => 'HALOPESA',
        'ZANTEL' => 'EZYPESA',
    ];

    public function __construct()
    {
        $this->baseUrl = config('services.nmb.base_url');
        $this->sharedSecret = config('services.nmb.shared_secret');
        $this->userId = config('services.nmb.user_id');
        $this->institution = config('services.nmb.institution');
        $this->accountCode = config('services.nmb.account_code');
        $this->accountDescription = config('services.nmb.account_description');
    }

    /**
     * Generate JWT token for authentication
     */
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
     * Determine channel identifier and destination code based on SWIFT code
     */
    protected function determineChannelAndDestination(LoanOffer $loanOffer): array
    {
        // Load bank relationship if not already loaded
        $loanOffer->load('bank');
        
        // Get SWIFT code from the loan offer or bank relationship
        $swiftCode = null;
        
        if ($loanOffer->bank && $loanOffer->bank->swift_code) {
            $swiftCode = strtoupper($loanOffer->bank->swift_code);
        } elseif ($loanOffer->swift_code) {
            $swiftCode = strtoupper($loanOffer->swift_code);
        }
        
        // Default values
        $channelIdentifier = 'DOMESTIC';
        $destinationCode = 'OTHER';
        
        // If no SWIFT code, try to determine from payment destination
        if (!$swiftCode && $loanOffer->paymentDestination) {
            $destination = $loanOffer->paymentDestination;
            
            if ($destination->type === 'MNO') {
                // Mobile Network Operator
                $channelIdentifier = 'MNO';
                $destinationCode = $this->mnoMapping[$destination->code] ?? $destination->code;
            } else {
                // Use payment destination code as fallback
                $destinationCode = $destination->code;
            }
        } elseif ($swiftCode) {
            // Determine from SWIFT code
            if ($swiftCode === 'NMIBTZTZ') {
                // Internal NMB transfer
                $channelIdentifier = 'INTERNAL';
                $destinationCode = 'NMB';
            } else {
                // External bank transfer
                $destinationCode = $this->swiftCodeMapping[$swiftCode] ?? $this->extractBankCodeFromSwift($swiftCode);
                
                // Determine channel based on amount
                $amount = (float)($loanOffer->take_home_amount ?: $loanOffer->requested_amount);
                
                if ($amount >= 20000000) {
                    // 20 million TZS or more - use TISS
                    $channelIdentifier = 'TISS';
                } else {
                    // Less than 20 million - use DOMESTIC
                    $channelIdentifier = 'DOMESTIC';
                }
            }
        }
        
        Log::info('Channel and destination determined', [
            'loan_offer_id' => $loanOffer->id,
            'swift_code' => $swiftCode,
            'channel_identifier' => $channelIdentifier,
            'destination_code' => $destinationCode,
            'amount' => $loanOffer->take_home_amount
        ]);
        
        return [
            'channelIdentifier' => $channelIdentifier,
            'destinationCode' => $destinationCode
        ];
    }
    
    /**
     * Extract bank code from SWIFT code if not in mapping
     */
    protected function extractBankCodeFromSwift(string $swiftCode): string
    {
        // SWIFT codes typically have 8 or 11 characters
        // First 4 chars are bank code
        $bankCode = substr($swiftCode, 0, 4);
        
        // Common patterns
        $knownPatterns = [
            'NLCB' => 'NBC',
            'CORU' => 'CRDB',
            'BARC' => 'ABSA',
            'SBIC' => 'STANBIC',
            'SCBL' => 'SCB',
            'DTKE' => 'DTB',
            'IMBL' => 'IM',
            'EQBL' => 'EQUITY',
            'KCBL' => 'KCB',
        ];
        
        return $knownPatterns[$bankCode] ?? $bankCode;
    }

    /**
     * Disburse loan with improved SWIFT code handling
     */
    public function disburseLoan(LoanOffer $loanOffer): array
    {
        // Load relationships
        $loanOffer->load(['bank', 'paymentDestination']);
        
        // Determine disbursement amount (use take_home_amount for actual disbursement)
        $disbursementAmount = $loanOffer->take_home_amount ?: $loanOffer->requested_amount;
        
        if (!$disbursementAmount || $disbursementAmount <= 0) {
            throw new \Exception('Invalid disbursement amount');
        }
        
        // Determine channel and destination
        $channelInfo = $this->determineChannelAndDestination($loanOffer);
        
        // Generate token
        $token = $this->generateJwtToken();
        
        // Prepare payload
        $totalAmountString = number_format($disbursementAmount, 2, '.', '');
        $beneficiaryName = implode(' ', array_filter([
            $loanOffer->first_name,
            $loanOffer->middle_name,
            $loanOffer->last_name
        ]));
        
        $payload = [
            "batchId" => 'URA' . $loanOffer->id . time(),
            "batchDate" => now()->toIso8601String(),
            "institution" => $this->institution,
            "accountCode" => $this->accountCode,
            "accountDescription" => $this->accountDescription,
            "callbackURL" => route('nmb.callback'),
            "totalAmount" => $totalAmountString,
            "userid" => "URA.SACCOSS",
            "clientId" => "urasaccoss",
            "gwxReference" => 'GWX' . time() . $loanOffer->id,
            "consoleReq" => "Y",
            "beneficiaries" => [
                "beneficiary" => [
                    [
                        "ROWNUM" => "1",
                        "NAME" => $beneficiaryName,
                        "CURRENCY" => "TZS",
                        "amount" => (float)$disbursementAmount,
                        "ACCOUNT" => $loanOffer->bank_account_number,
                        "channelIdentifier" => $channelInfo['channelIdentifier'],
                        "destinationCode" => $channelInfo['destinationCode'],
                        "OWNREFERENCE" => 'LOAN' . $loanOffer->id,
                        "NARRATION" => 'Loan Disbursement Ref ' . $loanOffer->application_number,
                    ]
                ]
            ]
        ];
        
        // Log the request
        Log::info('NMB Disbursement Request', [
            'loan_offer_id' => $loanOffer->id,
            'swift_code' => $loanOffer->swift_code ?? $loanOffer->bank->swift_code ?? null,
            'channel' => $channelInfo['channelIdentifier'],
            'destination' => $channelInfo['destinationCode'],
            'amount' => $disbursementAmount,
            'payload' => $payload
        ]);
        
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'GWX-Authorization' => 'Bearer ' . $token,
            ])->post($this->baseUrl . '/host-to-host-adapter/v1.0/pay', $payload);
            
            $responseData = $response->json();
            
            Log::info('NMB Disbursement Response', [
                'loan_offer_id' => $loanOffer->id,
                'status' => $response->status(),
                'body' => $responseData
            ]);
            
            // Store disbursement details in database if successful
            if (isset($responseData['body']['payload']['RespStatus']) && 
                $responseData['body']['payload']['RespStatus'] === 'Success') {
                
                $this->storeDisbursementDetails($loanOffer, $channelInfo, $responseData);
            }
            
            return $responseData;
            
        } catch (\Exception $e) {
            Log::error('NMB Disbursement Error', [
                'loan_offer_id' => $loanOffer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'body' => [
                    'payload' => [
                        'RespStatus' => 'Failure',
                        'RespBody' => ['ErrorDesc' => $e->getMessage()]
                    ]
                ]
            ];
        }
    }
    
    /**
     * Store disbursement details for audit trail
     */
    protected function storeDisbursementDetails(LoanOffer $loanOffer, array $channelInfo, array $response): void
    {
        try {
            // Update loan offer with batch ID for backward compatibility
            $batchId = $response['body']['payload']['RespHeader']['BatchId'] ?? null;
            $loanOffer->nmb_batch_id = $batchId;
            $loanOffer->save();
            
            // Get SWIFT code
            $swiftCode = $loanOffer->swift_code ?? ($loanOffer->bank ? $loanOffer->bank->swift_code : null);
            
            // Create or update disbursement record in normalized table
            \App\Models\LoanDisbursement::updateOrCreate(
                [
                    'loan_offer_id' => $loanOffer->id,
                    'batch_id' => $batchId
                ],
                [
                    'bank_id' => $loanOffer->bank_id,
                    'channel_identifier' => $channelInfo['channelIdentifier'],
                    'destination_code' => $channelInfo['destinationCode'],
                    'swift_code' => $swiftCode,
                    'status' => 'pending',
                    'amount' => $loanOffer->requested_amount,
                    'net_amount' => $loanOffer->take_home_amount,
                    'account_number' => $loanOffer->bank_account_number,
                    'account_name' => implode(' ', array_filter([
                        $loanOffer->first_name,
                        $loanOffer->middle_name,
                        $loanOffer->last_name
                    ])),
                    'reference_number' => $response['body']['payload']['gwxReference'] ?? null,
                    'response_data' => $response
                ]
            );
            
            Log::info('Disbursement details stored', [
                'loan_offer_id' => $loanOffer->id,
                'batch_id' => $batchId,
                'channel' => $channelInfo['channelIdentifier'],
                'destination' => $channelInfo['destinationCode'],
                'swift_code' => $swiftCode
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error storing disbursement details', [
                'loan_offer_id' => $loanOffer->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get bank information by SWIFT code
     */
    public function getBankBySwiftCode(string $swiftCode): ?array
    {
        $swiftCode = strtoupper($swiftCode);
        
        // Check if SWIFT code exists in our mapping
        if (isset($this->swiftCodeMapping[$swiftCode])) {
            return [
                'swift_code' => $swiftCode,
                'destination_code' => $this->swiftCodeMapping[$swiftCode],
                'is_nmb' => $swiftCode === 'NMIBTZTZ'
            ];
        }
        
        return null;
    }
    
    /**
     * Validate SWIFT code
     */
    public function isValidSwiftCode(string $swiftCode): bool
    {
        $swiftCode = strtoupper($swiftCode);
        return isset($this->swiftCodeMapping[$swiftCode]);
    }
}