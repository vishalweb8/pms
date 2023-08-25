<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            [
                'first_name' => 'Brian',
                'last_name' => 'Cady',
                'email' => 'ampbhsv9oys@temporary-mail.net',
                'phone_number' => '309-378-8092',
                'address1' => '2659 Coburn',
                'address2' => 'Hollow Road',
                'city_id' => null,
                'state_id' => null,
                'country_id' => null,
                'zipcode' => '61736'
            ],[
                'first_name' => 'John',
                'last_name' => 'Perry',
                'email' => 'aor3bxgltkr@temporary-mail.net',
                'phone_number' => '845-364-8643',
                'address1' => '2255 Benedum',
                'address2' => 'Drive',
                'city_id' => null,
                'state_id' => null,
                'country_id' => null,
                'zipcode' => '10970'
            ],[
                'first_name' => 'Arthur',
                'last_name' => 'Tigner',
                'email' => 'lwc3wtbreqe@temporary-mail.net',
                'phone_number' => '614-247-3087',
                'address1' => '3286 Bates',
                'address2' => 'Brothers Road',
                'city_id' => null,
                'state_id' => null,
                'country_id' => null,
                'zipcode' => '43081'
            ]
            // ,[
            //     'first_name' => 'John',
            //     'last_name' => 'Rios',
            //     'email' => 'omnlu5g005a@temporary-mail.net',
            //     'phone_number' => '785-202-5247',
            //     'address1' => '1104 Hummingbird',
            //     'address2' => 'Way',
            //     'city' => 'Hays',
            //     'state' => 'Kansas',
            //     'contry' => 'USA',
            //     'zipcode' => '67601'
            // ],[
            //     'first_name' => 'Barbara',
            //     'last_name' => 'Murray',
            //     'email' => '2c5mor5ykmk@temporary-mail.net',
            //     'phone_number' => '573-248-5973',
            //     'address1' => '2484 Penn',
            //     'address2' => 'Street',
            //     'city' => 'Hannibal',
            //     'state' => 'Missouri',
            //     'contry' => 'USA',
            //     'zipcode' => '63401'
            // ]
        ]);
    }
}
