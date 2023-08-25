<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin','code' => 'ADMIN', 'type' => 'ADMIN'],
            ['name' => 'Software Developer','code' => 'SD', 'type' => 'EMP'],
            ['name' => 'Senior Software Developer', 'code' => 'SSD', 'type' => 'EMP'],
            ['name' => 'Team Leader','code' => 'TL', 'type' => 'TL'],
            ['name' => 'Project Manager','code' => 'PM', 'type' => 'PM'],
            ['name' => 'HR','code' => 'HR', 'type' => 'HR'],
            ['name' => 'QA','code' => 'QA', 'type' => 'EMP']
        ];

        foreach ($roles as $key => $role) {

            Role::updateOrCreate(['name' => $role['name']],$role);
        }

    }
}
