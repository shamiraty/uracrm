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
    Schema::create('payment_destinations', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // e.g., "CRDB Bank Limited"
        $table->string('code'); // e.g., "CRDB"
        $table->enum('type', ['BANK', 'MNO']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_destinations');
    }
};
