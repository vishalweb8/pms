<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskAssigneesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_assignees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id')->unsigned()->nullable();
            $table->bigInteger('assignee_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('task_assignees', function ($table) {
            $table->foreign('task_id')->references('id')->on('project_tasks')->onDelete('cascade');
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_assignees');
    }
}
