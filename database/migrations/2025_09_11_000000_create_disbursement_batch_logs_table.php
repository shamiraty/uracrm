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
        Schema::create('disbursement_batch_logs', function (Blueprint $table) {
            $table->id();
            $table->date('batch_date');
            $table->enum('time_slot', ['morning', 'afternoon', 'evening']);
            $table->integer('total_processed')->default(0);
            $table->integer('successful')->default(0);
            $table->integer('failed')->default(0);
            $table->decimal('total_amount', 38, 2)->default(0);
            $table->json('batch_ids')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['batch_date', 'time_slot']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursement_batch_logs');
    }
};