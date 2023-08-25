<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInUsersOfficialDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_official_details', function (Blueprint $table) {
            $table->bigInteger('team_leader_id')->unsigned()->after('user_id');
            $table->string('emp_code')->after('team_leader_id');
            $table->string('joining_date')->after('emp_code');
            $table->string('confirmation_date')->after('joining_date');
            $table->string('offered_ctc')->after('confirmation_date');
            $table->string('current_ctc')->after('offered_ctc');
            $table->string('reporting_ids')->nullable()->after('technologies_ids');
        });

        Schema::table('user_official_details', function ($table) {
            $table->foreign('team_leader_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropColumn(['team_leader_id', 'emp_code', 'joining_date', 'confirmation_date', 'offered_ctc', 'current_ctc','reporting_ids']);
        });
    }
}
