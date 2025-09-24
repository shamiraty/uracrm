<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction_details', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key (optional, but recommended)
            $table->string('nationalId', 50);
            $table->decimal('checkNumber', 18, 0);
            $table->string('voteCode', 50);
            $table->string('voteName', 50);
            $table->string('deptCode', 50);
            $table->string('deptName', 50);
            $table->string('firstName', 40);
            $table->string('middleName', 70);
            $table->string('lastName', 40);
            $table->string('deductionCode', 50);
            $table->string('deductionDesc', 50);
            $table->decimal('deductionAmount', 38, 2);
            $table->decimal('balanceAmount', 38, 2);
            $table->decimal('monthlySalary', 38, 2);
            $table->string('fundingSource', 3);
            $table->dateTime('checkDate');
            $table->timestamps(); // Optional: creates created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deduction_details');
    }
}
