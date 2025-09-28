<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use App\Services\EssIntegrationService;
use App\Services\EssXmlResponseService;
use App\Services\NmbDisbursementService;
use App\Repositories\LoanOfferRepository;

/**
 * Improved Employee Loan Controller with better separation of concerns
 * Maintains full ESS API compatibility while using normalized database structure
 */
class ImprovedEmployeeLoanController extends Controller
{
    protected $essIntegrationService;
    protected $xmlResponseService;
    protected $nmbService;
    protected $loanOfferRepository;
    
    public function __construct(
        EssIntegrationService $essIntegrationService,
        EssXmlResponseService $xmlResponseService,
        NmbDisbursementService $nmbService,
        LoanOfferRepository $loanOfferRepository
    ) {
        $this->essIntegrationService = $essIntegrationService;
        $this->xmlResponseService = $xmlResponseService;
        $this->nmbService = $nmbService;
        $this->loanOfferRepository = $loanOfferRepository;
    }
    
    /**
     * Main entry point for ESS XML requests
     */
    public function handleRequest(Request $request)
    {
        $xmlContent = $request->getContent();
        Log::info('Received XML request', ['xml' => $xmlContent]);
        
        // Validate XML content
        if (empty($xmlContent)) {
            return $this->xmlResponseService->generateResponse('8001', 'Empty XML content');
        }
        
        if (!$this->isValidXml($xmlContent)) {
            return $this->xmlResponseService->generateResponse('8001', 'Invalid XML format');
        }
        
        // Extract and verify signature
        $signature = $this->extractSignature($xmlContent);
        if (!$signature) {
            return $this->xmlResponseService->generateResponse('8009', 'Invalid Signature');
        }
        
        if (!$this->verifySignature($xmlContent, $signature)) {
            return $this->xmlResponseService->generateResponse('8009', 'Invalid Signature');
        }
        
        // Parse and dispatch to appropriate handler
        return $this->parseAndDispatch($xmlContent);
    }
    
    /**
     * Parse XML and dispatch to appropriate handler
     */
    private function parseAndDispatch($xmlContent)
    {
        try {
            $xml = new SimpleXMLElement($xmlContent);
            $messageType = strtoupper(trim((string)($xml->Data->Header->MessageType ?? null)));
            
            Log::info('Processing message type', ['messageType' => $messageType]);
            
            if (!$messageType) {
                return $this->xmlResponseService->generateResponse('8002', 'MessageType not specified');
            }
            
            return $this->handleMessageType($messageType, $xml);
            
        } catch (\Exception $e) {
            Log::error('Error processing XML', ['error' => $e->getMessage()]);
            return $this->xmlResponseService->generateResponse('8001', 'Invalid XML format or processing error');
        }
    }
    
    /**
     * Route message to appropriate handler based on type
     */
    private function handleMessageType($messageType, $xml)
    {
        Log::info('Handling message type', ['messageType' => $messageType]);
        
        switch ($messageType) {
            case 'LOAN_CHARGES_REQUEST':
                return $this->essIntegrationService->processLoanChargesRequest($xml);
                
            case 'LOAN_OFFER_REQUEST':
                return $this->essIntegrationService->processLoanOfferRequest($xml);
                
            case 'LOAN_FINAL_APPROVAL_NOTIFICATION':
                return $this->essIntegrationService->processLoanFinalApproval($xml);
                
            case 'LOAN_CANCELLATION_NOTIFICATION':
                return $this->handleLoanCancellation($xml);
                
            case 'FSP_MONTHLY_DEDUCTIONS':
                return $this->handleMonthlyDeductions($xml);
                
            case 'TOP_UP_PAY_0FF_BALANCE_REQUEST':
                return $this->essIntegrationService->processTopUpBalanceRequest($xml);
                
            case 'TOP_UP_OFFER_REQUEST':
                return $this->essIntegrationService->processTopUpOfferRequest($xml);
                
            case 'LOAN_LIQUIDATION_NOTIFICATION':
                return $this->handleLoanLiquidation($xml);
                
            default:
                Log::error('Unsupported message type', ['messageType' => $messageType]);
                return $this->xmlResponseService->generateResponse('8003', 'Unsupported MessageType');
        }
    }
    
    /**
     * Handle loan cancellation notification
     */
    private function handleLoanCancellation($xml)
    {
        $messageDetails = $xml->Data->MessageDetails;
        $applicationNumber = (string)$messageDetails->ApplicationNumber;
        $reason = (string)$messageDetails->Reason;
        
        $loanOffer = $this->loanOfferRepository->findByApplicationNumber($applicationNumber);
        
        if (!$loanOffer) {
            return $this->xmlResponseService->generateResponse('8011', 'Loan application not found');
        }
        
        // Update loan status
        $loanOffer->status = 'CANCELLED';
        $loanOffer->approval = 'CANCELLED';
        $loanOffer->reason = $reason;
        $loanOffer->save();
        
        // Update approval record
        $this->loanOfferRepository->updateApproval(
            $applicationNumber,
            'final',
            'cancelled',
            ['reason' => $reason]
        );
        
        Log::info('Loan cancelled', [
            'application_number' => $applicationNumber,
            'reason' => $reason
        ]);
        
        return $this->xmlResponseService->generateResponse('8000', 'Loan cancellation processed successfully');
    }
    
