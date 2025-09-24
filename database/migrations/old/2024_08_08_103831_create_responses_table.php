<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('interest', 5, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->text('description')->nullable();
            $table->decimal('from_amount', 15, 2)->nullable();
            $table->decimal('to_amount', 15, 2)->nullable();
            $table->integer('duration')->nullable();
            $table->date('date_of_retirement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responses');
    }
}
