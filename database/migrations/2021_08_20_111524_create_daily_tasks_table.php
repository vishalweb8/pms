<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('current_date')->nullable();
            $table->string('sod_description')->nullable();
            $table->string('eod_description')->nullable();
            $table->enum('emp_status',['free','partially-occupied','occupied','on-leave'])->nullable();
            $table->enum('project_status',['non-billable','partially-billable','billable'])->nullable();
            $table->boolean('verified_by_TL')->default(0)->comment('1-Yes , 0-No');
            $table->boolean('verified_by_Admin')->default(0)->comment('1-Yes , 0-No');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('daily_tasks', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_tasks');
    }
}
