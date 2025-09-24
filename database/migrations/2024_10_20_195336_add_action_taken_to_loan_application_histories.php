<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionTakenToLoanApplicationHistories extends Migration
{
    public function up()
    {
        Schema::table('loan_application_histories', function (Blueprint $table) {
            $table->string('action_taken')->after('disbursement_amount')->nullable(); // Adds a new nullable string column
        });
    }

    public function down()
    {
        Schema::table('loan_application_histories', function (Blueprint $table) {
            $table->dropColumn('action_taken');
        });
    }
}
