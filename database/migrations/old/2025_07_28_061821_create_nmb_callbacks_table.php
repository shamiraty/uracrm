<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('nmb_callbacks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('loan_offer_id')->constrained('loan_offers')->onDelete('cascade');
        $table->string('batch_id');
        $table->string('final_status'); // e.g., "success", "failed"
        $table->text('status_description')->nullable(); // e.g., "INVALID ACC NUMBER OR ACC NAME"
        $table->string('payment_reference')->nullable();
        $table->string('file_ref_id')->nullable();
        $table->json('raw_payload'); // To store the full JSON response for auditing
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nmb_callbacks');
    }
};
