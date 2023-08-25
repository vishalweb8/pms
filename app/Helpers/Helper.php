<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Models\UserOfficialDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;
use App\Models\WorkFromHome;
use App\Models\Holiday;
use App\Models\LeaveAllocation;
use App\Models\User;
use App\Models\Project;
use App\Models\Team;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\commonController;
use App\Models\TeamLeaderMember;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\Log;

class Helper
{
    /**
     * for check single permission
     *
     * @param string $permission [single permission check]
     * @return boolean
     * @date 17-09-2021
     * @author Yatin Bhanderi <yatin.inexture@gmail.com>
     */
    public static function hasPermission($permission = '')
    {
        if(!$permission || !Auth::check()) {return false;}

        return (Auth::user()->hasDirectPermission($permission) || Auth::user()->hasPermission($permission));
    }
    /**
     * user for multiple permissions
     *
     * @param array $permissions [list of permissions]
     * @return boolean
     * @date 17-09-2021
     * @author Yatin Bhanderi <yatin.inexture@gmail.com>
     */
    public static function hasAnyPermission($permissions = [])
    {
        if (!$permissions || !Auth::check()) {return false;}

        return (Auth::user()->hasAnyDirectPermission($permissions) || Auth::user()->hasAnyPermission($permissions));
    }
    
    /**
     * for check has any member or not to current user.
     *
     * @return void
     */
    public static function isMentor()
    {
        $isExists = true;
        $user = Auth::user();
        $code = $user->roles[0]->code ?? null;
        $permissions = ['worklog.list','all-emp-time-entry.list'];
        if($code == "EMP") {
            $isExists = User::where('id',$user->id)->has('teamLeaderMembers')->exists();
        }
        
        return $user->hasAllPermissions($permissions) && $isExists;
    }

    /**
     * For make the filter array for
     *
     * @param [type] $filed_name
     * @param string $operator
     * @param [type] $value
     * @return array
     * @date 20-09-2021
     * @author Yatin Bhanderi <yatin.inexture@gmail.com>
     */
    public static function makeFilter($filed_name, $operator, $value)
    {
        if(empty($value)) { return  []; }
        if(empty($operator)) { $operator = '='; }

        return ['field' => $filed_name, 'operator' => $operator, 'value' => $value];
    }

    public static function ajaxFillDropdown($parent_ele, $child_ele, $url,$clear_dropdowns = [])
    {
        return view('common.dependant-dropdown', compact('parent_ele', 'child_ele', 'url', 'clear_dropdowns'))->render();
    }

    public static function getDateFormat($date, $format = 'd-m-Y')
    {
        if(empty($date) || $date == '0000-00-00'){
            return;
        }
        return \Carbon\Carbon::parse($date)->format($format);
    }

