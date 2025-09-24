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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_received');
            $table->string('force_no');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('check_number');
            $table->string('full_name');
            $table->string('district');
            $table->string('phone');
            $table->string('region');
            $table->string('sub_vote')->nullable();
            $table->string('loan_type_reason')->nullable();
            $table->decimal('amount', 10, 2);
            $table->integer('duration')->nullable();
            $table->string('received_by')->nullable();
            $table->enum('type', ['loan_application', 'refund', 'new_member', 'withdraw_savings', 'inheritance', 'deduction_add', 'termination', 'retirement', 'buy_shares']);
            $table->decimal('current_amount', 10, 2)->nullable();
            $table->date('date_of_retirement')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
