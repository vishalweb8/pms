<?php

namespace App\Http\Controllers;

use App\Events\ManageSodEodDate;
use App\Models\DailyTask;
use App\Models\EmployeeTimeLogEntry;
use App\Models\EmployeeTimeLogs;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\UserOfficialDetail;
use App\Models\WorkFromHome;
use App\Models\LeaveAllocation;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\PendingSodEod;
use App\Models\User;
use Auth;
use PDO;
use App\Helpers\Common;
use App\Models\TimeEntry;

class DashboardController extends Controller
{
    public function userDashboard(Request $request)
    {

        if ($request->filled('action')) {
            if ($request->action == 'updateAllocatedLeave') {
                return $this->updateAllocatedLeave($request);
            }elseif ($request->action == 'updateEmails') {
                return $this->updateEmails($request);
            }
        }
        $leaves = Leave::where('request_from','=',\Auth::user()->id)->where('status','=','approved')->get();
        $userLeaves = LeaveAllocation::where('allocated_year', Helper::getFinancialStartYearFromDate(today()))->where('user_id','=',\Auth::user()->id)->first();
        $dailyTaskMgt = DailyTask::with('userOfficial')->where('user_id','=',Auth::user()->id)->get();
        $holiday = Holiday::pluck('date');
        $joiningDate = UserOfficialDetail::where('user_id',Auth::user()->id)->first();
        $currentYearDate = Carbon::parse(Config('constant.currentYearDate'));
        $dt = Carbon::now();
        $nonFillableDates = [];
        $nonLeaveDates = [];
        $uniqueArray = [];
        $nonHolidayDates = [];
        $task_entry_date = '';
        if(!empty($joiningDate)){
            $taskEntryDate = Carbon::parse($joiningDate->task_entry_date);
            $joiningDate = Carbon::parse($joiningDate->joining_date);
        }
        if(!empty($leaves)){
            foreach($leaves as $lists){
                $start_date = Carbon::parse($lists->start_date);
                $end_date = Carbon::parse($lists->end_date);

                for($dl = $start_date; $dl->lte($end_date); $dl->addDay()) {
                    $nonLeaveDates[] = $dl->format('d-m-Y');
                }
            }
        }
        if(!empty($holiday)){
            foreach($holiday as $dates){
                $nonHolidayDates[] = $dates;
            }
        }
        if(Auth::user()->sod_eod_enabled == '1') {
            $pending_dates = PendingSodEod::where('user_id', Auth::id())->orderBy('date')->pluck('date');
            if(!empty($pending_dates) && $pending_dates->count()) {
                $nonFillableDates = $pending_dates;
            }
        }
        $empCode = Auth::user()->officialUser->emp_code ?? null;
        $lastDay = Helper::lastDay($empCode);
        $thisWeek = Helper::thisWeek($empCode);
        $aveMonth = Helper::currentMonth($empCode);

        return view('dashboard.user-dashboard',compact('leaves','nonFillableDates','nonLeaveDates','nonHolidayDates','userLeaves','lastDay','thisWeek','aveMonth'));
    }

    public function addModal()
    {
        $now = now()->format('Y-m-d');
        DB::enableQueryLog();
        $holidays = Holiday::select(['holidays.*', DB::raw("if(date<'".$now."', '0', '1') as sort_order")])->where('deleted_at','=',null)
            ->orderByRaw('sort_order DESC')
            ->orderBy('date','ASC')
            ->get();
        return view('dashboard.view-holiday-modal',compact('holidays'));
    }

    public function viewLeaveModal()
    {

        $userLeaves = LeaveAllocation::where('allocated_year', Helper::getFinancialStartYearFromDate(today()))->where('user_id','=',\Auth::user()->id)->first();

        $leaves = Leave::where('request_from','=',\Auth::user()->id)
            ->where('status','=','approved')
            ->whereDate('start_date', '>=', Helper::getFinancialStartYearFromDate(today()) . "-04-01")
            ->orderBy('start_date', 'DESC')->get();

        return view('dashboard.view-my-leave',compact('leaves','userLeaves'));
    }

    public function viewEmpLeaveModal($id){
        $user = User::where('id',$id)->with(['allocatedLeaves','leaves' => function ($query) {
            $query->financialYear(request('fyear'))->where('status','approved')->orderBy('start_date', 'DESC');
        }]);
        if (Auth::user()->isManagement()) {
            $user = $user->withBlocked();
        }
        $user = $user->first();
        $leaves = $user->leaves ?? [];
        $userLeaves = $user->allocatedLeaves ?? null;
        return view('dashboard.view-emp-leave',compact('leaves','userLeaves','user'));
    }

