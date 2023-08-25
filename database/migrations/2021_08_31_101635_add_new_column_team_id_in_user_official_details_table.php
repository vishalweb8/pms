<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnTeamIdInUserOfficialDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_official_details', function (Blueprint $table) {
            $table->bigInteger('team_id')->unsigned()->after('experience');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
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
            $table->dropForeign('user_official_details_team_id_foreign');
            $table->dropColumn('team_id');
        });
    }
}
