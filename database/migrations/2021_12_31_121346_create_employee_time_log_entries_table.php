<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTimeLogEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_time_log_entries', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->date('log_date')->nullable();
            $table->time('log_time')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-Active, 2-Inactive, 3-Deleted');
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
        Schema::dropIfExists('employee_time_log_entries');
    }
}