    public function viewtodayLeaveModal()
    {
        // $leaves = Leave::with('userTeam.teamsLeave')->get();
        $leaves = Leave::has('activeUser')->with(['activeUser','userTeam.teamsLeave'])->where('status','=','approved')
                        ->where('start_date', '<=' ,now()->format('Y-m-d'))->where('end_date', '>=' ,now()->format('Y-m-d'))->get();

        return view('dashboard.view-today-leave',compact('leaves'));
    }

    public function viewUpcomingLeave()
    {
        $leaves = Leave::has('activeUser')->with(['activeUser','userTeam.teamsLeave'])->where('status','=','approved')->where(
            function($query){
                return $query
                ->whereDate('start_date','>',Carbon::now()->format('Y-m-d'))
                ->orWhereDate('end_date','>',Carbon::now()->format('Y-m-d'));
            })->orderBy('start_date','asc')->get();
        return view('dashboard.view-upcoming-leave',compact('leaves'));
    }

    public function viewtodayWFHModal()
    {
        $wfh = WorkFromHome::has('activeUser')->with(['activeUser','userTeam.teamsLeave'])->where('status','=','approved')
            ->where(
                function($query){
                    return $query
                    ->where('start_date', '<=' ,now()->format('Y-m-d'))
                    ->where('end_date', '>=' ,now()->format('Y-m-d'));
                })->orderBy('user_id','desc')->get();
        return view('dashboard.view-today-wfh',compact('wfh'));
    }

    public function viewTimeentryModal()
    {
        $empCode = Auth::user()->officialUser->emp_code ?? null;
        $startMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $timeEntry = TimeEntry::durations()
            ->where('EmployeeCode',$empCode)
            ->whereBetween('LogDateTime',[$startMonth,$endMonth])
            ->orderBy('log_date','desc')
            ->get();

        return view('dashboard.view-time-entry',compact('timeEntry'));
    }

    public function viewTodayTimeEntryModal($empCode = null, $date = null)
    {
        $todayTitle = "";
        if(!$empCode) {
            $empCode = Auth::user()->officialUser->emp_code ?? null;
        }
        if(!$date) {
            $date = today();
            $todayTitle = "My Today's";
        }

        $timeEntry = TimeEntry::durations()
            ->where('EmployeeCode',$empCode)
            ->whereDate('LogDateTime',$date)
            ->orderBy('log_date')
            ->get();
        return view('dashboard.my-today-time-entry',compact('timeEntry','todayTitle'));
    }
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     * @date 08-02-2022 
     */
    public function updatePendingEntries(Request $request)
    {
        // return ['test', $request->all()];

        // ManageSodEodDate::dispatch('remove', '88', '2022-02-07');

        $start_date = Carbon::parse('2022-01-31');
        $to_date = now();
        $users = User::where('sod_eod_enabled', '1');
        $holiday_list = Helper::totalHolidays();
        $holiday_dates = $holiday_list->pluck('date');
        foreach ($users->cursor() as $key => $user) {
            if($request->filled('date')) {
                $start_date = Carbon::parse($request->date);
            }elseif(!empty($user->officialUser) && !empty($user->officialUser->task_entry_date)) {
                $start_date = Carbon::parse($user->officialUser->task_entry_date);
            }

            // If any entry before the start date is exist in pending_sod table the remove it for current user
            PendingSodEod::where('user_id', $user->id)->whereDate('date',"<", $start_date->format('Y-m-d'))->delete();

            $total_days = $start_date->diffInDays($to_date);

            for ($i=0; $i <= $total_days; $i++) {
                if($i>0) {
                    $start_date->addDay();
                }
                // Check for the date is weekend
                if(in_array($start_date->format('l'), ['Saturday', 'Sunday'])) {
                    continue;
                }
                // Check for the date is holiday
                if(in_array($start_date->format('d-m-Y'), $holiday_dates->toArray())) {
                    continue;
                }

                // Manage leave
                $today_leave_entry = (!empty($user->leaves)) ? $user->leaves()->where('start_date', '<=' ,$start_date->format('Y-m-d'))
                                                                ->where('end_date', '>=' ,$start_date->format('Y-m-d'))->first() : null;
                if(!empty($today_leave_entry) && $today_leave_entry->status == 'approved' && $today_leave_entry->type == "full") {
                    continue;
                }

                // Manage the day entry
                $task_entry = $user->userDailyTask()->where('current_date', $start_date->format('Y-m-d'))
                                    ->whereNotNull('sod_description');
                // if($start_date->format('d-m-Y') != now()->format('d-m-Y')) {
                //     $task_entry= $task_entry->whereNotNull('eod_description');
                // }

                $task_entry= $task_entry->first();

                if(!empty($task_entry)) {
                    PendingSodEod::where('user_id', $user->id)->where('date', $start_date->format('Y-m-d'))->delete();
                    continue;
                }

                //Add entries into table
                PendingSodEod::firstOrCreate([
                    'user_id' => $user->id,
                    'date' => $start_date->format('Y-m-d')
                ]);

                if($start_date->format('d-m-Y') == now()->format('d-m-Y')) {
                    break;
                }
            }
        }
    }

