<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFieldsNullableInUserOfficialDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_official_details', function (Blueprint $table) {
            $table->bigInteger('designation_id')->unsigned()->nullable()->change();
            $table->bigInteger('department_id')->unsigned()->nullable()->change();
            $table->bigInteger('team_id')->unsigned()->nullable()->change();
            $table->bigInteger('team_leader_id')->unsigned()->after('user_id')->nullable()->change();
            $table->string('emp_code')->after('team_leader_id')->nullable()->change();
            $table->string('joining_date')->after('emp_code')->nullable()->change();
            $table->string('confirmation_date')->after('joining_date')->nullable()->change();
            $table->string('offered_ctc')->after('confirmation_date')->nullable()->change();
            $table->string('current_ctc')->after('offered_ctc')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_official_details', function (Blueprint $table) {
            $table->bigInteger('designation_id')->unsigned()->change();
            $table->bigInteger('department_id')->unsigned()->change();
            $table->bigInteger('team_id')->unsigned()->after('experience')->change();
            $table->bigInteger('team_leader_id')->unsigned()->after('user_id')->change();
            $table->string('emp_code')->after('team_leader_id')->change();
            $table->string('joining_date')->after('emp_code')->change();
            $table->string('confirmation_date')->after('joining_date')->change();
            $table->string('offered_ctc')->after('confirmation_date')->change();
            $table->string('current_ctc')->after('offered_ctc')->change();
        });
    }
}
