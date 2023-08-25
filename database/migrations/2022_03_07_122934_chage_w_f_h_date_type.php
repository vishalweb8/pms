<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChageWFHDateType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('wfh_requests', function (Blueprint $table) {
            $table->date('start_date')->change();
            $table->date('end_date')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('wfh_requests', function (Blueprint $table) {
            $table->varchar('start_date')->change();
            $table->date('end_date')->change();
        });
    }
}
