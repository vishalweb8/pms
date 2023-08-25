<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectallocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_allocation')->insert([
            ['type' => 'Full Time'],
            ['type' => 'Partially Occupied'],
            ['type' => 'Support'],
            ['type' => 'On Demand']
        ]);
    }
}
