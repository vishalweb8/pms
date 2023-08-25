<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectIdColumnInDailyTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_tasks', function (Blueprint $table) {
            $table->bigInteger('project_id')->unsigned()->nullable()->after('user_id');
            $table->text('sod_description')->change();
            $table->text('eod_description')->change();
        });

        Schema::table('daily_tasks', function($table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_tasks', function (Blueprint $table) {
            $table->dropColumn('project_id');
            $table->string('sod_description')->change();
            $table->string('eod_description')->change();
        });
    }
}
