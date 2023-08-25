<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::disableForeignKeyConstraints();

        Schema::create('leave_allocations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->decimal('total_leave')->nullable();
            $table->integer('allocated_year')->nullable();
            $table->integer('allocated_leave')->nullable();
            $table->decimal('used_leave')->nullable();
            $table->decimal('pending_leave')->nullable();
            $table->decimal('remaining_leave')->nullable();
            $table->decimal('exceed_leave')->nullable();
            $table->boolean('is_carry_forward')->default(0)->comment('1-yes , 0-no');
            $table->boolean('is_compensated')->default(0)->comment('1-yes , 0-no');
            $table->timestamps();
        });

        Schema::table('leave_allocations', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_allocations');
    }
}
