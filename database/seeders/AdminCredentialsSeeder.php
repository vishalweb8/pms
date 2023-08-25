<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;



class AdminCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'role_id' => '1',
            'email' => 'admin@test.com',
            'phone_number' => '123456789',
            'password' => bcrypt('Admin@123'),
        ]);
    }
}
