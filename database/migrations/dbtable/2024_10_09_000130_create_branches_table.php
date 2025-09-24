<?php

// database/migrations/yyyy_mm_dd_hhmmss_create_branches_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('region_id');
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        // Pivot table for branch and department many-to-many relationship
        Schema::create('branch_department', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('department_id');

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->primary(['branch_id', 'department_id']);
        });

        // Pivot table for branch and user many-to-many relationship
        Schema::create('branch_user', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['branch_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('branch_department');
        Schema::dropIfExists('branch_user');
        Schema::dropIfExists('branches');
    }
}
