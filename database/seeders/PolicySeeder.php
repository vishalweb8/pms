<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('policies')->insert([
            ['title' => 'Employment Contracts'],
            ['title' => 'Code of Conduct'],
            ['title' => 'Employee Wages'],
            ['title' => 'Gratuity Policy'],
            ['title' => 'Employee Provident Fund'],
            ['title' => 'Leave Policy'],
            ['title' => 'Sexual Harassment in The Workplace Policy'],
            ['title' => 'Maternity and paternity leave Policy'],
            ['title' => 'Termination of Employment Policy'],
            ['title' => 'Adaptive Work Culture Policy'],
            ['title' => 'Communications Policy'],
            ['title' => 'Nondiscrimination Policy'],
            ['title' => 'Dress Code Policy'],
            ['title' => 'Probation and Confirmation Policy'],
            ['title' => 'Work from Home Policy'],
            ['title' => 'Grievance Policy'],
            ['title' => 'Awards and Recognition Policy'],
            ['title' => 'Travel Policy'],
            ['title' => 'Performance Management and Appraisal']
        ]);
    }
}
