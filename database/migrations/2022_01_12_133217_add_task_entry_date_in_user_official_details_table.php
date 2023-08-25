<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskEntryDateInUserOfficialDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_official_details', function (Blueprint $table) {
            $table->string('task_entry_date')->nullable()->after('confirmation_date');
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
            $table->dropColumn('task_entry_date');
        });
    }
}
