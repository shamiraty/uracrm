<?php

// database/migrations/xxxx_xx_xx_create_employees_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('department');
            $table->string('checkNumber');
            $table->string('fullName');
            $table->string('accountNumber');
            $table->string('bankName');
            $table->decimal('basicSalary', 15, 2);
            $table->decimal('allowance', 15, 2)->nullable();
            $table->decimal('arrear', 15, 2)->nullable();
            $table->decimal('grossAmount', 15, 2);
            $table->decimal('netAmount', 15, 2);

            // Columns for loan calculations
            $table->decimal('loanableAmount', 15, 2)->nullable();
            $table->decimal('totalLoanWithInterest', 15, 2)->nullable();
            $table->decimal('totalInterest', 15, 2)->nullable();
            $table->decimal('monthlyDeduction', 15, 2)->nullable();
            $table->decimal('processingFee', 15, 2)->nullable();
            $table->decimal('insurance', 15, 2)->nullable();
            $table->decimal('disbursementAmount', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
}
