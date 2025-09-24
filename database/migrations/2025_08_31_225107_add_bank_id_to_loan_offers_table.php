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
        Schema::table('loan_offers', function (Blueprint $table) {
            $table->foreignId('bank_id')->nullable()->after('swift_code')->constrained('banks');
            $table->enum('loan_type', ['new', 'topup'])->default('new')->after('offer_type');
            $table->index('bank_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_offers', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
            $table->dropColumn('bank_id');
            $table->dropColumn('loan_type');
        });
    }
};
