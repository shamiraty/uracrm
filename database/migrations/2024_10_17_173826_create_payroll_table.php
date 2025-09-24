<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollTable extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('check_number')->unique();
            $table->string('full_name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('allowance', 10, 2);
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}
