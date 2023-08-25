<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsInProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->integer('created_by')->index()->after('reviewer_id')->default(1);
            $table->integer('bde_id')->nullable()->after('created_by');
            $table->date('start_date')->nullable()->after('bde_id');
            $table->date('end_date')->nullable()->after('start_date');
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
            $table->dropColumn(['description','created_by','bde_id','start_date','end_date']);
        });
    }
}
