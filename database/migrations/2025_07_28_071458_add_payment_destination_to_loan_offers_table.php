<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('loan_offers', function (Blueprint $table) {
        $table->foreignId('payment_destination_id')->nullable()->after('bank_account_number')->constrained('payment_destinations');
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
