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
        Schema::create('loan_disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_offer_id')->constrained('loan_offers')->onDelete('cascade');
            $table->foreignId('bank_id')->nullable()->constrained('banks');
            $table->enum('status', ['pending', 'success', 'failed']);
            $table->decimal('amount', 38, 2);
            $table->decimal('net_amount', 38, 2)->nullable();
            $table->string('account_number', 20);
            $table->string('account_name', 100)->nullable();
            $table->string('reference_number', 50)->nullable();
            $table->string('transaction_id', 50)->nullable();
            $table->timestamp('disbursed_at')->nullable();
            $table->foreignId('disbursed_by')->nullable()->constrained('users');
            $table->string('failure_reason', 255)->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();
            
            $table->index('loan_offer_id');
            $table->index('status');
            $table->index('disbursed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_disbursements');
    }
};
