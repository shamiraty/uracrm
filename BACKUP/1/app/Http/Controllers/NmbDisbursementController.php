<?php

namespace App\Http\Controllers;

use App\Models\LoanOffer;
use App\Services\NmbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NmbDisbursementController extends Controller
{
    private $nmbService;
    
    public function __construct(NmbService $nmbService)
    {
        $this->nmbService = $nmbService;
    }
    
    /**
     * Handles the asynchronous callback notification from NMB.
     * Enhanced with validation, duplicate protection, and proper error handling
     */
    // public function handleCallback(Request $request)
    // {
    //     // Log the entire incoming request from NMB for auditing
    //     Log::info('NMB Callback Received:', ['data' => $request->all()]);

    //     try {
    //         // Validate callback structure
    //         if (!$request->has('paymentStatus')) {
    //             Log::warning('Invalid NMB callback structure');
    //             return response()->json(['status' => 'error', 'message' => 'Invalid callback structure'], 400);
    //         }

    //         // Process callback using the service
    //         $result = $this->nmbService->processCallback($request->all());
            
    //         if ($result) {
    //             return response()->json(['status' => 'received'], 200);
    //         } else {
    //             return response()->json(['status' => 'processing_error'], 500);
    //         }
            
    //     } catch (\Exception $e) {
    //         Log::error('NMB callback processing exception', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
            
    //         // Still return 200 to prevent NMB from retrying
    //         return response()->json(['status' => 'received_with_error'], 200);
    //     }
    // }
    

     public function handleCallback(Request $request)
    {
        // Log the entire incoming request from NMB for auditing
        Log::info('NMB Callback Received:', [
            'data' => $request->all(),
            'headers' => $request->headers->all(),
            'method' => $request->method(),
            'ip' => $request->ip()
        ]);

        try {
            $callbackData = $request->all();
            
            // Check for different possible callback structures
            // Some APIs send direct payload, others wrap in paymentStatus
            if (!isset($callbackData['paymentStatus']) && isset($callbackData['payload'])) {
                // Wrap in expected structure if needed
                $callbackData = ['paymentStatus' => $callbackData];
            }
            
            // Validate callback structure
            if (!isset($callbackData['paymentStatus']) && !isset($callbackData['batchId'])) {
                Log::warning('Invalid NMB callback structure - missing expected fields', [
                    'received_keys' => array_keys($callbackData)
                ]);
                // Still acknowledge receipt to prevent retries
                return response()->json(['status' => 'received'], 200);
            }

            // Process callback using the service
            $result = $this->nmbService->processCallback($callbackData);
            
            if ($result) {
                Log::info('NMB callback processed successfully');
                return response()->json(['status' => 'received'], 200);
            } else {
                Log::warning('NMB callback processing returned false');
                return response()->json(['status' => 'received'], 200);
            }
            
        } catch (\Exception $e) {
            Log::error('NMB callback processing exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Still return 200 to prevent NMB from retrying
            return response()->json(['status' => 'received_with_error'], 200);
        }
    }
    /**
     * Check payment status for a specific batch
     */
    public function checkStatus(Request $request, $batchId = null)
    {
        try {
            $batchId = $batchId ?? $request->input('batch_id');
            
            if (!$batchId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batch ID is required'
                ], 400);
            }
            
            $result = $this->nmbService->checkPaymentStatus($batchId);
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Status check failed', [
                'batch_id' => $batchId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Status check failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Process bulk disbursement for multiple loans
     */
    public function processBulkDisbursement(Request $request)
    {
        try {
            $loanIds = $request->input('loan_ids', []);
            
            if (empty($loanIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No loans selected for bulk disbursement'
                ], 400);
            }
            
            // Get loans with proper validation
            $loans = LoanOffer::whereIn('id', $loanIds)
                ->where('approval', 'APPROVED')
                ->whereNotIn('status', ['disbursed', 'DISBURSEMENT_FAILED'])
                ->get();
                
            if ($loans->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No eligible loans found for disbursement'
                ], 400);
            }
            
            // Process bulk disbursement
            $result = $this->nmbService->disburseBulkLoans($loans);
            
            if ($result['success']) {
                // Update all loans with batch ID
                $loans->each(function($loan) use ($result) {
                    $loan->update([
                        'nmb_batch_id' => $result['batch_id'],
                        'status' => 'disbursement_pending',
                        'state' => 'Submitted for disbursement'
                    ]);
                });
                
                return response()->json([
                    'success' => true,
                    'message' => 'Bulk disbursement initiated successfully',
                    'batch_id' => $result['batch_id'],
                    'count' => $loans->count()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Bulk disbursement failed'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Bulk disbursement failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk disbursement failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reconcile transactions for a given date
     */
    public function reconcileTransactions(Request $request)
    {
        try {
            $date = $request->input('date') ? 
                \Carbon\Carbon::parse($request->input('date')) : 
                \Carbon\Carbon::today();
                
            $result = $this->nmbService->reconcileTransactions($date);
            
            return response()->json([
                'success' => true,
                'date' => $date->format('Y-m-d'),
                'reconciled' => $result['reconciled'],
                'discrepancies' => $result['discrepancies'],
                'total_checked' => $result['total_checked']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Reconciliation failed', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Reconciliation failed: ' . $e->getMessage()
            ], 500);
        }
    }
}