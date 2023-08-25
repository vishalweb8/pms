<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnPaymentCurrencyInProjectBillableResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_billable_resources', function (Blueprint $table) {
            $table->bigInteger('payment_type_id')->unsigned()->nullable()->after('user_id');
            $table->bigInteger('currency_id')->unsigned()->nullable()->after('payment_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_billable_resources', function (Blueprint $table) {
            $table->dropColumn(['payment_type_id','currency_id']);
        });
    }
}