    /**
     * Handle monthly deductions notification
     */
    private function handleMonthlyDeductions($xml)
    {
        // This would integrate with your monthly deduction processing system
        // For now, just acknowledge receipt
        
        Log::info('Monthly deductions received', ['xml' => $xml->asXML()]);
        
        return $this->xmlResponseService->generateResponse('8000', 'Monthly deductions processed successfully');
    }
    
    /**
     * Handle loan liquidation notification
     */
    private function handleLoanLiquidation($xml)
    {
        $messageDetails = $xml->Data->MessageDetails;
        $loanNumber = (string)$messageDetails->LoanNumber;
        $liquidationDate = (string)$messageDetails->LiquidationDate;
        
        $loanOffer = LoanOffer::where('loan_number', $loanNumber)->first();
        
        if (!$loanOffer) {
            return $this->xmlResponseService->generateResponse('8011', 'Loan not found');
        }
        
        // Update loan status
        $loanOffer->status = 'LIQUIDATED';
        $loanOffer->liquidation_date = $liquidationDate;
        $loanOffer->save();
        
        Log::info('Loan liquidated', [
            'loan_number' => $loanNumber,
            'liquidation_date' => $liquidationDate
        ]);
        
        return $this->xmlResponseService->generateResponse('8000', 'Loan liquidation processed successfully');
    }
    
