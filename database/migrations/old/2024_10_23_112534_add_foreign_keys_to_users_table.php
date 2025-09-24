<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Adding the foreign key fields
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('command_id')->nullable();

            // Adding foreign key constraints
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('command_id')->references('id')->on('commands')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Dropping foreign key constraints
            $table->dropForeign(['region_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['command_id']);

            // Dropping the columns
            $table->dropColumn(['region_id', 'department_id', 'district_id', 'command_id']);
        });
    }
}