    public static function setDateFormat($date)
    {
        if (empty($date) || $date == '0000-00-00') {
            return;
        }
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public static function getDateTimeFormat($date)
    {

        return \Carbon\Carbon::parse($date)->format('Y-m-d H:m:s');
    }

    public static function getNextAutoCode($model, $field)
    {
        $lastAutoCode = $model->where($field, '<>', NULL)->orderBy('id', 'desc')->first()->$field;
        $lastCode = preg_replace('/[^0-9]/', '', $lastAutoCode);
        $autoCode = ($lastCode != '') ? ( $lastCode + 1 ): 1 ;
        return $autoCode;
    }

    public static function getEmployeeIdByCode($code,$field,$selectField)
    {
        $userId = UserOfficialDetail::where($field,$code)->select($selectField)->first();
        if($userId){
            return $userId->$selectField;
        }
        else{
            return null;
        }
    }

    public static function calulateTotalDuration($time)
    {
        $sum = strtotime('00:00:00');
        $totaltime = 0;

        foreach( $time as $element ) {
            // Converting the time into seconds
            $timeinsec = strtotime($element) - $sum;
            // Sum the time with previous value
            $totaltime = $totaltime + $timeinsec;
        }

        // Totaltime is the summation of all
        // time in seconds
        // Hours is obtained by dividing
        // totaltime with 3600
        $h = intval($totaltime / 3600);
        $totaltime = $totaltime - ($h * 3600);

        // Minutes is obtained by dividing
        // remaining total time with 60
        $m = intval($totaltime / 60);

        // Remaining value is seconds
        $s = $totaltime - ($m * 60);
        $h = str_pad($h, 2, "0", STR_PAD_LEFT);
        $m = str_pad($m, 2, "0", STR_PAD_LEFT);
        $s = str_pad($s, 2, "0", STR_PAD_LEFT);
        // Printing the result
        return ("$h:$m:$s");
    }

    public static function lastDay($empCode)
    {
        //fetch last day total duration
        $lastDay = TimeEntry::durations()
            ->where('EmployeeCode',$empCode)
            ->whereDate('LogDateTime',Carbon::yesterday()->format('Y-m-d'))
            ->first();

        return $lastDay->total_duration ?? "00:00:00";
    }

    public static function thisWeek($empCode)
    {
        //fetch current week total duration
        $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        $thisWeek = TimeEntry::durations()
            ->where('EmployeeCode',$empCode)
            ->whereBetween('LogDateTime',[$startDate,$endDate])
            ->get()
            ->pluck('total_duration');
        return Self::calulateTotalDuration($thisWeek);
    }

    public static function currentMonth($empCode)
    {
        // fetch current month total duration average dayswise
        $startMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $thisMonth = TimeEntry::durations()
            ->where('EmployeeCode',$empCode)
            ->whereBetween('LogDateTime',[$startMonth,$endMonth])
            ->get();
        $totalHours = Self::calulateTotalDuration($thisMonth->pluck('total_duration'));
        $dailyTime = $thisMonth->count();
        $secToTime = "00:00:00";
        //this month total hours convert to second
        $totalThisMonthSecond = Self::timeToSeconds($totalHours);
        if($totalThisMonthSecond > 0){
            // divide by total this month to daily hours
            $divideTotalMonthSec = ($totalThisMonthSecond / $dailyTime);
            // convert seconds to time
            $secToTime = round($divideTotalMonthSec);
            $secToTime = date("H:i:s", mktime(0, 0,$secToTime));
        }
        return $secToTime;
    }
    public static function timeToSeconds(string $time): int
    {
        $arr = explode(':', $time);
        if (count($arr) === 3) {
            return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        }else {
            return 0;
        }
        return $arr[0] * 60 + $arr[1];
    }

    public static function totalEmployee()
    {
        return Leave::has('activeUser')->where('status','=','approved')
            ->where(
                function($query){
                    return $query
                    ->where('start_date', '<=' ,now()->format('Y-m-d'))->where('end_date', '>=' ,now()->format('Y-m-d'))
                    ->groupBy('request_from');
                })->count();
    }

    public static function fullLeaves()
    {
        return Leave::has('activeUser')->where('type', '=', 'full')->where('start_date', '<=' ,now()->format('Y-m-d'))
            ->where('end_date', '>=' ,now()->format('Y-m-d'))
            ->where('status','=','approved')
            ->select(DB::raw("count(id) as total_duration"))
            ->first();
    }

    public static function halfLeaves()
    {
        return Leave::has('activeUser')->where('type','=','half')->where('start_date', '<=' ,now()->format('Y-m-d'))
            ->where('end_date', '>=' ,now()->format('Y-m-d'))
            ->where('status','=','approved')
            ->select(DB::raw("COUNT(id) as total_duration"))->first();
    }

    public static function totalUpcomingLeaves()
    {
        return Leave::has('activeUser')->where('status','=','approved')->where(
            function ($query){
                return $query
                    ->whereDate('start_date','>',Carbon::now()->format('Y-m-d'))
                    ->orWhereDate('end_date','>',Carbon::now()->format('Y-m-d'));
            })->get()->groupBy('request_from');
    }

    public static function fullUpcomingLeaves()
    {
        return Leave::has('activeUser')->where('status','=','approved')
                ->where('type','=','full')
                ->where(
                    function ($query) {
                        return $query
                            ->whereDate('start_date','>',Carbon::now()->format('Y-m-d'))
                            ->orWhereDate('end_date','>',Carbon::now()->format('Y-m-d'));
                        })->select(DB::raw("SUM(duration) as total_duration"))->first();
    }

    public static function halfUpcomingLeaves()
    {
        return Leave::has('activeUser')->where('status','=','approved')
                ->where('type','=','half')->where(
                function ($query) {
                    return $query
                        ->whereDate('start_date','>',Carbon::now()->format('Y-m-d'))
                        ->orWhereDate('end_date','>',Carbon::now()->format('Y-m-d'));
                    })->select(DB::raw("COUNT(duration) as total_duration"))->first();
    }

    public static function totalWfh()
    {
        return WorkFromHome::has('activeUser')->where('status','=','approved')->where(
            function ($query){
                return $query
                ->where('start_date', '<=' ,now()->format('Y-m-d'))
                ->where('end_date', '>=' ,now()->format('Y-m-d'))
                ->groupBy('user_id');
            })->count();
    }

    public static function fullWfh()
    {
        return WorkFromHome::has('activeUser')->where('status','=','approved')
            ->where('wfh_type','=','full')->where(
            function ($query) {
                return $query
                    ->where('start_date', '<=' ,now()->format('Y-m-d'))
                    ->where('end_date', '>=' ,now()->format('Y-m-d'));
                })->select(DB::raw("SUM(duration) as total_duration"))->first();
    }

    public static function halfWfh()
    {
        return WorkFromHome::has('activeUser')->where('status','=','approved')
                ->where('wfh_type','=','half')
                ->where(
                    function ($query) {
                        return $query
                        ->where('start_date', '<=' ,now()->format('Y-m-d'))
                        ->where('end_date', '>=' ,now()->format('Y-m-d'));
                        })->select(DB::raw("COUNT(duration) as total_duration"))->first();
    }

    public static function usedLeaves($userId)
    {
        $current_year = date("Y");
        $past_year = date("Y",strtotime("-1 year"));
        $next_year = date("Y",strtotime("+1 year"));

        $from_date = $past_year."-04-01";
        $to_date = $current_year."-03-31";

        $current_date_timestamp = strtotime(date('Y-m-d'));
        $last_date_timestamp = strtotime($to_date);

        if($current_date_timestamp <= $last_date_timestamp){
           return Leave::where('request_from','=',$userId)
            ->where('status','=','approved')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])
            ->first();
        }else{

            $from_date = $current_year."-04-01";
            $to_date = $next_year."-03-31";

            return Leave::where('request_from','=',$userId)
            ->where('status','=','approved')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }

    }

    public static function remainingLeaves($userId)
    {
        $current_year = date("Y");
        $past_year = date("Y",strtotime("-1 year"));
        $next_year = date("Y",strtotime("+1 year"));

        $from_date = $past_year."-04-01";
        $to_date = $current_year."-03-31";

        $current_date_timestamp = strtotime(date('Y-m-d'));
        $last_date_timestamp = strtotime($to_date);

        if($current_date_timestamp <= $last_date_timestamp){
           $remainingLeave = Leave::where('request_from','=',$userId)
            ->where('status', '=', 'approved')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }else{
            $from_date = $current_year."-04-01";
            $to_date = $next_year."-03-31";
            $remainingLeave = Leave::where('request_from','=',$userId)
            ->where('status', '=', 'approved')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }

        // Get the allocated leave details
        $now = now();
        $allocated_year = now()->year;
        // Check the current date is between 1-Jan to 31-March then consider last year as allocated year
        if($now->greaterThan(Carbon::parse("$allocated_year-01-01")) && $now->lessThan(Carbon::parse("$allocated_year-03-31 23:59:59"))) {
            $allocated_year = now()->subYear()->year;
        }
        $allocated_leaves = LeaveAllocation::where('user_id', $userId)->where('allocated_year', $allocated_year)->first();
        if(!empty($allocated_leaves)) {
            $subLeave = $allocated_leaves->total_leave - $remainingLeave->total_duration;
        }else{
            $subLeave = (config('constant.total_leave') > $remainingLeave->total_duration) ? (config('constant.total_leave') - $remainingLeave->total_duration) : 0 ;
        }

        return $subLeave;
    }

    /**
     * to get array wfh counts group by status
     *
     * @return array
     */
    public static function getWFHCounts()
    {
        $leaveCount = [
            'pending' => 0,
            'approved' => 0,
            'cancelled' => 0,
            'rejected' => 0,
        ];
        try {
            $query = WorkFromHome::where('id', '!=', 0);
            $query->addSelect('status',DB::raw('count(id) as count'));
            $leaveCounts = $query->groupBy('status')->pluck('count','status')->toArray();
            $leaveCounts = array_replace($leaveCount,$leaveCounts);

        } catch (\Throwable $th) {
            Log::error("getting error while count leaves:-".$th);
        }
        return $leaveCounts;
    }


    /**
     * to get array billable status
     *
     * @return array
     */
    public static function getResourceBillableStatus()
    {


        $billable = DB::select("SELECT resource_management.user_id, resource_management.status, resource_management.project_id, users.id, users.first_name FROM `resource_management` INNER JOIN users on users.id = resource_management.user_id WHERE resource_management.status = 1 and users.status = 1 GROUP by resource_management.user_id ORDER by users.id");

        $nonbillable = DB::select("SELECT resource_management.user_id, resource_management.status, resource_management.project_id, users.id, users.first_name FROM resource_management INNER JOIN users on users.id = resource_management.user_id WHERE resource_management.user_id in (SELECT user_id FROM `resource_management` WHERE status=2) and resource_management.user_id not in (SELECT user_id FROM `resource_management` WHERE status=1) AND users.status=1");

        $on_hold = User::whereHas('resourceProjects', function ($qry) {
                $qry->where('status',3);
            })->count();

        $no_project = User::doesntHave('resourceProjects')->count();


        $statsCount = [
            'billable'=>sizeof($billable),
            'nonbillable'=>sizeof($nonbillable),
            'on_hold'=>$on_hold,
            'no_project'=>$no_project,
        ];

        return $statsCount;
    }

    /**
     * for get array leave counts group by status
     *
     * @param  mixed $requestFrom
     * @return void
     */
    public static function getLeaveCounts($query)
    {
        $leaveCount = [
            'pending' => 0,
            'approved' => 0,
            'cancelled' => 0,
            'rejected' => 0,
        ];
        try {
            $query->addSelect('status',DB::raw('count(id) as count'));
            $leaveCounts = $query->groupBy('status')->pluck('count','status')->toArray();
            $leaveCounts = array_replace($leaveCount,$leaveCounts);

        } catch (\Throwable $th) {
            Log::error("getting error while count leaves:-".$th);
        }
        return $leaveCounts;
    }

    public static function getUserTypeCounts($query)
    {
        $userCount = [
            '1' => 0,
            '0' => 0,
            '2' => 0,
        ];
        try {
            $query->addSelect('status',DB::raw('count(id) as count'));
            $userCounts = $query->groupBy('status')->pluck('count','status')->toArray();

            $userCounts = array_replace($userCount,$userCounts);
            if($userCounts['']){
                $userCounts['0'] += ($userCounts['']) ? $userCounts['']: 0;
            }
            $userCounts['2'] += $userCounts['1'] + $userCounts['0'];
            unset($userCounts['']);

        } catch (\Throwable $th) {
            Log::error("getting error while count users:-".$th);
        }
        return $userCounts;
    }

    public static function totalHolidays()
    {
        return Holiday::where('deleted_at', '=', null)->get();
    }

    public static function thisMonthHolidays()
    {
        return Holiday::where('deleted_at', '=', null)
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)->get();
    }

    public static function nextMonthHolidays()
    {
        return Holiday::where('deleted_at', '=', null)
                ->whereMonth('date', Carbon::now()->addMonth()->month)->get();
    }

    public static function fetchReportingIds($userId)
    {
        $requestUsers = [];
        $mentorIds = TeamLeaderMember::where('member_id',$userId)->pluck('user_id')->toArray();
        $employee = UserOfficialDetail::where('user_id', $userId)->first();
        $reportingIds = ($employee && $employee->reporting_ids) ? explode(',', $employee->reporting_ids) : [];
        $reportingIds = array_merge($reportingIds,$mentorIds);

        $requestUsers = User::whereIn('id', $reportingIds)->orWhereIn('email',config('constant.default_users_email'))->get()->pluck('full_name','id')->toArray();

        return $requestUsers;
    }

    /**
     * Calculate total leave duration
     *
     * @param [type] $start_date
     * @param [type] $to_date
     * @param array $holiday_list
     * @return void
     * @date 10-02-2022
     */
    public static function calculateLeaveDuration($start_date, $to_date, $day_type="full", $holiday_list = [])
    {
        if(empty($holiday_list)) {
            $holiday_list = Helper::totalHolidays();
            $holiday_list = $holiday_list->pluck('date');
        }
        $total_duration = 0;
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
            if(in_array($start_date->format('d-m-Y'), $holiday_list->toArray())) {
                continue;
            }
            $total_duration += ($day_type == "full") ? 1 : 0.5;
        }
        return $total_duration;
    }

    public static function getFinancialYearDates()
    {
        $dates = [];
        if(now()->month < 4) {
            $dates['start_date'] = Carbon::createFromDate(now()->subYear()->year, 04, 01)->setHour(0)->setMinute(0)->setSecond(0)->setMillisecond(0);
            $dates['end_date'] = Carbon::createFromDate(now()->year, 03, 31)->setHour(23)->setMinute(59)->setSecond(59)->setMillisecond(0);
        }else {
            $dates['start_date'] = Carbon::createFromDate(now()->year, 04, 01)->setHour(0)->setMinute(0)->setSecond(0)->setMillisecond(0);
            $dates['end_date'] = Carbon::createFromDate(now()->addYear()->year, 03, 31)->setHour(23)->setMinute(59)->setSecond(59)->setMillisecond(0);
        }
        return $dates;
    }

    /**
     * Calculate the leave allocation on joining date
     *
     * @param [user] $user
     * @param [type] $joiningDate
     * @param [boolean] $is_viewable
     * @return [float|array] allocated leaves
     * @date 14-02-2022
     */
    public static function getAllocatedLeaves($user, $joiningDate, $is_viewable = false)
    {
        $yearly_allocated_leaves = ((float) config('constant.leaves.allocated_leave_in_financial_year') - 1); // Default allocated leaves, its total leaves = birthday/anniversary + leaves, so use -1 for ignore birthday/anniversary
        $viewable_data = [];
        if(!empty($joiningDate)) {
            $join_date = Carbon::createFromFormat('d-m-Y', $joiningDate)->setHour(0)->setMinute(0)->setSecond(0)->setMillisecond(0);
            $leave_allocation_date = $join_date->copy()->addMonths(3); // Fetch the date after probation period is over.
            $financial_dates = Helper::getFinancialYearDates();
            if($leave_allocation_date->lessThan($financial_dates['start_date'])) {
                $leave_allocation_date = $financial_dates['start_date']->addDay();
            }
            $after_allocation_date = Carbon::createFromDate("01-04-".$financial_dates['start_date']->year)->setHour(0)->setMinute(0)->setSecond(0)->setMillisecond(0); // Set the current year's allocation start date.

            if($leave_allocation_date->greaterThanOrEqualTo($after_allocation_date)) {
                $end_allocation_date = $financial_dates['end_date']->addDay()->setTime(0,0,0); // Set the end date for the current year allocation.
                $viewable_data['join_date'] = $join_date;
                $viewable_data['leave_allocation_date'] = $leave_allocation_date;
                $viewable_data['after_allocation_date'] = $after_allocation_date;
                $viewable_data['end_allocation_date'] = $end_allocation_date;

                if($leave_allocation_date->greaterThanOrEqualTo($after_allocation_date) && $leave_allocation_date->lessThan($end_allocation_date)){
                    $months = round($leave_allocation_date->diffInMonths($end_allocation_date)); // Get remaining months
                    $days = 0;
                    if($leave_allocation_date->day != 1) {
                        $days = ($leave_allocation_date->daysInMonth-$leave_allocation_date->day) + 1; // Get remaining days of joining month
                    }
                    $month_leave_count = (($months*$yearly_allocated_leaves)/12); // Get the leave count of remaining month
                    if(!empty($month_leave_count)) {
                        $month_leave_count = round($month_leave_count, 3);
                    }
                    $remaining_days_leave_count = $days*0.045; // 16÷364 = 0.043956044 // Get the leave count of remaining days
                    $viewable_data['months'] = $months;
                    $viewable_data['days'] = $days;
                    $viewable_data['month_leave_count'] = $month_leave_count;
                    $viewable_data['remaining_days_leave_count'] = $remaining_days_leave_count;

                    $leave_to_be_allocated =(float) number_format($month_leave_count+$remaining_days_leave_count,3); // Combine day's leave count and month's leave count
                    $int_leave = (int) $leave_to_be_allocated; // get the integer value of leave count (without decimal)
                    $viewable_data['leave_to_be_allocated'] = $leave_to_be_allocated;

                    $decimal_leave = $leave_to_be_allocated - $int_leave;
                    if($decimal_leave >= 0.750) {
                        $decimal_leave = 1;
                    }elseif($decimal_leave >= 0.500 && $decimal_leave < 0.750) {
                        $decimal_leave = 0.5;
                    }else {
                        $decimal_leave = 0;
                    }
                    $yearly_allocated_leaves = $int_leave + $decimal_leave;


                    // Check the birthday or anniversary days come between the joining date and end allocation date
                    if(!empty($user)) {
                        $birth_date = Carbon::parse($user->birth_date)->setHour(0)->setMinute(0)->setSecond(0);
                        if ($birth_date->month > 3) {
                            $birth_date->setYear($leave_allocation_date->year);
                        }else {
                            $birth_date->setYear($end_allocation_date->year);
                        }
                        $anniversary_date = '';
                        if(!empty($user->wedding_anniversary)) {
                            $anniversary_date = Carbon::parse($user->wedding_anniversary)->setHour(0)->setMinute(0)->setSecond(0);
                            if($anniversary_date->month > 3) {
                                $anniversary_date->setYear($leave_allocation_date->year);
                            }else {
                                $anniversary_date->setYear($end_allocation_date->year);
                            }
                        }
                        // Allocation_start_date <= birth_date/anniversary_date <= end_allocation_date
                        if(($birth_date->greaterThanOrEqualTo($leave_allocation_date) && $birth_date->lessThanOrEqualTo($end_allocation_date) )||
                        !empty($anniversary_date) && $anniversary_date->greaterThanOrEqualTo($leave_allocation_date) && $anniversary_date->lessThanOrEqualTo($end_allocation_date)) {
                            $yearly_allocated_leaves +=1;
                        }
                        $viewable_data['birth_date'] = $birth_date;
                        $viewable_data['anniversary_date'] = $anniversary_date;
                    }
                    $viewable_data['yearly_allocated_leaves'] = $yearly_allocated_leaves;

                }else {
                    $yearly_allocated_leaves = 0;
                }
            }
        }

        if($is_viewable && !empty($viewable_data)) {
            return $viewable_data;
        }
        return $yearly_allocated_leaves;
    }

    public static function hasAccess($repo, $key, $allowedRoles = [], $allowedUserId = '') {
        array_push($allowedRoles, 'Super Admin');
        $currentUserId = Auth::user()->id;
        $currentUserRole = Auth::user()->roles->pluck('name')->toArray();

        // $userTeamLeaders = Helper::getUserTeamLeader($repo->{$key});
        // $teamLeadIds = [];
        // if(sizeof($userTeamLeaders) > 0) {
        //     $teamLeadIds = array_keys($userTeamLeaders);
        // }

        $allowedUserIdArr = [];
        if($allowedUserId != '') {
            $allowedUserIdArr = explode(',',$allowedUserId);
        }
        if($repo->{$key} == $currentUserId) {
            //edit item belongs to current user
            return true;
        } else if( count( array_intersect($allowedRoles, $currentUserRole) ) > 0 ) {
            //current user role has full rights to edit
            return true;
        // }
        //  else if( in_array($currentUserId, $teamLeadIds) ) {
        //     //current user is the team leader of the item's owner
        //     return true;

        } else if(sizeof($allowedUserIdArr) > 0 && in_array( $currentUserId, $allowedUserIdArr) ) {
            return true;
        } else {
            //all fail //GET OUT
            return false;
        }
    }

    public static function getUserTeamLeader($userId) {

        $teamLeader = UserOfficialDetail::where('user_id', $userId)->with('userTeam.teamLeaders')->first()->toArray();
        // echo "<pre>";
        // echo "-----------------";
        // print_r($teamLeader);
        // echo "-----------------";
        // print_r($teamLeader['user_team']['team_lead_id']);
        // exit;

        $teamLeaderData = [];

        if(isset($teamLeader['user_team']['team_lead_id'])) {
            $teamLeaderData = (new commonController)->getTeamLeaderByIds(explode(',',$teamLeader['user_team']['team_lead_id']));
        }
        // print_r($teamLeaderData);
        // exit;

        return $teamLeaderData;
    }

    public static function usedWFH($userId)
    {
        $current_year = date("Y");
        $past_year = date("Y",strtotime("-1 year"));
        $next_year = date("Y",strtotime("+1 year"));

        $from_date = $past_year."-04-01";
        $to_date = $current_year."-03-31";

        $current_date_timestamp = strtotime(date('Y-m-d'));
        $last_date_timestamp = strtotime($to_date);

        if($current_date_timestamp <= $last_date_timestamp){
           return WorkFromHome::where('user_id','=',$userId)
            ->where('status','=','approved')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }else{

            $from_date = $current_year."-04-01";
            $to_date = $next_year."-03-31";

            return WorkFromHome::where('user_id','=',$userId)
            ->where('status','=','approved')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }

    }

    public static function rejectedWFH($userId)
    {
        $current_year = date("Y");
        $past_year = date("Y",strtotime("-1 year"));
        $next_year = date("Y",strtotime("+1 year"));

        $from_date = $past_year."-04-01";
        $to_date = $current_year."-03-31";

        $current_date_timestamp = strtotime(date('Y-m-d'));
        $last_date_timestamp = strtotime($to_date);

        if($current_date_timestamp <= $last_date_timestamp){
           return WorkFromHome::where('user_id','=',$userId)
            ->where('status','=','rejected')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }else{

            $from_date = $current_year."-04-01";
            $to_date = $next_year."-03-31";

            return WorkFromHome::where('user_id','=',$userId)
            ->where('status','=','rejected')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }

    }

    public static function cancelledWFH($userId)
    {
        $current_year = date("Y");
        $past_year = date("Y",strtotime("-1 year"));
        $next_year = date("Y",strtotime("+1 year"));

        $from_date = $past_year."-04-01";
        $to_date = $current_year."-03-31";

        $current_date_timestamp = strtotime(date('Y-m-d'));
        $last_date_timestamp = strtotime($to_date);

        if($current_date_timestamp <= $last_date_timestamp){
           return WorkFromHome::where('user_id','=',$userId)
            ->where('status','=','cancelled')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }else{

            $from_date = $current_year."-04-01";
            $to_date = $next_year."-03-31";

            return WorkFromHome::where('user_id','=',$userId)
            ->where('status','=','cancelled')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }

    }

    public static function pendingWFH($userId)
    {
        $current_year = date("Y");
        $past_year = date("Y",strtotime("-1 year"));
        $next_year = date("Y",strtotime("+1 year"));

        $from_date = $past_year."-04-01";
        $to_date = $current_year."-03-31";

        $current_date_timestamp = strtotime(date('Y-m-d'));
        $last_date_timestamp = strtotime($to_date);

        if($current_date_timestamp <= $last_date_timestamp){
           return WorkFromHome::where('user_id','=',$userId)
            ->where('status','=','pending')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }else{

            $from_date = $current_year."-04-01";
            $to_date = $next_year."-03-31";

            return WorkFromHome::where('user_id','=',$userId)
            ->where('status','=','pending')
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }

    }

    public static function totalReqWFH($userId)
    {
        $current_year = date("Y");
        $past_year = date("Y",strtotime("-1 year"));
        $next_year = date("Y",strtotime("+1 year"));

        $from_date = $past_year."-04-01";
        $to_date = $current_year."-03-31";

        $current_date_timestamp = strtotime(date('Y-m-d'));
        $last_date_timestamp = strtotime($to_date);

        if($current_date_timestamp <= $last_date_timestamp){
           return WorkFromHome::where('user_id','=',$userId)
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }else{

            $from_date = $current_year."-04-01";
            $to_date = $next_year."-03-31";

            return WorkFromHome::where('user_id','=',$userId)
            ->select(DB::raw("SUM(duration) as total_duration"))
            ->whereBetween('start_date',[$from_date,$to_date])->first();
        }
    }

    /**
     * for get class name on team wise
     *
     * @param  mixed $user
     * @return void
     */
    public static function getClassName($user)
    {
        $team = $user->officialUser->userTeam->name ?? '';
        $className = 'basic-team';
        if(!empty($team)) {
            $className = str_replace(' ', '-', strtolower($team));
        }
        return $className;
    }

    public static function resourceMgtCount()
    {
        $teams = (new commonController)->getTeams();
        $totalTeam = Team::where('status', 1)->count();
        $totalUsers = User::count();

        $totalProject = Project::whereHas('projectStatus',function($query) {
            $query->where('name', '!=','Closed');
        })->count();
        $completedProject = Project::whereHas('projectStatus',function($query) {
            $query->where('name', 'Closed');
        })->count();
        $resourceMgtArray = [
            "teams" => $teams,
            "totalTeam" => $totalTeam,
            "totalUsers" => $totalUsers,
            "totalProject" => $totalProject,
            "completedProject" => $completedProject
        ];
        return $resourceMgtArray;
    }

    public static function getFinancialYearData($startYear, $endYear = '') {

        if ($endYear == '') {
            $currentYear = date('Y');
            $endYear = $currentYear + 5;
        }

        $financeYearArr = [];

        $startYearZ = $startYear;
        $endYearZ = $startYearZ+1;
        for($i= $startYear; $i <=$endYear; $i++) {
            $financeYearArr[$startYearZ.'-04-01/'.$endYearZ.'-03-31'] = $startYearZ.'-'.$endYearZ;
            $startYearZ++;
            $endYearZ++;
        }

        return $financeYearArr;
    }

    public static function getFinancialStartYearFromDate($startDate) {
        $formatedStartDate = \Carbon\Carbon::parse($startDate);
        $startMonth = $formatedStartDate->format('n');
        if ( $startMonth <= 3 ) {
            return $formatedStartDate->format('Y') - 1;
        }
        else {
            return $formatedStartDate->format('Y');
        }
    }

    public static function getMonthWeekendDays($start_date, $end_date) {

        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);

        $weekendArr = [];
        while (date("Y-m-d", $start_date) != date("Y-m-d", $end_date)) {
            $day_index = date("w", $start_date);
            if ($day_index == 0 || $day_index == 6) {
                // Print or store the weekends here
                array_push($weekendArr, date("d-m-Y",$start_date));
            }
            $start_date = strtotime(date("Y-m-d", $start_date) . "+1 day");
        }

        return $weekendArr;
    }

    // Function to get all the dates in given range
    public static function getDatesRange($start, $end, $format = 'Y-m-d') {

        // Declare an empty array
        $array = array();

        // Variable that store the date interval of period 1 day
        $interval = new \DateInterval('P1D');

        $realEnd = new \DateTime($end);
        $realEnd->add($interval);

        $period = new \DatePeriod(new \DateTime($start), $interval, $realEnd);
        foreach($period as $date) {
            $array[] = $date->format($format);
        }

        return $array;
    }

    public static function getExperience($user)
    {
        $user_experience = (double) (!empty($user->officialUser) && !empty($user->officialUser->experience)) ? $user->officialUser->experience : 0;
        // separate the year and month from value
        $exp_year = intval($user_experience);
        $exp_month = ($user_experience - $exp_year);
        $exp_month = (int) !empty($exp_month) ? substr($exp_month, 2):0;
        // Check the user's joining date:
        $join_date =  !empty($user->officialUser) && !empty($user->officialUser->joining_date) ? \Carbon\Carbon::createFromFormat('d-m-Y', $user->officialUser->joining_date) : '';
        if (!empty($join_date)) {
            $month = $join_date->diffInMonths(now());
            $month += $exp_month;
            $year = intval($month/12);
            $exp_year += $year;
            $exp_month = round($month%12, 2);
        }

        return [
            'year' => $exp_year,
            'month' => $exp_month,
            'total_experience' => $exp_year.'.'.$exp_month,
        ];
    }
}
