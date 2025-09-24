<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            // Add new columns
            $table->string('dependent_member_type')->nullable();
            $table->string('gender')->nullable();
            $table->string('disaster_type')->nullable();
            $table->string('membership_status')->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();

        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            // Drop the new columns if rolled back
            $table->dropColumn([
                'dependent_member_type',
                'gender',
                'disaster_type',
                'membership_status',
                'startdate',
                'enddate',
            ]);

            
        });
    }
}
