<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdhocColumnInWfhRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wfh_requests', function (Blueprint $table) {
            $table->boolean('is_adhoc')->default(0)->after('duration');
            $table->enum('adhoc_status',['team_member','directly','not_inform'])->nullable()->after('is_adhoc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wfh_requests', function (Blueprint $table) {
            $table->dropColumn(['is_adhoc','adhoc_status']);
        });
    }
}
