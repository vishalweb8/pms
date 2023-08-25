<?php

namespace App\Http\Controllers\Works;

use App\Helpers\Helper;
use App\Http\Controllers\commonController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\HolidayController;
use Illuminate\Http\Request;
use App\Models\DailyTask;
use App\Models\ProjectTaskWorkLog;
use App\Models\User;
use App\Models\UserOfficialDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\UnauthorizedException;

class DefaulterController extends Controller
{
    public function defaulters(Request $request){

        $teams = (new commonController)->getTeams();
        $loggedInUser = Auth::user();
        $myTeamId = isset(Auth::user()->officialUser->userTeam) ? Auth::user()->officialUser->userTeam->id : 0;

        $date_filters = [
            'today' => 'Today',
            'week' => 'This Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'this_year' => 'This Year',
            'last_year' => 'Last Year',
        ];

        // For not show the last year option if current year is 2022
        if(now()->year == "2022") {
            unset($date_filters['last_year']);
        }
        if(\Request::route()->getName() == 'work-log-defaulters') {
            if(!Helper::hasAnyPermission('worklog-defaulters.list')) {
                $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
                return view('errors.403')->with(['exception'=>$exception]);
            }
            $date_filters['today'] = 'Yesterday';
            return view('works.work-log-defaulters', compact('teams', 'date_filters', 'loggedInUser', 'myTeamId'));
        } else {
            return view('works.defaulters', compact('teams', 'date_filters', 'loggedInUser', 'myTeamId'));
        }
    }

    public function getDefaulterData(Request $request)
    {
        if($request->filled('date')) {

            $from_date = now();
            $to_date = now();
            if(isset($request->workLogDefaulter) && $request->workLogDefaulter == 1) {
                $software_start_date = Carbon::parse('2022-03-14');
                $from_date = now()->subDay();
            } else {
                $software_start_date = Carbon::parse('2022-01-31');
            }
            if($request->date == 'week') {
                $from_date = $from_date->startOfWeek(Carbon::MONDAY);
                $to_date = $to_date->endOfWeek(Carbon::SUNDAY);
            }elseif($request->date == 'this_month') {
                $from_date = $from_date->startOfMonth();
                $to_date = $to_date->endOfMonth();
            }elseif($request->date == 'last_month') {
                $from_date = $from_date->subMonth()->startOfMonth();
                $to_date = $to_date->subMonth()->endOfMonth();
            }elseif($request->date == 'this_year') {
                $from_date = $from_date->startOfYear();
                $to_date = $to_date->endOfYear();
            }elseif($request->date == 'last_year') {
                $from_date = $from_date->subYear()->startOfYear();
                $to_date = $to_date->subYear()->endOfYear();
            }

            if($from_date->lessThan($software_start_date)){
                $from_date = $software_start_date;
            }
            if(isset($request->workLogDefaulter) && $request->workLogDefaulter == 1 && !in_array($request->date, ['last_month', 'last_year'])) {
                $to_date = now()->subDay();
            }
            $fromDate = $from_date->format('Y-m-d');
            $toDate  = $to_date->format('Y-m-d');
            $team_users = [];
            if($request->filled('team')) {
                $team_users = UserOfficialDetail::where('team_id', $request->team)->pluck('user_id');
            }
            $userOnLeave = [];
            if(isset($request->workLogDefaulter) && $request->workLogDefaulter == 1 ) {
                $sod_data = ProjectTaskWorkLog::select([
                    DB::raw('group_concat(user_id) as user_id_list'),
                    'log_date',
                    DB::raw("DATE_FORMAT(log_date, '%d-%m-%Y') as 'current_date'"),
                ]);
                $sod_data->where('log_date', '>=', DB::raw("'$fromDate'"));
                $sod_data->where('log_date', '<=', DB::raw("'$toDate'"));
                DB::enableQueryLog();

                $sod_data = $sod_data->groupBy('log_date')->orderBy('log_date')->get();
                $userOnLeave = DailyTask::where('current_date', '>=', DB::raw("'$fromDate'"))
                                ->where('current_date', '<=', DB::raw("'$toDate'"))
                                ->where('emp_status','like','on-leave')->pluck('user_id')->toArray();
                                // dd($userOnLeave);
            }else {
                $sod_data = DailyTask::select([
                    DB::raw('group_concat(user_id) as user_id_list'),
                    'current_date',
                    'verified_by_Admin',
                    'verified_by_TL',
                    'project_status',
                    'emp_status',
                ]);
                // dump($from_date, $to_date); exit();
                DB::enableQueryLog();
                $sod_data->where('current_date', '>=', DB::raw("'$fromDate'"));
                $sod_data->where('current_date', '<=', DB::raw("'$toDate'"));
                $sod_data->whereNotNull('sod_description');
                // if(isset($request->workLogDefaulter) && $request->workLogDefaulter == 1) {
                //     $sod_data->whereNotNull('eod_description')->where('emp_status','like','on-leave');
                // }

                $sod_data = $sod_data->groupBy('current_date')->orderBy('current_date')->get();
            }

            $holiday_list = Helper::totalHolidays();
            $holiday_dates = $holiday_list->pluck('date');

            // dump(DB::getQueryLog()); exit();
            $all_users = User::with(['officialUser.userTeam', 'leaves'])->where('sod_eod_enabled', 1);
            if(!empty($team_users)) {
                $all_users->whereIn('id', $team_users->toArray());
            }

            $all_users = $all_users->get();

            $i=0;
            $result = collect([]);
            $total_days = $from_date->diffInDays($to_date);
            $total_days+=1;

            for ($i=0; $i < $total_days; $i++) {
                if($i>0) {
                    $from_date->addDay();
                }
                // Check for the date is weekend
                if(in_array($from_date->format('l'), ['Saturday', 'Sunday'])) {
                    continue;
                }
                // Check for the date is holiday
                if(in_array($from_date->format('d-m-Y'), $holiday_dates->toArray())) {
                    continue;
                }
                $result[$i] = [
                    'current_date' => $from_date->format('d-m-Y'),
                    'defaulters'=> ''
                ];

                if($from_date->format('d-m-Y') == now()->format('d-m-Y')) {
                    break;
                }
            }
            if($request->filled('return_data_as') && $request->return_data_as == 'email_content') {
                return view('works.partials.defaulter-email-row', compact('sod_data', 'result', 'all_users','userOnLeave'));
            }
            return view('works.partials.defaulter-row', compact('sod_data', 'result', 'all_users','userOnLeave'));
        }
        return false; // Default return blank as html;
    }
}
