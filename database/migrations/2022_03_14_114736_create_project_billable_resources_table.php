<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectBillableResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_billable_resources', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('amount')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('project_billable_resources', function ($table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_billable_resources');
    }
}
