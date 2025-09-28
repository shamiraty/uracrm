<?php

namespace App\Repositories;

use App\Models\LoanOffer;
use App\Models\LoanOfferApproval;
use App\Models\LoanDisbursement;
use App\Models\LoanOfferTopup;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoanOfferRepository
{
    /**
     * Create a new loan offer with proper relationships
     */
    public function createLoanOffer(array $data): LoanOffer
    {
        return DB::transaction(function () use ($data) {
            // Find or create bank based on swift code
            if (!empty($data['swift_code'])) {
                $bank = Bank::firstOrCreate(
                    ['swift_code' => $data['swift_code']],
                    ['name' => $data['bank_name'] ?? 'Unknown Bank']
                );
                $data['bank_id'] = $bank->id;
            }
            
            // Create loan offer
            $loanOffer = LoanOffer::create($data);
            
            // Create initial approval record
            $this->createApprovalRecord($loanOffer->id, 'initial', 'pending');
            
            return $loanOffer;
        });
    }
    
    /**
     * Create approval record
     */
    public function createApprovalRecord(int $loanOfferId, string $approvalType, string $status = 'pending', array $additionalData = []): LoanOfferApproval
    {
        return LoanOfferApproval::create(array_merge([
            'loan_offer_id' => $loanOfferId,
            'approval_type' => $approvalType,
            'status' => $status
        ], $additionalData));
    }
    
    /**
     * Update loan approval status
     */
    public function updateApproval(string $applicationNumber, string $approvalType, string $status, array $data = []): bool
    {
        $loanOffer = LoanOffer::where('application_number', $applicationNumber)->first();
        
        if (!$loanOffer) {
            return false;
        }
        
        // Update or create approval record
        $approval = LoanOfferApproval::updateOrCreate(
            [
                'loan_offer_id' => $loanOffer->id,
                'approval_type' => $approvalType
            ],
            array_merge([
                'status' => $status,
                'reason' => $data['reason'] ?? null,
                'fsp_reference_number' => $data['fsp_reference_number'] ?? null,
                'total_amount_to_pay' => $data['total_amount_to_pay'] ?? null,
                'other_charges' => $data['other_charges'] ?? null
            ], $data)
        );
        
        // Update loan offer status based on approval
        if ($approvalType === 'final' && $status === 'approved') {
            $loanOffer->approval = 'APPROVED';
            $loanOffer->status = 'APPROVED';
        } elseif ($status === 'rejected') {
            $loanOffer->approval = 'REJECTED';
            $loanOffer->status = 'REJECTED';
        }
        
        if (!empty($data['loan_number'])) {
            $loanOffer->loan_number = $data['loan_number'];
        }
        
        if (!empty($data['fsp_reference_number'])) {
            $loanOffer->fsp_reference_number = $data['fsp_reference_number'];
        }
        
        $loanOffer->save();
        
        Log::info("Loan approval updated", [
            'application_number' => $applicationNumber,
            'approval_type' => $approvalType,
            'status' => $status
        ]);
        
        return true;
    }
    
    /**
     * Create disbursement record
     */
    public function createDisbursement(int $loanOfferId, array $data): LoanDisbursement
    {
        $loanOffer = LoanOffer::with('bank')->find($loanOfferId);
        
        if (!$loanOffer) {
            throw new \Exception("Loan offer not found");
        }
        
        // Get SWIFT code
        $swiftCode = $data['swift_code'] ?? 
                     $loanOffer->swift_code ?? 
                     ($loanOffer->bank ? $loanOffer->bank->swift_code : null);
        
        return LoanDisbursement::create([
            'loan_offer_id' => $loanOfferId,
            'bank_id' => $loanOffer->bank_id,
            'channel_identifier' => $data['channel_identifier'] ?? null,
            'destination_code' => $data['destination_code'] ?? null,
            'swift_code' => $swiftCode,
            'status' => 'pending',
            'amount' => $data['amount'] ?? $loanOffer->requested_amount,
            'net_amount' => $data['net_amount'] ?? $loanOffer->take_home_amount,
            'account_number' => $data['account_number'] ?? $loanOffer->bank_account_number,
            'account_name' => $data['account_name'] ?? implode(' ', array_filter([
                $loanOffer->first_name,
                $loanOffer->middle_name,
                $loanOffer->last_name
            ])),
            'reference_number' => $data['reference_number'] ?? null,
            'batch_id' => $data['batch_id'] ?? null
        ]);
    }
    
    /**
     * Update disbursement status
     */
    public function updateDisbursementStatus(int $disbursementId, string $status, array $data = []): bool
    {
        $disbursement = LoanDisbursement::find($disbursementId);
        
        if (!$disbursement) {
            return false;
        }
        
        $disbursement->status = $status;
        
        if ($status === 'success') {
            $disbursement->disbursed_at = now();
            $disbursement->transaction_id = $data['transaction_id'] ?? null;
            $disbursement->reference_number = $data['reference_number'] ?? null;
            $disbursement->disbursed_by = $data['disbursed_by'] ?? auth()->id();
            
            // Update loan offer status
            $loanOffer = $disbursement->loanOffer;
            $loanOffer->status = 'DISBURSED';
            $loanOffer->save();
        } elseif ($status === 'failed') {
            $disbursement->failure_reason = $data['failure_reason'] ?? 'Unknown error';
        }
        
        $disbursement->response_data = $data['response_data'] ?? null;
        $disbursement->save();
        
        Log::info("Disbursement status updated", [
            'disbursement_id' => $disbursementId,
            'status' => $status
        ]);
        
        return true;
    }
    
    /**
     * Create top-up loan relationship
     */
    public function createTopupLoan(int $newLoanOfferId, int $originalLoanOfferId, array $balanceData): LoanOfferTopup
    {
        return LoanOfferTopup::create([
            'new_loan_offer_id' => $newLoanOfferId,
            'original_loan_offer_id' => $originalLoanOfferId,
            'original_loan_number' => $balanceData['loan_number'],
            'settlement_amount' => $balanceData['settlement_amount'] ?? 0,
            'payoff_amount' => $balanceData['total_payoff_amount'] ?? null,
            'outstanding_balance' => $balanceData['outstanding_balance'] ?? null,
            'payment_reference_number' => $balanceData['payment_reference_number'] ?? null,
            'final_payment_date' => $balanceData['final_payment_date'] ?? null,
            'last_deduction_date' => $balanceData['last_deduction_date'] ?? null,
            'last_pay_date' => $balanceData['last_pay_date'] ?? null,
            'end_date' => $balanceData['end_date'] ?? null,
            'status' => 'pending'
        ]);
    }
    
    /**
     * Find loan offer by application number
     */
    public function findByApplicationNumber(string $applicationNumber): ?LoanOffer
    {
        return LoanOffer::with(['approvals', 'disbursements', 'bank'])
            ->where('application_number', $applicationNumber)
            ->first();
    }
    
    /**
     * Find loan offer by check number
     */
    public function findByCheckNumber(string $checkNumber): ?LoanOffer
    {
        return LoanOffer::with(['approvals', 'disbursements', 'bank'])
            ->where('check_number', $checkNumber)
            ->orderBy('created_at', 'desc')
            ->first();
    }
    
    /**
     * Get loan offers pending approval
     */
    public function getPendingApprovals(string $approvalType = null)
    {
        $query = LoanOffer::with(['approvals', 'bank'])
            ->whereHas('approvals', function ($q) use ($approvalType) {
                $q->where('status', 'pending');
                if ($approvalType) {
                    $q->where('approval_type', $approvalType);
                }
            });
            
        return $query->get();
    }
    
    /**
     * Get loan offers pending disbursement
     */
    public function getPendingDisbursements()
    {
        return LoanOffer::with(['disbursements', 'bank'])
            ->whereHas('disbursements', function ($q) {
                $q->where('status', 'pending');
            })
            ->orWhere(function ($q) {
                $q->where('status', 'APPROVED')
                  ->doesntHave('disbursements');
            })
            ->get();
    }
}