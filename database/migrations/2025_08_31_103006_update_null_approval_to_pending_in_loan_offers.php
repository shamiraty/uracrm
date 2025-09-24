<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing null approval values to 'PENDING'
        DB::table('loan_offers')
            ->whereNull('approval')
            ->update(['approval' => 'PENDING']);
        
        // Also update any null status values to 'PENDING' for consistency
        DB::table('loan_offers')
            ->whereNull('status')
            ->update(['status' => 'PENDING']);
            
        // Log the update for tracking
        $countApproval = DB::table('loan_offers')->where('approval', 'PENDING')->count();
        $countStatus = DB::table('loan_offers')->where('status', 'PENDING')->count();
        
        echo "Updated approval field: {$countApproval} records now have PENDING approval status.\n";
        echo "Updated status field: {$countStatus} records now have PENDING status.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: We don't revert the data changes as that could lose information
        // The original NULL values cannot be distinguished from intentionally set PENDING values
        // If needed, you can manually set specific PENDING values back to NULL
    }
};
