<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
            [
                'name' => 'Downs',
                'state_id' => 1
            ],
            [
                'name' => 'Pomona',
                'state_id' => 1
            ],
            [
                'name' => 'Westerville',
                'state_id' => 2
            ],
        ]);
    }
}
