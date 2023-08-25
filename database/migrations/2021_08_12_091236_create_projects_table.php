<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('project_type')->default(1)->comment('1-billable , 0-nonbillable');
            $table->bigInteger('payment_type_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->decimal('amount', 10, 2)->nullable();
            $table->char('currency', 20)->nullable();	
            $table->bigInteger('allocation_id')->unsigned();
            $table->bigInteger('priority_id')->unsigned();
            $table->boolean('status')->default(1)->comment('1-active , 0-inactive');
            $table->bigInteger('team_lead_id')->unsigned();
            $table->bigInteger('reviewer_id')->unsigned();
            $table->string('technologies_ids')->nullable();	
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('projects', function($table) {
            $table->foreign('payment_type_id')->references('id')->on('project_payment_type')->onDelete('cascade');
        });

        Schema::table('projects', function($table) {
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

        Schema::table('projects', function($table) {
            $table->foreign('allocation_id')->references('id')->on('project_allocation')->onDelete('cascade');
        });

        Schema::table('projects', function($table) {
            $table->foreign('priority_id')->references('id')->on('project_priority')->onDelete('cascade');
        });

        Schema::table('projects', function($table) {
            $table->foreign('team_lead_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('projects', function($table) {
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
