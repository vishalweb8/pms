<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityStateCountryForeinkeyInClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['city','state','contry']);
            $table->bigInteger('country_id')->after('address2')->unsigned()->nullable();
            $table->bigInteger('state_id')->after('address2')->unsigned()->nullable();
            $table->bigInteger('city_id')->after('address2')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete("cascade");
            $table->foreign('state_id')->references('id')->on('states')->onDelete("cascade");
            $table->foreign('country_id')->references('id')->on('countries')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_city_id_foreign');
            $table->dropForeign('clients_state_id_foreign');
            $table->dropForeign('clients_country_id_foreign');
            $table->dropColumn(['country_id','state_id','city_id']);
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('contry')->nullable();

        });
    }
}
