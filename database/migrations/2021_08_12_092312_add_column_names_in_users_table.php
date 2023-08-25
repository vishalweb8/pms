<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNamesInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('gender')->nullable()->after('last_name');
            $table->bigInteger('role_id')->unsigned()->nullable()->after('gender');
            $table->string('user_name')->nullable()->after('role_id');
            $table->string('profile_image')->nullable()->after('user_name');
            $table->string('phone_number')->nullable()->after('profile_image');
            $table->string('emergency_number')->nullable()->after('phone_number');
            $table->string('address1')->nullable()->after('emergency_number');
            $table->string('address2')->nullable()->after('address1');
            $table->string('city')->nullable()->after('address2');
            $table->string('state')->nullable()->after('city');
            $table->string('contry')->nullable()->after('state');
            $table->integer('zipcode')->nullable()->after('contry');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
            $table->dropColumn(['last_name','gender','role_id','user_name','profile_image','phone_number','emergency_number','address1','address2','city','state','contry','zipcode']);
        });
    }
}
