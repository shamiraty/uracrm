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
        Schema::table('loan_offer_topups', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['new_loan_offer_id']);
            
            // Make new_loan_offer_id nullable to allow balance inquiries before topup offer creation
            $table->unsignedBigInteger('new_loan_offer_id')->nullable()->change();
            
            // Re-add the foreign key constraint with nullable
            $table->foreign('new_loan_offer_id')
                  ->references('id')
                  ->on('loan_offers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_offer_topups', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['new_loan_offer_id']);
            
            // Make new_loan_offer_id non-nullable (Note: This will fail if there are null values)
            $table->unsignedBigInteger('new_loan_offer_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('new_loan_offer_id')
                  ->references('id')
                  ->on('loan_offers')
                  ->onDelete('cascade');
        });
    }
};
