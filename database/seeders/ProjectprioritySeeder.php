<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectprioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_priority')->insert([
            ['name' => 'High'],
            ['name' => 'Medium'],
            ['name' => 'Low']
        ]);
    }
}
