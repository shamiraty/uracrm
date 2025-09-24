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
        Schema::create('loan_application_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained();
            $table->decimal('loan_amount', 10, 2);
            $table->integer('loan_duration');
            $table->decimal('monthly_deduction', 10, 2);
            $table->decimal('total_loan_with_interest', 10, 2);
            $table->decimal('total_interest', 10, 2);
            $table->decimal('processing_fee', 10, 2);
            $table->decimal('insurance', 10, 2);
            $table->decimal('disbursement_amount', 10, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_application_histories');
    }
};
