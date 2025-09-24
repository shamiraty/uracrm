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
        Schema::create('loan_offer_topups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('new_loan_offer_id')->constrained('loan_offers')->onDelete('cascade');
            $table->foreignId('original_loan_offer_id')->constrained('loan_offers')->onDelete('cascade');
            $table->string('original_loan_number', 20);
            $table->decimal('settlement_amount', 38, 2);
            $table->decimal('payoff_amount', 38, 2)->nullable();
            $table->decimal('outstanding_balance', 38, 2)->nullable();
            $table->string('payment_reference_number', 50)->nullable();
            $table->date('final_payment_date')->nullable();
            $table->datetime('last_deduction_date')->nullable();
            $table->datetime('last_pay_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'disbursed', 'cancelled']);
            $table->timestamps();
            
            $table->index('new_loan_offer_id');
            $table->index('original_loan_offer_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_offer_topups');
    }
};
