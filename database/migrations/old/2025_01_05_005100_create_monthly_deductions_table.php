<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('monthly_deductions', function (Blueprint $table) {
            $table->id();
            $table->string('loan_number', 20);
            $table->unsignedBigInteger('check_number');
            $table->string('first_name', 30);
            $table->string('middle_name', 30)->nullable();
            $table->string('last_name', 30);
            $table->string('national_id', 22);
            $table->string('vote_code', 10);
            $table->string('vote_name', 255);
            $table->string('department_code', 10);
            $table->string('department_name', 255);
            $table->string('deduction_code', 10);
            $table->string('deduction_description', 255);
            $table->decimal('balance_amount', 38, 2);
            $table->decimal('deduction_amount', 38, 2);
            $table->boolean('has_stop_pay')->default(false);
            $table->string('stop_pay_reason', 255)->nullable();
            $table->date('check_date');
            $table->timestamps();

            // Optional: Add indexes for frequently queried fields
            $table->index(['loan_number', 'check_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('monthly_deductions');
    }
}
