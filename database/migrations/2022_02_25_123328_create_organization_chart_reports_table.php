<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationChartReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_chart_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_chart_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('organization_chart_id')->references('id')->on('organization_charts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_chart_reports');
    }
}
