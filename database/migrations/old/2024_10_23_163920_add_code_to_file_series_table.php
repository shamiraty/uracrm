<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeToFileSeriesTable extends Migration
{
    public function up()
    {
        Schema::table('file_series', function (Blueprint $table) {
            $table->string('code')->unique()->after('name');
        });
    }

    public function down()
    {
        Schema::table('file_series', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
