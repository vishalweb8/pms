<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_details', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('total_experience')->nullable();
            $table->string('current_ctc')->nullable();
            $table->string('expected_ctc')->nullable();
            $table->string('notice_period')->nullable();
            $table->string('current_organization')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('reference_id');
            $table->string('resume')->nullable();
            $table->string('source_by')->nullable();
            $table->string('remark')->nullable();
            $table->boolean('status')->default(1)->comment('1-active , 0-inactive');

            $table->foreign('reference_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('interview_details');
    }
}
