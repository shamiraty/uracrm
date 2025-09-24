<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_disbursements', function (Blueprint $table) {
            // Add channel and destination tracking
            $table->string('channel_identifier', 20)->nullable()->after('bank_id')
                ->comment('INTERNAL, DOMESTIC, TISS, MNO');
            $table->string('destination_code', 20)->nullable()->after('channel_identifier')
                ->comment('Bank code or MNO code from SWIFT mapping');
            $table->string('swift_code', 10)->nullable()->after('destination_code')
                ->comment('SWIFT code used for the transaction');
            $table->string('batch_id', 50)->nullable()->after('reference_number')
                ->comment('NMB batch ID for tracking');
            
            // Add indexes for better query performance
            $table->index('channel_identifier');
            $table->index('destination_code');
            $table->index('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_disbursements', function (Blueprint $table) {
            $table->dropColumn([
                'channel_identifier',
                'destination_code',
                'swift_code',
                'batch_id'
            ]);
        });
    }
};
