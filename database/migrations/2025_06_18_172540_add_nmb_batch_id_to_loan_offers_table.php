<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNmbBatchIdToLoanOffersTable extends Migration
{
    public function up() {
        Schema::table('loan_offers', function (Blueprint $table) {
            $table->string('nmb_batch_id')->nullable()->after('status');
        });
    }
    public function down() {
        Schema::table('loan_offers', function (Blueprint $table) {
            $table->dropColumn('nmb_batch_id');
        });
    }
}