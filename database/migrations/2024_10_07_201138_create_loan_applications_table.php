<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')->constrained()->onDelete('cascade');
            $table->decimal('loan_amount', 20, 2);
            $table->integer('loan_duration'); // in months
            $table->decimal('interest_rate', 5, 2); // as a percentage
            $table->decimal('monthly_deduction', 20, 2);
            $table->decimal('total_loan_with_interest', 20, 2);
            $table->decimal('total_interest', 20, 2);
            $table->decimal('processing_fee', 20, 2);
            $table->decimal('insurance', 20, 2);
            $table->decimal('disbursement_amount', 20, 2);
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_applications');
    }
}
