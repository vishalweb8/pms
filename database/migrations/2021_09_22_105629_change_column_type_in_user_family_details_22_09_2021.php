<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypeInUserFamilyDetails22092021 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_family_details', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
        Schema::table('user_experience_details', function (Blueprint $table) {
            $table->string('previous_company')->nullable()->change();
            $table->bigInteger('designation_id')->unsigned()->nullable()->change();

        });
        Schema::table('user_official_details', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->change();
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
        Schema::table('user_family_details', function (Blueprint $table) {
            //
        });

        Schema::table('user_official_details', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
}
