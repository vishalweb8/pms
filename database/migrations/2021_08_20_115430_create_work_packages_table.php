<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_packages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('project_id')->unsigned();
            $table->string('task_name')->nullable();
            $table->text('description')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('estimated_hours')->nullable();
            $table->bigInteger('assign_to')->unsigned();
            $table->enum('work_type',['New', 'In Progress', 'Done', 'QA', 'Complete'])->nullable();
            $table->enum('priority',['Low','Medium','High'])->nullable();
            $table->timestamps();
        });

        Schema::table('work_packages', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assign_to')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('work_packages', function($table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_packages');
    }
}
