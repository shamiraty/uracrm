<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable(); // You might not need this if you're using Spatie's role system
            $table->string('designation')->nullable();
            $table->string('rank')->nullable();
            $table->string('status')->default('active'); // Could also be a boolean
            $table->string('phone_number')->nullable();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null'); // Check if you use Spatie's roles
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['branch_id', 'role_id', 'designation', 'rank', 'status', 'phone_number']);
        });
    }
}
