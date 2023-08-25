<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('request_from')->unsigned()->nullable();
            $table->bigInteger('request_to')->unsigned()->nullable();
            $table->enum('type',['full','half'])->default('full');
            $table->enum('halfday_status',['firsthalf','secondhalf'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('duration')->nullable();
            $table->string('reason')->nullable();
            $table->boolean('isadhoc_leave')->nullable();
            $table->enum('adhoc_status',['teammember','directly','notinform'])->nullable();
            $table->boolean('available_on_phone')->nullable();
            $table->boolean('available_on_city')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->enum('status',['pending','approved','rejected','cancelled'])->nullable();
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->bigInteger('rejected_by')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('request_from')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('request_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaves');
    }
}
