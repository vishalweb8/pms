<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTimeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_time_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->date('log_date')->nullable();
            $table->string('log_in_time')->nullable();
            $table->string('log_out_time')->nullable();
            $table->string('duration')->nullable();
            $table->string('total_duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_time_logs');
    }
}
