<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\Works\DefaulterController;
use App\Models\DailyTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AutomationController extends Controller
{
    /**
     * Make/Add eod_description base on the date wise work log entry into database
     *
     * @param Request $request
     * @return void
     * @date 16-03-2022 
     */
    public function makeEodEntry(Request $request)
    {
        $taskDashboardController = new TaskDashboardController();

        // If particular date passed with the request
        if($request->filled('date')) {
            $missing_eod_entries = DailyTask::where('current_date', $request->date);
        }else {
            $missing_eod_entries = DailyTask::whereNull('eod_description'); // Get all the daily_task entries which eod_description is null
        }

        foreach($missing_eod_entries->cursor() as $entry) {
            $request['date'] = Carbon::createFromFormat('d-m-Y', $entry->current_date)->format('Y-m-d');
            $request['user_id'] = $entry->user_id;
            $modalData = $taskDashboardController->getWorkLogDetails($request); // Use the TaskController's method for maintain consistency
            // If there is time entry for the date available
            if($modalData->isNotEmpty()) {
                $entry->eod_description = view("task-dashboard.details-modal-content", compact('modalData'))->render();
                $entry->save();
            }
        }
    }

    /**
     * Update the user's daily_task entry for the eod_description for the particular date
     *
     * @param Request $request
     * @return void
     * @date 16-03-2022 
     */
    public function updateEodEntry(Request $request)
    {
        if($request->filled('date') && $request->filled('user_id')) {
            $taskDashboardController = new TaskDashboardController();
            $entry = DailyTask::where('current_date', $request->date)->where('user_id', $request->user_id)->firstOrNew([
                'current_date' => $request->date,
                'user_id' => $request->user_id,
            ]);
            if(!empty($entry)) {
                $modalData = $taskDashboardController->getWorkLogDetails($request); // Use the TaskController's method for maintain consistency

                // If there is time entry for the date available
                if($modalData->isNotEmpty()) {
                    $entry->eod_description = view("task-dashboard.details-modal-content", compact('modalData'))->render();
                }else{
                    $entry->eod_description = NULL;
                }
                $entry->save();
            }
        }
    }

    public function sendTodaysDefaulterInEmail(Request $request)
    {
        $send_to = $request->input('send_to', ['harpalsinh.inexture@gmail.com']);
        $defaulterController = new DefaulterController;
        $response_html = $defaulterController->getDefaulterData($request);
        $today = $request->run_at->format('d-m-Y');

        if(!empty($response_html)) {
            Mail::send('emails.defaulters.today_defaulter', ['response_html' => $response_html, 'run_at' => $request->run_at], function ($message) use ($send_to, $today) {
                $message->from(env('MAIL_USERNAME') ? (config('constant.default_users_email.admin') ? config('constant.default_users_email.admin') : 'admin@inexture.in') : 'admin@inexture.in', env('MAIL_FROM_NAME') ? (config('constant.default_users_name.admin') ? config('constant.default_users_name.admin') : 'INEXTURE PORTAL') : 'INEXTURE PORTAL');
                $message->to($send_to)
                ->subject("Date:$today: Defaulter List" );
            });
        }
    }

    public function syncEODWithWorkLogEntry(Request $request)
    {
        $taskDashboardController = new TaskDashboardController();

        $date = $request->input('date', now()->format('2022-03-01'));


        $daily_task_entries = DailyTask::select();

        $daily_task_entries->where('current_date', '>=', $date);

        foreach ($daily_task_entries->cursor() as $entry) {
            $request['user_id'] = $entry->user_id;
            $request['date'] = Carbon::parse($entry->current_date)->format('Y-m-d');
            $modalData = $taskDashboardController->getWorkLogDetails($request); // Use the TaskController's method for maintain consistency

            // If there is time entry for the date available
            if($modalData->isNotEmpty()) {
                $entry->eod_description = view("task-dashboard.details-modal-content", compact('modalData'))->render();
            }else{
                $entry->eod_description = NULL;
            }
            $entry->save();
        }
        echo "done";
    }

    public function UpdateTheAllotedLeave(Request $request)
    {
        $year = now()->year;
        // Get the users whose joining date is after 10-01 and before 1-Apr
        $users = User::with(['officialUser', 'leaveAllocationByFY'])->whereHas('officialUser', function ($query) use ($year) {
            $query->whereBetween('joining_date', ["{$year}-01-08","{$year}-03-31"]);
        })->get();
        foreach ($users as $key => $user) {
            // Get the leaveAllocation entry
            $leaveAllocation = $user->leaveAllocationByFY ?? null;
            if(!empty($leaveAllocation)) {
                $leaves = Helper::getAllocatedLeaves($user, $user->officialUser['joining_date']);
                if(!empty($leaves)) {
                    $leaveAllocation->allocated_leave = $leaves;
                    $leaveAllocation->total_leave = $leaves;
                }
                $leaveAllocation->save();
            }
        }
        // Calculate allocated leave

        // If the entry is exists the update the total allocated leaves

        // If entry not available the add new entry
    }
}
