<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInWfhRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE wfh_requests MODIFY wfh_type ENUM('full', 'half') DEFAULT 'full'");
        Schema::table('wfh_requests', function (Blueprint $table) {
            $table->string('request_to')->change();
            $table->enum('halfday_status', ['firsthalf', 'secondhalf'])->nullable()->after('wfh_type');
            $table->date('return_date')->nullable()->after('halfday_status');
            $table->string('duration')->nullable()->after('return_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->nullable()->after('duration');
            $table->bigInteger('approved_by')->unsigned()->nullable()->after('status');
            $table->bigInteger('rejected_by')->unsigned()->nullable()->after('approved_by');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE wfh_requests MODIFY wfh_type text");
        Schema::table('wfh_requests', function (Blueprint $table) {
            $table->dropForeign('wfh_requests_approved_by_foreign');
            $table->dropForeign('wfh_requests_rejected_by_foreign');
        });

        Schema::table('wfh_requests', function (Blueprint $table) {

            $table->dropColumn(['halfday_status', 'return_date', 'duration', 'status', 'approved_by', 'rejected_by']);
        });


    }
}
