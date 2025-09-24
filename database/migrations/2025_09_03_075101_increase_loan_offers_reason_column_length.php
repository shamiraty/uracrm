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
            // Change reason column from varchar(150) to text to allow longer messages
            $table->text('reason')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_offers', function (Blueprint $table) {
            // Revert back to varchar(150)
            $table->string('reason', 150)->nullable()->change();
        });
    }
};
