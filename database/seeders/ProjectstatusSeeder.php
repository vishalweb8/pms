<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_status')->insert([
            ['name' => 'On Track'],
            ['name' => 'On Hold'],
            ['name' => 'Done'],
            ['name' => 'Ready'],
            ['name' => 'Off Track'],
            ['name' => 'Blocked'],
            ['name' => 'Critical'],
            ['name' => 'Delayed']
        ]);
    }
}
