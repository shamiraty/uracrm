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
        Schema::table('loan_offers', function (Blueprint $table) {
            $table->decimal('net_loan_amount', 15, 2)->nullable()->after('requested_amount')->comment('Take-home amount after fees (amount to be disbursed)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_offers', function (Blueprint $table) {
            //
        });
    }
};
