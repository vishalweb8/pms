<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableColumnsInProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->bigInteger('payment_type_id')->unsigned()->nullable()->change();
            $table->bigInteger('client_id')->unsigned()->nullable()->change();
            $table->bigInteger('allocation_id')->unsigned()->nullable()->change();
            $table->bigInteger('priority_id')->unsigned()->nullable()->change();
            $table->bigInteger('team_lead_id')->unsigned()->nullable()->change();
            $table->bigInteger('reviewer_id')->unsigned()->nullable()->change();
            $table->bigInteger('reviewer_id')->unsigned()->nullable()->change();
            $table->string('members_ids')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('members_ids');
        });
    }
}
