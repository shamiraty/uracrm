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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_series_id');
            $table->unsignedBigInteger('keyword1_id')->nullable();
            $table->unsignedBigInteger('keyword2_id')->nullable();
            $table->integer('running_number')->unique();
            $table->string('file_subject');
            $table->string('reference_number')->unique();
            $table->unsignedBigInteger('department_id');
            $table->timestamps();

            $table->foreign('file_series_id')->references('id')->on('file_series');
            $table->foreign('keyword1_id')->references('id')->on('keywords');
            $table->foreign('keyword2_id')->references('id')->on('keywords');
            $table->foreign('department_id')->references('id')->on('departments');  // Ensure you have a departments table
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
