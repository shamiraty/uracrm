<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\LoanOffer;
use App\Models\LoanOfferApproval;

class BackfillLoanOfferApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all loan offers that have been approved or rejected
        $loanOffers = LoanOffer::whereIn('approval', ['APPROVED', 'REJECTED', 'CANCELLED'])
            ->get();
        
        foreach ($loanOffers as $loanOffer) {
            // Check if approval record already exists
            $existingApproval = LoanOfferApproval::where('loan_offer_id', $loanOffer->id)
                ->where('approval_type', 'initial')
                ->first();
            
            if (!$existingApproval) {
                // Create initial approval record (always created when loan is created)
                LoanOfferApproval::create([
                    'loan_offer_id' => $loanOffer->id,
                    'approval_type' => 'initial',
                    'status' => 'pending',
                    'created_at' => $loanOffer->created_at,
                    'updated_at' => $loanOffer->created_at,
                ]);
            }
            
            // Check if final approval record exists
            $finalApproval = LoanOfferApproval::where('loan_offer_id', $loanOffer->id)
                ->where('approval_type', 'final')
                ->first();
            
            if (!$finalApproval && $loanOffer->approval !== 'PENDING') {
                // Determine the normalized status
                $normalizedStatus = 'pending';
                $approvedAt = null;
                $rejectedAt = null;
                
                if ($loanOffer->approval === 'APPROVED') {
                    $normalizedStatus = 'approved';
                    $approvedAt = $loanOffer->updated_at;
                } elseif (in_array($loanOffer->approval, ['REJECTED', 'CANCELLED'])) {
                    $normalizedStatus = 'rejected';
                    $rejectedAt = $loanOffer->updated_at;
                }
                
                // Create final approval record
                LoanOfferApproval::create([
                    'loan_offer_id' => $loanOffer->id,
                    'approval_type' => 'final',
                    'status' => $normalizedStatus,
                    'approved_by' => null, // We don't know who approved historically
                    'rejected_by' => null, // We don't know who rejected historically
                    'approved_at' => $approvedAt,
                    'rejected_at' => $rejectedAt,
                    'reason' => $loanOffer->reason,
                    'comments' => 'Backfilled from existing data',
                    'created_at' => $loanOffer->updated_at,
                    'updated_at' => $loanOffer->updated_at,
                ]);
            }
        }
        
        // Also create initial approval records for pending loans
        $pendingLoans = LoanOffer::where(function($query) {
            $query->where('approval', 'PENDING')
                  ->orWhereNull('approval');
        })->get();
        
        foreach ($pendingLoans as $loanOffer) {
            $existingApproval = LoanOfferApproval::where('loan_offer_id', $loanOffer->id)
                ->where('approval_type', 'initial')
                ->first();
            
            if (!$existingApproval) {
                LoanOfferApproval::create([
                    'loan_offer_id' => $loanOffer->id,
                    'approval_type' => 'initial',
                    'status' => 'pending',
                    'created_at' => $loanOffer->created_at,
                    'updated_at' => $loanOffer->created_at,
                ]);
            }
        }
        
        // Log the results
        $totalBackfilled = LoanOfferApproval::count();
        \Log::info("Backfilled {$totalBackfilled} loan approval records");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We don't want to delete approval records on rollback
        // as they might have been updated with real data
        \Log::warning('Backfill migration rolled back - approval records retained');
    }
}