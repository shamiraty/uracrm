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
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->string('otp')->nullable();
            $table->dateTime('otp_expires_at')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn('otp');
            $table->dropColumn('otp_expires_at');
        });
    }
};
