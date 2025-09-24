<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBenefitDescriptionToDescriptionInEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw SQL to rename the column for MySQL compatibility
        DB::statement('ALTER TABLE enquiries CHANGE COLUMN benefit_description description TEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Use raw SQL to reverse the column renaming
        DB::statement('ALTER TABLE enquiries CHANGE COLUMN description benefit_description TEXT');
    }
}
