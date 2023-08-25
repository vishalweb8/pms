<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LeadStatus;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // DB::table('lead_status')->insert([
        //     [
        //         'name' => 'Open',
        //         'status' => 1
        //     ],[
        //         'name' => 'Converted',
        //         'status' => 1
        //     ],[
        //         'name' => 'Rejected',
        //         'status' => 1
        //     ]
        // ]);
        $leadStatuses = [
            [
                'name' => 'Open',
                'status' => 1
            ],[
                'name' => 'Converted',
                'status' => 1
            ],[
                'name' => 'Rejected',
                'status' => 1
            ]
        ]; 
        foreach ($leadStatuses as $key => $leadStatus) {
            $leadStatusData = LeadStatus::updateOrCreate([
                'name' => $leadStatus['name'],
            ],[
                'status' => $leadStatus['status']
            ]);
        }
    }
}
