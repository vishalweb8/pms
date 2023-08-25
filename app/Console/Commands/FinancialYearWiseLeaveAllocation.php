<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\LeaveAllocation;
use Log;
use Exception;

class FinancialYearWiseLeaveAllocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'financialyear:leaveallocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allocate Financial Year wise New Leave to All the Employees';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            // Get all the existinig employess
            $allemployees = User::pluck('id');

            // Get current year
            $allocatedYear = date("Y");

            // Use default leave values from constants
            $totalLeaves = config('constant.leaves.total_leave');
            $allocatedLeave = config('constant.leaves.allocated_leave_in_financial_year');

            // Allocate leave to every employee for new year and save data to LeaveAllocation table
            foreach ($allemployees as $employee) {
                $allocateLeave = LeaveAllocation::firstOrNew(['user_id' => $employee, 'allocated_year' => $allocatedYear]);
                $allocateLeave->user_id = $employee;
                $allocateLeave->total_leave = $allocateLeave->total_leave ?? $totalLeaves;
                $allocateLeave->allocated_year = $allocatedYear;
                $allocateLeave->allocated_leave = $allocateLeave->allocated_leave ?? $allocatedLeave;
                $allocateLeave->used_leave = '0';
                $allocateLeave->pending_leave = '0';
                $allocateLeave->remaining_leave = '0';
                $allocateLeave->save();
            }

            Log::info("Successfully allocate leave to all the employees for : ".$allocatedYear);
            return "Successfully allocate leave to all the employees.";

        } catch (Exception $e) {

            Log::error("Something went wrong in FinancialYearWiseLeaveAllocation : ".$e);
            return "Something went wrong. ".$e;

        }

    }
}
