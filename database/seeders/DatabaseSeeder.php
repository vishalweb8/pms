<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(AdminCredentialsSeeder::class);
        $this->command->info('User table seeded!');

        $this->call(TechnologySeeder::class);
        $this->command->info('Technology table seeded!');

        $this->call(ProjectallocationSeeder::class);
        $this->command->info('ProjectAllocation table seeded!');

        $this->call(UserdesignationSeeder::class);
        $this->command->info('UserDesignation table seeded!');

        $this->call(ProjectpaymenttypeSeeder::class);
        $this->command->info('ProjectPaymentType table seeded!');

        $this->call(ProjectprioritySeeder::class);
        $this->command->info('ProjectPriority table seeded!');

        $this->call(ProjectstatusSeeder::class);
        $this->command->info('ProjectStatus table seeded!');

        $this->call(RoleSeeder::class);
        $this->command->info('Role table seeded!');

        // $this->call(PermissionSeeder::class);
        // $this->command->info('Permission table seeded!');

        $this->call(DepartmentSeeder::class);
        $this->command->info('Department table seeded!');

        // $this->call(CountrySeeder::class);
        // $this->command->info('Country table seeded!');

        // $this->call(StateSeeder::class);
        // $this->command->info('State table seeded!');

        // $this->call(CitySeeder::class);
        // $this->command->info('City table seeded!');

        $this->call(ClientSeeder::class);
        $this->command->info('Client table seeded!');

        $this->call(PolicySeeder::class);
        $this->command->info('Policy table seeded!');

        $this->call(EventSeeder::class);
        $this->command->info('Event table seeded!');

        $this->call(LeadStatusSeeder::class);
        $this->command->info('Lead status table seeded!');
    }
}