    /**
     * Recalculate the leave allocation
     *
     * @param Request $request
     * @return void|string
     * @date 14-02-2022 
     */
    public function updateAllocatedLeave(Request $request)
    {
        $userLeaves = LeaveAllocation::with(['user.officialUser']);
        if($request->filled('user_id')) {
            $userLeaves->where('user_id', $request->user_id);
        }
        $userLeaves = $userLeaves->get();

        foreach ($userLeaves as $allocated_leave) {
            if(!empty($allocated_leave->user) && !empty($allocated_leave->user->officialUser)) {
                $joiningDate = $allocated_leave->user->officialUser->joining_date;
                if(!empty($joiningDate)) {
                    $join_date = Carbon::createFromFormat('d-m-Y', $joiningDate);
                    $leave_allocation_date = $join_date->copy()->addMonths(3);
                    $after_allocation_date = Carbon::createFromDate("01-04-".now()->subYear()->year);
                    $end_allocation_date = Carbon::createFromDate("31-03-".now()->year);

                    if($leave_allocation_date->greaterThan($after_allocation_date) && $leave_allocation_date->lessThan($end_allocation_date)){
                        $assign_leaves = Helper::getAllocatedLeaves($allocated_leave->user, $joiningDate);
                        $allocated_leave->total_leave = $assign_leaves;
                        $allocated_leave->allocated_leave = $assign_leaves;
                        $allocated_leave->save();
                    }elseif($leave_allocation_date->greaterThan($end_allocation_date)) {
                        $allocated_leave->total_leave = 0;
                        $allocated_leave->allocated_leave = 0;
                        $allocated_leave->save();
                    }
                }
            }
        }
        echo "All Done!";
    }

    public function checkLeaveAllocationCalculation(Request $request)
    {
        $user = Auth::user();
        if($request->filled('id')) {
            $user = User::find($request->id);
        }elseif($request->filled('email')) {
            $user = User::where('email', $request->email)->first();
        }

        if(!empty($user)) {
            $joiningDate = $user->officialUser->joining_date;
            $join_date = Carbon::createFromFormat('d-m-Y', $joiningDate);
            $leave_allocation_date = $join_date->copy()->addMonths(3);
            if(now()->year == $leave_allocation_date->year) {

                $after_allocation_date = Carbon::createFromDate("01-04-".now()->year);
                $end_allocation_date = Carbon::createFromDate("31-03-".now()->addYear()->year);
            }else{
                $after_allocation_date = Carbon::createFromDate("01-04-".now()->subYear()->year);
                $end_allocation_date = Carbon::createFromDate("31-03-".now()->year);

            }
            if($leave_allocation_date->greaterThan($after_allocation_date) && $leave_allocation_date->lessThan($end_allocation_date)){
                $data = Helper::getAllocatedLeaves($user, $joiningDate, true);
            }else {
               $data['join_date'] = $join_date;
               $data['leave_allocation_date'] = $leave_allocation_date;
               $data['after_allocation_date'] = $after_allocation_date;
               $data['end_allocation_date'] = $end_allocation_date;
            }
            return view('dashboard.view-leave-allocation-calculation',compact('data'));
        }
    }

    public function updateEmails(Request $request)
    {
        $email_list = config('old_portal_statistical.update_email');

        foreach ($email_list as $entry) {
            $user = User::where('email', trim($entry['old']))->first();
            if(!empty($user)) {
                $user->email = trim($entry['new']);
                $user->user_name = trim($entry['user_name']);
                $user->save();
            }
        }
        dump("Done"); exit();
    }
}
