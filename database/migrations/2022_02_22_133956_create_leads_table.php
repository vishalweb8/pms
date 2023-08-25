<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('skype_id')->nullable();
            $table->string('website')->nullable();
            $table->bigInteger('lead_industry_id')->unsigned()->nullable();
            $table->bigInteger('lead_source_id')->unsigned()->nullable();
            $table->bigInteger('lead_status_id')->unsigned()->nullable();
            $table->bigInteger('lead_owner_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('leads', function ($table) {
            $table->foreign('lead_owner_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('lead_status_id')->references('id')->on('lead_status')->onDelete('restrict');
            $table->foreign('lead_source_id')->references('id')->on('lead_source')->onDelete('restrict');
            $table->foreign('lead_industry_id')->references('id')->on('industry')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
