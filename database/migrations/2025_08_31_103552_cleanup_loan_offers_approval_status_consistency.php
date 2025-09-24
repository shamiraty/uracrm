<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration cleans up data inconsistencies between approval and status fields
     * according to the proper workflow:
     * - PENDING: Initial state when loan offer is created
     * - APPROVED: When URAERP approves the loan
     * - REJECTED: When URAERP rejects the loan
     * - CANCELLED: When the employee rejects/cancels the loan offer
     */
    public function up(): void
    {
        // Fix records where approval is APPROVED but status is still PENDING
        // These should move to disbursement_pending status
        DB::table('loan_offers')
            ->where('approval', 'APPROVED')
            ->where('status', 'PENDING')
            ->update(['status' => 'disbursement_pending']);
            
        // Fix records where approval is REJECTED but status is still PENDING
        // Status should match approval
        DB::table('loan_offers')
            ->where('approval', 'REJECTED')
            ->where('status', 'PENDING')
            ->update(['status' => 'REJECTED']);
            
        // Fix records where approval is CANCELLED but status is still PENDING
        // Status should reflect cancellation
        DB::table('loan_offers')
            ->where('approval', 'CANCELLED')
            ->where('status', 'PENDING')
            ->update(['status' => 'CANCELLED']);
            
        // Handle special cases
        DB::table('loan_offers')
            ->where('status', 'NONE')
            ->update(['status' => 'CANCELLED']);
            
        // For any future records without approval/status, set them to PENDING
        DB::table('loan_offers')
            ->whereNull('approval')
            ->orWhereNull('status')
            ->update([
                'approval' => 'PENDING',
                'status' => 'PENDING'
            ]);
            
        // Log the cleanup results
        echo "Data cleanup completed:\n";
        echo "- Approved loans with pending status -> disbursement_pending\n";
        echo "- Rejected loans with pending status -> REJECTED\n";
        echo "- Cancelled loans with pending status -> CANCELLED\n";
        echo "- Records with 'NONE' status -> CANCELLED\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration performs data cleanup - reversal is not recommended
        // as it would reintroduce data inconsistencies
    }
};
