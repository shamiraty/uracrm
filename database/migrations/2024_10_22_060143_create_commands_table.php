<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commands', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // The name of the command
            $table->foreignId('region_id')->constrained()->onDelete('cascade');  // Foreign key to the regions table
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');  // Foreign key to the branches table
            $table->foreignId('district_id')->constrained()->onDelete('cascade');  // Foreign key to the districts table
            $table->timestamps();  // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commands');  // Drop the commands table
    }
}
