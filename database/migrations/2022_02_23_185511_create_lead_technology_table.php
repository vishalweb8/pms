<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadTechnologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_technology', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lead_id')->unsigned()->nullable();
            $table->bigInteger('technology_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('lead_technology', function ($table) {
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('restrict');
            $table->foreign('technology_id')->references('id')->on('technology')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_technology');
    }
}
