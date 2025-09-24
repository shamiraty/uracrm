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
        Schema::table('folios', function (Blueprint $table) {
            if (!Schema::hasColumn('folios', 'folioable_id')) {
                $table->unsignedBigInteger('folioable_id')->nullable();
            }
            if (!Schema::hasColumn('folios', 'folioable_type')) {
                $table->string('folioable_type')->nullable();
            }
            if (!Schema::hasColumn('folios', 'file_id') && Schema::hasTable('files')) {
                $table->unsignedBigInteger('file_id')->nullable();
                $table->foreign('file_id')->references('id')->on('files')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('folios', function (Blueprint $table) {
            $table->dropColumn(['folioable_id', 'folioable_type', 'file_id']);
        });
    }

};
