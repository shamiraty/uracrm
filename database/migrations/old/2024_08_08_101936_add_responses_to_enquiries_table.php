<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponsesToEnquiriesTable extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->decimal('loan_amount', 10, 2)->nullable();
            $table->decimal('loan_interest', 10, 2)->nullable();
            $table->decimal('shares_received', 10, 2)->nullable();
            $table->decimal('shares_accepted', 10, 2)->nullable();
            $table->decimal('savings_withdrawn', 10, 2)->nullable();
            $table->decimal('savings_accepted', 10, 2)->nullable();
            $table->decimal('retirement_amount', 10, 2)->nullable();
            $table->decimal('disaster_amount', 10, 2)->nullable();
            $table->decimal('unjoin_amount', 10, 2)->nullable();
            $table->decimal('deduction_add_amount', 10, 2)->nullable();
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->unsignedBigInteger('response_by')->nullable();
            $table->foreign('response_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn([
                'loan_amount', 'loan_interest', 'shares_received', 'shares_accepted',
                'savings_withdrawn', 'savings_accepted', 'retirement_amount',
                'disaster_amount', 'unjoin_amount', 'deduction_add_amount',
                'deposit_amount', 'response_by'
            ]);
        });
    }
}

