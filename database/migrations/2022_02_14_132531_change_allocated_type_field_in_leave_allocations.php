<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAllocatedTypeFieldInLeaveAllocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_allocations', function (Blueprint $table) {
            $table->decimal('allocated_leave')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_allocations', function (Blueprint $table) {
            $table->integer('allocated_leave')->nullable()->change();
        });
    }
}
