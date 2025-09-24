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
        Schema::table('loan_offers', function (Blueprint $table) {
            $table->decimal('take_home_amount', 15, 2)->nullable()->after('net_loan_amount')
                  ->comment('Actual disbursement amount after deducting all charges (insurance, processing fees, etc.)');
        });
        
        // Copy existing net_loan_amount values to take_home_amount for existing records
        DB::statement('UPDATE loan_offers SET take_home_amount = net_loan_amount WHERE take_home_amount IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_offers', function (Blueprint $table) {
            $table->dropColumn('take_home_amount');
        });
    }
};
