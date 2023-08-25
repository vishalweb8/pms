<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWfhCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_comments', function (Blueprint $table) {
            $table->longText('comments')->nullable()->change();
        });

        Schema::create('wfh_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wfh_id')->unsigned()->nullable();
            $table->bigInteger('request_from')->unsigned()->nullable();
            $table->bigInteger('review_by')->unsigned()->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->nullable();
            $table->longText('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('wfh_id')->references('id')->on('wfh_requests')->onDelete('cascade');
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
        Schema::dropIfExists('wfh_comments');
        Schema::table('leave_comments', function (Blueprint $table) {
            $table->string('comments')->nullable()->change();
        });
    }
}
