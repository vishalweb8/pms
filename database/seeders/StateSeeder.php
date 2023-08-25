<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->insert([
            [
                'name' => 'Illinois',
                'country_id' => 1
            ],
            [
                'name' => 'New York',
                'country_id' => 1
            ],
            [
                'name' => 'Ohio',
                'country_id' => 1
            ]

        ]);
    }
}
