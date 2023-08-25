<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyWorkTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_work_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('work_id')->unsigned();
            $table->string('date')->nullable();
            $table->string('work_time')->nullable();
            $table->text('description')->nullable();
            $table->text('activity_type')->nullable();
            $table->timestamps();
        });

        Schema::table('daily_work_tasks', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('daily_work_tasks', function($table) {
            $table->foreign('work_id')->references('id')->on('work_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_work_tasks');
    }
}
