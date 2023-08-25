<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignKeyRequestToFromLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (Schema::hasColumn('leaves', 'request_to')) {
                $table->dropForeign(['request_to']);
                $table->dropColumn('request_to');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('leaves', 'request_to')) {
                $table->bigInteger('request_to')->unsigned()->nullable()->after('request_from');
                $table->foreign('request_to')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }
}
