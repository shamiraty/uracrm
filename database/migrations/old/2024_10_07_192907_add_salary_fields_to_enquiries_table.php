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
        Schema::table('enquiries', function (Blueprint $table) {
            $table->decimal('basic_salary', 8, 2)->nullable();
            $table->decimal('allowances', 8, 2)->nullable();
            $table->decimal('take_home', 8, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['basic_salary', 'allowances', 'take_home']);
        });
    }

};
