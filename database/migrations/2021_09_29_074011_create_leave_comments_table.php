<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('leave_id')->unsigned()->nullable();
            $table->bigInteger('request_from')->unsigned()->nullable();
            $table->bigInteger('review_by')->unsigned()->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('leave_id')->references('id')->on('leaves')->onDelete('cascade');
            $table->foreign('request_from')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('review_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_comments');
    }
}
