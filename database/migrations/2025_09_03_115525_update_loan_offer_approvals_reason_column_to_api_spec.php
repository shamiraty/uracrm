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
        // Update the reason column to match API specification (varchar 150)
        Schema::table('loan_offer_approvals', function (Blueprint $table) {
            $table->string('reason', 150)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous size
        Schema::table('loan_offer_approvals', function (Blueprint $table) {
            $table->string('reason', 255)->nullable()->change();
        });
    }
};
