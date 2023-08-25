<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('birth_date')->nullable()->after('user_name');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])->after('birth_date');
            $table->date('wedding_anniversary')->nullable()->after('blood_group');
            $table->enum('marital_status', ['married', 'unmarried', 'divorced', 'widowed'])->after('wedding_anniversary');
            $table->string('temp_address1')->nullable()->after('emergency_number');
            $table->string('temp_address2')->nullable()->after('temp_address1');
            $table->string('temp_city')->nullable()->after('temp_address2');
            $table->string('temp_state')->nullable()->after('temp_city');
            $table->string('temp_contry')->nullable()->after('temp_state');
            $table->integer('temp_zipcode')->nullable()->after('temp_contry');
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
            $table->dropColumn(['birth_date', 'blood_group', 'wedding_anniversary', 'marital_status', 'temp_address1', 'temp_address2', 'temp_city', 'temp_state', 'temp_contry', 'temp_zipcode']);
        });
    }
}
