<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypeInLeaveCompsentaionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_compensation', function (Blueprint $table) {
            $table->longText('reason')->change();
        });
        Schema::table('leave_compensation_comments', function (Blueprint $table) {
            $table->longText('comments')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_compensation', function (Blueprint $table) {
            $table->string('reason')->change();
        });
        Schema::table('leave_compensation_comments', function (Blueprint $table) {
            $table->string('comments')->change();
        });
    }
}
