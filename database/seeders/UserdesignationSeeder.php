<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserdesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_designation')->insert([
            ['name' => 'Senior Team Leader'],
            ['name' => 'Team Leader'],
            ['name' => 'Associate Developer'],
            ['name' => 'Associate Team Leader'],
            ['name' => 'Associate Project Manager'],
            ['name' => 'Software Developer'],
            ['name' => 'Senior Software Developer'],
            ['name' => 'Project Manager'],
            ['name' => 'Senior Project Manager'],
            ['name' => 'SCRUM Master'],
            ['name' => 'HR']
        ]);
    }
}