    /**
     * Approve a loan offer
     */
    public function approve(Request $request, $id)
    {
        try {
            $loanOffer = \App\Models\LoanOffer::findOrFail($id);
            
            // Update loan offer approval status
            $loanOffer->approval = 'APPROVED';
            $loanOffer->reason = $request->input('reason', 'Approved by admin');
            $loanOffer->other_charges = $request->input('other_charges', $loanOffer->other_charges);
            $loanOffer->total_amount_to_pay = $request->input('total_amount_to_pay', $loanOffer->total_amount_to_pay);
            $loanOffer->save();
            
            // Create or update approval record in normalized table
            \App\Models\LoanOfferApproval::updateOrCreate(
                [
                    'loan_offer_id' => $loanOffer->id,
                    'approval_type' => 'final'
                ],
                [
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'reason' => $request->input('reason', 'Approved by admin'),
                    'total_amount_to_pay' => $request->input('total_amount_to_pay', $loanOffer->total_amount_to_pay),
                    'other_charges' => $request->input('other_charges', $loanOffer->other_charges)
                ]
            );
            
            Log::info('Loan offer approved', [
                'loan_offer_id' => $loanOffer->id,
                'approved_by' => auth()->id(),
                'approval_type' => 'final'
            ]);
            
            // Send notification to ESS if needed
            try {
                // You can uncomment this if you have the notification method
                // $this->notifyEssOnInitialApproval($loanOffer->id);
            } catch (\Exception $e) {
                Log::error("ESS notification failed for loan #{$id}", [
                    'error' => $e->getMessage()
                ]);
            }
            
            return redirect()->route('loan-offers.v2.edit', $id)
                ->with('status', 'Loan offer approved successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error approving loan offer', [
                'loan_offer_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('loan-offers.v2.edit', $id)
                ->with('error', 'Failed to approve loan offer: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject a loan offer
     */
    public function reject(Request $request, $id)
    {
        try {
            $loanOffer = \App\Models\LoanOffer::findOrFail($id);
            
            // Update loan offer rejection status
            $loanOffer->approval = 'REJECTED';
            $loanOffer->reason = $request->input('reason', 'Rejected by admin');
            $loanOffer->save();
            
            // Create or update rejection record in normalized table
            \App\Models\LoanOfferApproval::updateOrCreate(
                [
                    'loan_offer_id' => $loanOffer->id,
                    'approval_type' => 'final'
                ],
                [
                    'status' => 'rejected',
                    'rejected_by' => auth()->id(),
                    'rejected_at' => now(),
                    'reason' => $request->input('reason', 'Rejected by admin')
                ]
            );
            
            Log::info('Loan offer rejected', [
                'loan_offer_id' => $loanOffer->id,
                'rejected_by' => auth()->id(),
                'reason' => $request->input('reason', 'Rejected by admin'),
                'approval_type' => 'final'
            ]);
            
            return redirect()->route('loan-offers.v2.edit', $id)
                ->with('status', 'Loan offer rejected successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error rejecting loan offer', [
                'loan_offer_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('loan-offers.v2.edit', $id)
                ->with('error', 'Failed to reject loan offer: ' . $e->getMessage());
        }
    }
    
    /**
     * Show edit form for a loan offer
     */
    public function edit($id)
    {
        $loanOffer = \App\Models\LoanOffer::with([
            'bank',
            'approvals.approvedBy',
            'approvals.rejectedBy',
            'disbursements.disbursedBy',
            'disbursements.bank',
            'topupAsNew.originalLoan',
            'topupAsOriginal.newLoan'
        ])->findOrFail($id);
        
        $destinations = \App\Models\PaymentDestination::orderBy('name')->get()->groupBy('type');
        
        return view('employee_loan.edit_enhanced', compact('loanOffer', 'destinations'));
    }
    
    /**
     * Process loan disbursement through NMB
     */
    public function processDisbursement($loanOfferId)
    {
        try {
            $loanOffer = $this->loanOfferRepository->findByApplicationNumber($loanOfferId);
            
            if (!$loanOffer) {
                return response()->json(['error' => 'Loan offer not found'], 404);
            }
            
            // Check if approved
            if (!$loanOffer->isFullyApproved()) {
                return response()->json(['error' => 'Loan not approved'], 400);
            }
            
            // Create disbursement record
            $disbursement = $this->loanOfferRepository->createDisbursement($loanOffer->id, [
                'amount' => $loanOffer->requested_amount,
                'net_amount' => $loanOffer->take_home_amount
            ]);
            
            // Process through NMB
            $nmbResponse = $this->nmbService->disburseLoan($loanOffer);
            
            if (isset($nmbResponse['body']['payload']['RespStatus']) && 
                $nmbResponse['body']['payload']['RespStatus'] === 'Success') {
                
                $batchId = $nmbResponse['body']['payload']['RespHeader']['BatchId'];
                
                // Update disbursement status
                $this->loanOfferRepository->updateDisbursementStatus($disbursement->id, 'pending', [
                    'reference_number' => $batchId,
                    'response_data' => $nmbResponse
                ]);
                
                // Send disbursement notification to ESS
                $this->sendDisbursementNotification($loanOffer, 'Submitted to bank for processing');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Disbursement initiated',
                    'batch_id' => $batchId
                ]);
                
            } else {
                $errorMessage = $nmbResponse['body']['message'] ?? 'Unknown error';
                
                // Update disbursement as failed
                $this->loanOfferRepository->updateDisbursementStatus($disbursement->id, 'failed', [
                    'failure_reason' => $errorMessage,
                    'response_data' => $nmbResponse
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => $errorMessage
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Disbursement error', [
                'loan_offer_id' => $loanOfferId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send disbursement notification to ESS
     */
    private function sendDisbursementNotification($loanOffer, $status)
    {
        // This would send the notification to ESS endpoint
        // For now, just log it
        Log::info('Disbursement notification', [
            'loan_offer_id' => $loanOffer->id,
            'status' => $status
        ]);
        
        return true;
    }
    
    /**
     * Validate XML structure
     */
    private function isValidXml($xmlContent)
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlContent);
        
        if ($xml === false) {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                Log::error('XML parsing error', ['error' => $error->message]);
            }
            libxml_clear_errors();
            return false;
        }
        
        return true;
    }
    
    /**
     * Extract signature from XML
     */
    private function extractSignature($xmlContent)
    {
        try {
            $xml = new SimpleXMLElement($xmlContent);
            if (isset($xml->Signature)) {
                return (string)$xml->Signature;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error extracting signature', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Verify digital signature
     */
    private function verifySignature($xmlContent, $signature)
    {
        $publicKeyPath = config('services.ess.public_key_path', '/home/crm/ess_utumishi_go_tz.crt');
        
        if (!file_exists($publicKeyPath)) {
            Log::error('Public key file not found', ['path' => $publicKeyPath]);
            return false;
        }
        
        $publicKeyContent = file_get_contents($publicKeyPath);
        $publicKey = openssl_pkey_get_public($publicKeyContent);
        
        if (!$publicKey) {
            Log::error('Invalid public key content');
            return false;
        }
        
        $signatureDecoded = base64_decode($signature, true);
        if ($signatureDecoded === false) {
            Log::error('Failed to base64 decode the signature');
            return false;
        }
        
        // Remove signature node from XML
        $xmlContent = preg_replace('/<Signature>.*?<\/Signature>/s', '', $xmlContent);
        
        // Extract Data element for verification
        $startPos = strpos($xmlContent, '<Data>');
        $endPos = strpos($xmlContent, '</Data>') + strlen('</Data>');
        $dataElementAsString = substr($xmlContent, $startPos, $endPos - $startPos);
        
        // Verify using SHA256withRSA
        $verification = openssl_verify($dataElementAsString, $signatureDecoded, $publicKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($publicKey);
        
        if ($verification === 1) {
            Log::info('Signature verified successfully');
            return true;
        } elseif ($verification === 0) {
            Log::warning('Signature verification failed');
            return false;
        } else {
            Log::error('Error during signature verification', ['error' => openssl_error_string()]);
            return false;
        }
    }
}