<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersOfficialDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_official_details', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('experience')->nullable();
            $table->bigInteger('designation_id')->unsigned();
            $table->bigInteger('department_id')->unsigned();
            $table->string('skype_id')->nullable();
            $table->string('company_email_id')->nullable();
            $table->string('company_gmail_id')->nullable();
            $table->string('company_gitlab_id')->nullable();
            $table->string('company_github_id')->nullable();
            $table->string('technologies_ids')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('designation_id')->references('id')->on('user_designation')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('department')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_official_details');
    }
}
