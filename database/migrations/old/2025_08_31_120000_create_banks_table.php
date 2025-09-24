<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('swift_code', 50)->unique();
            $table->string('short_name', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('swift_code');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('banks');
    }
};