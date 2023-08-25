<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableColumnInProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('status')->default(1)->nullable()->comment('1-active , 0-inactive')->change();
            $table->boolean('project_type')->default(1)->nullable()->comment('1-billable , 0-nonbillable')->change();
            $table->boolean('status')->default(1)->nullable()->comment('1-active , 0-inactive')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('status')->default(1)->comment('1-active , 0-inactive')->change();
            $table->boolean('project_type')->default(1)->comment('1-billable , 0-nonbillable')->change();
            $table->boolean('status')->default(1)->comment('1-active , 0-inactive')->change();
        });
    }
}
