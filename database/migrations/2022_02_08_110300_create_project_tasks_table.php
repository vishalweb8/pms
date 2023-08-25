<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('project_id')->unsigned()->nullable();
            $table->string('assignee_ids')->nullable();
            $table->bigInteger('priority_id')->unsigned()->nullable();
            $table->enum('status', ['todo', 'inprogress', 'completed'])->default('todo');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('project_tasks', function ($table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('priority_id')->references('id')->on('project_priority')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_tasks');
    }
}
