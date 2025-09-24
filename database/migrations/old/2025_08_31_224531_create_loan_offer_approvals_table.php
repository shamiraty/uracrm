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
        Schema::create('loan_offer_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_offer_id')->constrained('loan_offers')->onDelete('cascade');
            $table->enum('approval_type', ['initial', 'final', 'employer', 'fsp']);
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->string('reason', 255)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('fsp_reference_number', 20)->nullable();
            $table->decimal('total_amount_to_pay', 38, 2)->nullable();
            $table->decimal('other_charges', 38, 2)->nullable();
            $table->timestamps();
            
            $table->index(['loan_offer_id', 'approval_type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_offer_approvals');
    }
};
