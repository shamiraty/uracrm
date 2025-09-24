<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTrackingColumnsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('initiated_by')->nullable()->after('note_path');
            $table->unsignedBigInteger('approved_by')->nullable()->after('initiated_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_by');

            $table->unsignedBigInteger('paid_by')->nullable()->after('rejected_by');

            // Foreign keys assuming the users table ID columns are bigIntegers
            $table->foreign('initiated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');

            $table->foreign('paid_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop foreign keys before dropping the columns
            $table->dropForeign(['initiated_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);

            $table->dropForeign(['paid_by']);

            $table->dropColumn(['initiated_by', 'approved_by', 'rejected_by',  'paid_by']);
        });
    }
}
