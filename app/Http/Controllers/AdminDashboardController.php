<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use App\Models\UserProjects;
use App\Models\UserOfficialDetail;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use App\Helpers\Helper;
use App\Models\TimeEntry;
use Session;
use Auth;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

        return view('dashboard.admin-dashboard', compact('teams', 'totalTeam', 'totalUsers', 'totalProject', 'completedProject'));
    }

    public function adminEmployeeList(Request $request)
    {

        $allEmployees = User::with(['officialUser.userTeam','userProjects.project.projectStatus','userProjects' => function ($query) {
            $query->whereHas('project.projectStatus', function ($qry) {
                $qry->where('name', '<>', 'closed');
                $qry->OrWhere('name', '<>', 'Closed');
            });
        }]);

        if (!empty(request()->team_id) ) {
            $allEmployees = $allEmployees->whereHas('officialUser', function ($qry) {
                $qry->where('team_id', '=', request()->team_id);
            });
        }
        if (isset(request()->type)) {
            if(request()->type == 'On Hold'){
                $allEmployees = $allEmployees->whereHas('userProjects.project.projectStatus', function ($qry) {
                    $qry->where('name', 'like','%'.request()->type .'%');
                });
            } else if(request()->type == 'no_project') {
                $allEmployees = $allEmployees->doesnthave('userProjects.project');
            } else {
                $allEmployees = $allEmployees->whereHas('userProjects.project', function ($qry) {
                    $qry->where('project_type',request()->type);
                });
            }
        }

        $allEmployees = $allEmployees->get();

        $employees = '';
        $non_assigned_project = '';
        $assigned_project = '';
        if ($request->ajax()) {
            if (($allEmployees->count() > 0)) {
                foreach ($allEmployees as $employee) {
                    $team_name = '';
                    if(!empty($employee->officialUser) && isset($employee->officialUser->userTeam) && !empty($employee->officialUser->userTeam) && !empty($employee->officialUser->userTeam->name)) {
                        $team_name = $employee->officialUser->userTeam->name;
                    }
                    $employees .= '<tr><td class="avtar-td">'.userNameWithIcon($employee, $team_name).'</td>';

                    if (!empty($employee->userProjects) && $employee->userProjects->count() > 0) {
                        $projects = $employee->userProjects->sortByDesc('project.project_type');
                        $assigned_project .= '<td><div>';
                        if(request('type') != 'On Hold' && request('type') != ''){
                            $projects = $projects->where('project.project_type',request()->type);
                        }
                        foreach ($projects as $projectDetail) {
                            $projectStatus = (($projectDetail->project) ? (strtolower($projectDetail->project->projectStatus->name)) : '');
                            if((request('type') == 'On Hold' && $projectStatus != 'on hold') || ($projectStatus == 'on hold' && (request('type') == "1" || request('type') === "0"))){
                                info(request('type'));
                                info($projectStatus);
                                continue;
                            }
                            $project = $projectDetail->projectList->first();
                            $project_class_list = 'badge font-size-12 font-color-black ';
                            $project_class_list .= (!empty($projectStatus) ? (($projectStatus == 'on hold') ? "bg-hold" : (($project['project_type'] == 1) ? "bg-external" : ($project['project_type'] == 0  ? "bg-internal ": ''))) : '');
                            $project_type = ($projectStatus  == 'on hold') ? 'On Hold' : ($project['project_type']== 1 ? "External" : "Internal");
                            $assigned_project .= '<a target="_blank" href="'.route('super-admin-project-activity',$project->id).'"><span  class="'.$project_class_list.'" title="'.$project_type.'">' . $project['project_code'] . ' - ' . $project['name'] . '</span></a>';
                        }
                        $assigned_project .= '</div></td>';
                    } else {
                        $projects = [];
                        $non_assigned_project .= '<td><div><span class="badge bg-danger font-size-12">
                                                        No Project Assigned
                                                        </span></div></td>';
                    }

                    $employees .= (!empty($projects)) ? $assigned_project . '</tr>' : $non_assigned_project . '</tr>';
                    $non_assigned_project = '';
                    $assigned_project = '';
                }
            }

            return $employees;
        }
    }

    public function adminEmployeeExp(Request $request)
    {

        $allEmployees = User::with('officialUser.userTeam');

        if (!empty(request()->team_id) ) {
            $allEmployees = $allEmployees->whereHas('officialUser', function ($qry) {
                $qry->where('team_id', '=', request()->team_id);
            });
        }

        $allEmployees = $allEmployees->get();

        $employees = '';

        if ($request->ajax()) {
            if (($allEmployees->count() > 0)) {
                foreach ($allEmployees as $employee) {
                    $team_name = '';
                    if(isset($employee->officialUser->userTeam) && !empty($employee->officialUser->userTeam) && !empty($employee->officialUser->userTeam->name)) {
                        $team_name = $employee->officialUser->userTeam->name;
                    }
                    $employees .= '
                        <tr>
                            <td class="avtar-td">

                                <!-- <img src="/public/images/users/avatar-1.jpg" class="rounded-circle avatar-sm" alt="" /> -->';
                                if (!empty($employee['profile_image']) && Storage::exists("public/upload/user/images/". $employee['profile_image'])){
                                    $employees .= ' <img src="'. asset("/storage/upload/user/images/" . $employee['profile_image']) .'" class="rounded-circle avatar-xs" alt="" /> ';
                                } else {
                                    $employees .= '<div class="avatar-xs">
                                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-12">
                                                            ' . $employee->profile_picture . '
                                                        </span>
                                                    </div>';
                                }
                                $employees .= '<div class="ps-2 name-info text-truncate">
                                    <h5 class="font-size-14 m-0 pl-3 text-truncate fw-bold">
                                        ' . $employee->full_name . '
                                    </h5>
                                    <span class="font-size-12 text-muted pl-3">
                                        ' . $team_name ?? '' . '
                                    </span>
                                </div>
                            </td>';

                    if ($employee->officialUser) {
                        $experiences = '<td>';
                        $user_experiance = (double) (!empty($employee->officialUser) && !empty($employee->officialUser->experience)) ? $employee->officialUser->experience : 0;
                        // separate the year and month from value
                        $exp_year = intval($user_experiance);
                        $exp_month = ($user_experiance - $exp_year);
                        $exp_month = (int) !empty($exp_month) ? substr($exp_month, 2):0;
                        // Check the user's joining date:
                        $join_date = !empty($employee->officialUser->joining_date) ? \Carbon\Carbon::createFromFormat('d-m-Y', $employee->officialUser->joining_date) : '';
                        if (!empty($join_date)) {
                            $month = $join_date->diffInMonths(now());
                            $month += $exp_month;
                            $year = intval($month/12);
                            $exp_year += $year;
                            $exp_month = round($month%12, 2);

                        }
                        $experiences .= __("{$exp_year} year(s) and {$exp_month} month(s)").'</td>';
                    } else {
                        $experiences = '<td><div><span class="badge bg-danger font-size-12">
                                                        Data not available
                                                        </span></div></td>';
                    }
                    if ($employee->officialUser && !empty($employee->officialUser->technologies_ids)) {
                        $technologies = (new commonController)->getTechnology(explode(',',$employee->officialUser->technologies_ids));

                        $knownTechnologies = '<td><div>';
                        foreach ($technologies as $technology) {
                            $class_list = 'badge font-size-12 font-color-black bg-external';
                            $knownTechnologies .= '<span  class="'.$class_list.'" title="'.$technology.'">' . $technology . '</span>';
                        }
                        $knownTechnologies .= '</div></td>';
                    } else {
                        $knownTechnologies = '<td><div><span class="badge bg-danger font-size-12">
                                                        Data not available
                                                        </span></div></td>';
                    }

                    $employees .= $experiences . $knownTechnologies . '</tr>';
                }
            }

            return $employees;
        }
    }

    public function adminProjectList(Request $request)
    {
        $allProjects = Project::with(['projectUser', 'projectStatus', 'billable_resources'])
            ->where('project_type',1);

        $allProjects = $allProjects->whereHas('projectStatus', function ($query) {
            $query->where('name', '<>', 'closed');
            $query->OrWhere('name', '<>', 'Closed');
        });

        if ($request->filled('project')) {
            $allProjects = $allProjects->where('projects.name', 'LIKE', '%' . request()->project . '%');
        }
        $allProjects = $allProjects->orderBy("projects.id", 'desc')->get();

        $employee_projects = '';
        $employee_details = '';
        if ($request->ajax()) {
            if (($allProjects->count() > 0)) {
                foreach ($allProjects as $project) {
                    $billable_resource = '';

                    $employee_projects .= '<tr>
                    <td>
                        <div>
                            <h5 class="font-size-14 fw-bold">
                                ' . $project->project_code . ' - ' . $project->name . '
                            </h5>

                        </div>
                    </td>';
                    $employee_projects .= '<td class="billable-resource"><div class="avtar-td d-flex flex-wrap">';

                    if ($project->billable_resources->count() > 0) {
                        foreach ($project->billable_resources as $user) {
                            $team_name = '';
                            if(!empty($user->officialUser) && isset($user->officialUser->userTeam) && !empty($user->officialUser->userTeam) && !empty($user->officialUser->userTeam->name)) {
                                $team_name = $user->officialUser->userTeam->name;
                            }
                            $billable_resource .= userNameWithIcon($user, $team_name);
                        }
                    } else {
                        $billable_resource .= '<span class="badge bg-danger font-size-12">
                                No Billable Resource
                            </span>';
                    }
                    $employee_projects .= $billable_resource . '</div></td>';

                    $employee_projects .= '<td class="employee"><div class="avtar-td d-flex flex-wrap justify-content-start">';

                    if ($project->projectUser->count() > 0) {
                        $billableResIds = $project->billable_resources->pluck('id')->toArray();
                        foreach ($project->projectUser as $user) {
                            if(in_array($user->id,$billableResIds)) {
                                continue;
                            }
                            $team_name = '';
                            if(!empty($user->officialUser) && isset($user->officialUser->userTeam) && !empty($user->officialUser->userTeam) && !empty($user->officialUser->userTeam->name)) {
                                $team_name = $user->officialUser->userTeam->name;
                            }
                            $employee_details .= userNameWithIcon($user, $team_name);
                        }
                    } else {
                        $employee_details .= '<span class="badge bg-danger font-size-12">
                            No Non-Billable Resource
                        </span>';
                    }
                    $employee_projects .= $employee_details . '</div></td>';
                    $employee_details .= '</tr>';
                    $employee_details = '';
                }
            }
            return $employee_projects;
        }
    }

    public function adminProjectBDEList(Request $request) {

        $allUsers = User::withBlocked()->with('bdeProjects')
                        ->has('officialUser.userTeam')->wherehas('officialUser.userTeam', function($query) {
                            $query->where('name', "like",  'Sales%');
                        });
        if($request->filled('project')) {
            $allUsers = $allUsers->where(function ($query) {
                                $query->where('first_name', 'LIKE', '%' . request()->project . '%')
                                ->orwhere('last_name', 'LIKE', '%' . request()->project . '%')
                                ->orwhere('full_name', 'LIKE', '%'. request()->project . '%');
                            });

        }

        $allUsers = $allUsers->get();

        // dump($allUsers); exit();

        $employee_projects = '';
        $employee_details = '';
        if ($request->ajax()) {
            if (($allUsers->count() > 0)) {
                foreach ($allUsers as $user) {
                    $team_name = '';
                    if(!empty($user->officialUser) && isset($user->officialUser->userTeam) && !empty($user->officialUser->userTeam) && !empty($user->officialUser->userTeam->name)) {
                        $team_name = $user->officialUser->userTeam->name;
                    }

                    $employee_projects .= '<tr><td  class="avtar-td">'.userNameWithIcon($user, $team_name).'</td>';

                    $employee_details .= '<td><div>';
                    if (isset($user->bdeProjects) && sizeof($user->bdeProjects) > 0) {
                        $user->bdeProjects = $user->bdeProjects->map(function ($value) {
                            $value['sort_order'] = (!empty($value->projectStatus) && !in_array($value->projectStatus->name, ['closed', 'Closed'])) ? 0 : 1;
                            return $value;
                        })->sortBy('sort_order');

                        foreach ($user->bdeProjects as $project) {
                            $project_class_list = 'badge font-size-12 font-color-black ';
                            if(!empty($project->projectStatus)) {
                                $project_class_list .= ($project->projectStatus->name == 'closed' || $project->projectStatus->name == 'Closed') ? "bg-internal" : 'bg-external';
                            }
                            $project_type = (!empty($project->projectStatus) && !in_array($project->projectStatus->name, ['closed', 'Closed'])) ? "Open Project" : "Closed Project";
                            $employee_details .= '<span  class="'.$project_class_list.'" title="'.$project_type.'">' . $project->project_code . ' - ' . $project->name . '</span>';
                        }
                    } else {
                        $employee_details .= '<span class="badge bg-danger font-size-12">
                                No Current Project
                            </span>';
                    }
                    $employee_details .= '</div>';

                    $employee_projects .= $employee_details . '</td></tr>';
                    $employee_details = '';
                }
            }
            return $employee_projects;
        }
    }

    public function adminAllTeam(Request $request) {

        $allEmployees = Team::with(['userOfficialDetail']);

        if($request->filled('team')) {
            $allEmployees = $allEmployees->where(function ($query) {
                                $query->where('name', 'LIKE', '%' . request()->team . '%');
                            });

        }

        $allEmployees = $allEmployees->groupBy('id')->get();

        $employees = '';
        if ($request->ajax()) {
            if (($allEmployees->count() > 0)) {
                foreach ($allEmployees as $employee) {
                    $employees .= '
                            <tr>
                                <td class="avtar-td">';

                                    $employees .= '<div class="avatar-xs">
                                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-12">
                                                            ' . $employee->userOfficialDetail->count() . '
                                                        </span>
                                                    </div>';
                                $employees .= '<div class="ps-2 name-info text-truncate">
                                    <h5 class="font-size-14 m-0 pl-3 text-truncate fw-bold">
                                        ' . $employee->name . '
                                    </h5>
                                </div>
                            </td>';

                    if (!empty($employee->userOfficialDetail) && $employee->userOfficialDetail->count() > 0) {
                        $employees .= '<td class=""><div class="w-auto avtar-td"><div class="d-flex flex-wrap">';
                        foreach ($employee->userOfficialDetail as $userInfo) {
                            $user = $userInfo->userOfficial;
                            $employees .= userNameWithIcon($user);

                        }
                        $employees .= '</div></div></td>';
                    } else {
                        $projects = [];
                        $employees .= '<td><div><span class="badge bg-danger font-size-12">
                                                        No Member Assigned
                                                        </span></div></td>';
                    }

                    $employees .= '</tr>';
                }
            }

            return $employees;
        }
    }


    function adminMonthlyWorkLog(Request $request) {
        $requestData = $request->all();
        $teamID = 0;
        $today = Carbon::now(); //Current Date and Time

        $taskMonth = $today->month;
        $taskYear = $today->year;

        if(Session::get('selectedTabWorklog') == '1'){
            if (isset($requestData['teamID']) && $requestData['teamID'] != '') {
                $teamID = $requestData['teamID'];
            }
        }

        if (isset($requestData['taskMonth']) && $requestData['taskMonth'] != '') {
            $taskMonth = $requestData['taskMonth'];
        }

        if (isset($requestData['taskYear']) && $requestData['taskYear'] != '') {
            $taskYear = $requestData['taskYear'];
        }
        $today = $taskYear.'-'.$taskMonth.'-01';

        $from_date = Carbon::parse($today)->startOfMonth();
        $to_date = Carbon::parse($today)->endOfMonth();
        if(Session::get('selectedTabWorklog') == '2'){
            $from_date = (!empty($requestData['startDate']) ? Carbon::parse($requestData['startDate']): Carbon::parse(Carbon::today()));
            $to_date = (!empty($requestData['endDate']) ? Carbon::parse($requestData['endDate']) : Carbon::parse(Carbon::today()));
        }
        $period = CarbonPeriod::create($from_date, $to_date);
        $range = [];
        foreach ($period as $date) {
            $range[$date->format('d-m-Y')] = [];
        }
        $workRepo = User::withBlocked();
        if($request->filled('userType') && $request->userType == "active") {
            $workRepo = User::select();
        }

        // with('workLogData.logProjectTask.project')
        $workRepo = $workRepo->with(['workLogData' => function ($query) use($from_date, $to_date) {
            $query->select('id', 'user_id', 'description', 'task_id', 'log_time',DB::raw("DATE_FORMAT(log_date, '%d-%m-%Y') as log_date_f"))
                ->whereDate('log_date', '>=', $from_date)
                ->whereDate('log_date', '<=', $to_date)
                ->withCasts([
                    'log_time' => 'double']);
        },
        'workFromHome' => function($q) use($from_date, $to_date) {
            $q->select('id', 'user_id','start_date', 'end_date', 'wfh_type')
            ->where('status', '!=', 'cancelled')
            ->where('start_date', '>=', $from_date)
            ->orWhere(function ($qry) use($from_date, $to_date) {
                $qry->where('start_date', '<=', $from_date)
                ->where('end_date', '<=', $to_date);

            });
        }, 'workLogData.logProjectTask.project', 'teamLeaders'])
        ->withcount(['tasks as monthly_hour' => function ($query) use($from_date, $to_date) {
            $query->select(DB::raw("COALESCE(SUM(log_time), 0) as monthly_hour"), 'start_date')
            ->whereDate('log_date', '>=', $from_date)
            ->whereDate('log_date', '<=', $to_date);
        }])
        ->with(['officialUser.userTeam', 'leaves' => function($qz) use($from_date, $to_date) {
            $qz->select('id', 'request_from','start_date', 'end_date', 'type', 'status')
            ->where('start_date', '>=', $from_date->format('Y-m-d'))
            ->orWhere(function ($qry) use($from_date, $to_date) {
                $qry->where('start_date', '<=', $from_date->format('Y-m-d'))
                ->where('end_date', '<=', $to_date->format('Y-m-d'));

            });
            // ->where('end_date', '<=', $to_date->format('Y-m-d'));
        }])

        ->wherehas('officialUser.userTeam', function($qq) use($teamID) {
            if ($teamID != '') {
                $qq->where('id', $teamID);
            }
        });
        if(Auth::user()->roles[0]->type == "EMP") {
            $workRepo->WhereHas('teamLeaders', function ($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }

        // ->wherehas('officialUser', function($qq) use($to_date) {
        //         $qq->whereDate('joining_date', '<=', $to_date);
        // });
        // ->has('officialUser.userTeam')->wherehas('officialUser.userTeam', function($query) use ($teamID) {
        //     if ($teamID != '') {
        //         $query->where('id', $teamID);
        //     }
        // });

        if($request->filled('user')) {
            $workRepo = $workRepo->where(function ($query) {
                        $query->where('first_name', 'LIKE', '%' . request()->user . '%')
                        ->orwhere('last_name', 'LIKE', '%' . request()->user . '%')
                        ->orwhere('full_name', 'LIKE', '%'. request()->user . '%');
                    });
        }

        $workRepo = $workRepo->whereHas('roles', function ($query) {
            $query->whereNotIn("type", ['ADMIN']);
        });

        if(Session::get('selectedTabWorklog') == '2' && !empty($requestData['employee'])){
            $workRepo = $workRepo->whereIn('id',explode(',',$requestData['employee']));
        }
        // $users = $workRepo->get()->pluck('full_name','id')->toArray();
        $workRepo = $workRepo->get();
        $weekendList = Helper::getMonthWeekendDays($from_date, $to_date);


        foreach ($workRepo as $key => $userData) {
            $leaveData = [];
            $mastred = [];
            if(isset($userData['leaves'])) {
                foreach($userData['leaves'] as $leaveItem) {
                    if(isset($leaveItem->id) && $leaveItem->status != 'cancelled') {
                        $leaveData = Helper::getDatesRange($leaveItem->start_date, $leaveItem->end_date, 'd-m-Y');
                        foreach ($leaveData as $keyZ => $leaveValue) {
                            $mastred[$leaveValue] = array(
                                'leave_date' => $leaveValue,
                                'type' => $leaveItem->type,
                                'status' => $leaveItem->status
                            );
                        }
                    }
                }
            }
            $workRepo[$key]['leaveData'] = $mastred;
        }


        foreach ($workRepo as $key => $userWork) {

            if($userWork->is_blocked && $userWork->workLogData->isEmpty()) {
                unset($workRepo[$key]);
                continue;
            }

            $tL = [];
            if(isset($userWork['workLogData'])) {

                foreach($userWork['workLogData'] as $logItem) {
                    $tL[$logItem['log_date_f']][] = $logItem;
                }
            }
            $tL = array_replace($range, $tL);
            $workRepo[$key]['workLogData'] = collect($tL);

        }
        foreach ($workRepo as $key => $user) {
            $wfhData = [];
            foreach($user['workFromHome'] as $wfh) {
                $dateRange = Helper::getDatesRange($wfh->start_date, $wfh->end_date, 'd-m-Y');
                foreach ($dateRange as $keyZ => $value) {
                    $wfhData[$value] = array(
                        'wfh_date' => $value,
                        'type' => $wfh->wfh_type
                    );
                }
            }
            $workRepo[$key]['wfhData'] = $wfhData;
        }

        $rangeHTML = '';
        foreach ($period as $date) {
            $rangeHTML .= '<th class="date-cell"><div><span>'.$date->format('D'). '</span> <span>' .$date->format('d') . '</span></div></th>';
        }

        $publicHoliday = Holiday::whereDate('date', ">=",$from_date)->whereDate('date', "<=",$to_date)->pluck('date','id')->toArray();

        // echo "<pre>";
        // echo $from_date;
        // echo $to_date;
        // print_r($publicHoliday);
        // print_r($weekendList);
        // exit;
        if(Session::get('selectedTabWorklog') == '2' && empty($requestData['endDate']) &&  empty($requestData['startDate']))
        {
            $workRepo = [];
            $rangeHTML = '';
        }

        $returnHTML = view('resource-managment.monthly-worklog-record')->with(compact('workRepo','weekendList', 'publicHoliday'))->render();
        return ['returnHTML'=>$returnHTML, 'rangeHTML'=>$rangeHTML];
    }

    /**
     * for get data of filter project
     *
     * @param  mixed $request
     * @return void
     */
    public function adminFilteredWorkLog(Request $request)
    {
        try {

            $from = (!empty($request->startDate)) ? Carbon::parse($request->startDate) : today()->startOfMonth();
            $to = (!empty($request->endDate)) ? Carbon::parse($request->endDate) : today()->endOfMonth();
            $from = $from->format('Y-m-d');
            $to = $to->format('Y-m-d');
            $users =  User::basicColumns()->withBlocked()->with(['workLogData' => function ($query) use($from, $to,$request) {
                $query->select('task_id',
                        'project_task_work_logs.id',
                        'project_task_work_logs.user_id',
                        'project_tasks.name as task_name',
                        \DB::raw("sum(log_time) as task_total_time"),
                        \DB::raw("(select name from projects where id = project_tasks.project_id) as project_name")
                    )
                    ->leftJoin("project_tasks",function($join) {
                        $join->on('project_tasks.id', '=', 'project_task_work_logs.task_id');
                    })
                    ->when($request->projects, function ($q) use($request) {
                        return $q->where('project_tasks.project_id',$request->projects);
                    })
                    ->whereBetween('log_date', [$from,$to])
                    ->groupBy('project_task_work_logs.user_id','project_tasks.project_id','project_task_work_logs.task_id')
                    ;
            },'officialUser.userTeam'])
            ->whereHas('roles', function ($query) {
                $query->whereNotIn("type", ['ADMIN']);
            });

            if($request->filled('employee')) {
                $users->whereIn('users.id',explode(',',$request->employee));
            }
            if($request->filled('teamID')) {
                $teamId = $request->teamID;
                $users->wherehas('officialUser', function($q) use($teamId) {
                    $q->where('team_id', $teamId);
                });
            }
            if($request->filled('projects')) {
                $projects = $request->projects;
                $users->wherehas('workLogData.logProjectTask', function($q) use($projects) {
                    $q->where('project_id', $projects);
                });
            }

            $users = $users->orderBy('first_name')->get();
            $returnHTML = view('resource-managment.filtered-worklog-record',compact('users'))->render();
            return ['returnHTML'=>$returnHTML];
        } catch (\Throwable $th) {
            \Log::error('Getting error while getting filterd worklog data:- '.$th);
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    /**
     * for get time entry of all employee monthly wise
     *
     * @param  mixed $request
     * @return void
     */
    public function monthlyTimeEntry(Request $request) {
        try {
            $teamID = 0;
            $today = Carbon::now(); //Current Date and Time

            $month = $today->month;
            $year = $today->year;

            if ($request->filled('teamID')) {
                $teamID = $request->teamID;
            }

            if ($request->filled('month')) {
                $month = $request->month;
            }

            if ($request->filled('year')) {
                $year = $request->year;
            }

            $today = $year.'-'.$month.'-01';


            $from_date = Carbon::parse($today)->startOfMonth()->format('Y-m-d');
            $to_date = Carbon::parse($today)->endOfMonth()->format('Y-m-d');
            $period = CarbonPeriod::create($from_date, $to_date);

            $users = User::select('id','first_name','last_name','full_name','profile_image','status');
            if($request->filled('userType') && $request->userType == "all") {
                $users->withBlocked();
            }
            $users->with([
                'leaves' => function($q) use($from_date, $to_date) {
                    $q->select('id', 'request_from','start_date', 'end_date', 'type')
                    ->where('status', '!=', 'cancelled')
                    ->where('start_date', '>=', $from_date)
                    ->orWhere(function ($qry) use($from_date, $to_date) {
                        $qry->where('start_date', '<=', $from_date)
                        ->where('end_date', '<=', $to_date);

                    });
                },
                'workFromHome' => function($q) use($from_date, $to_date) {
                    $q->select('id', 'user_id','start_date', 'end_date', 'wfh_type')
                    ->where('status', '!=', 'cancelled')
                    ->where('start_date', '>=', $from_date)
                    ->orWhere(function ($qry) use($from_date, $to_date) {
                        $qry->where('start_date', '<=', $from_date)
                        ->where('end_date', '<=', $to_date);

                    });
                },
                'officialUser.timeEntry' => function($q) use($from_date, $to_date) {
                    $q->whereBetween('LogDateTime',[$from_date,$to_date]);
                },
                'teamLeaders'
            ]);
            if(Auth::user()->roles[0]->type == "EMP") {
                $users->WhereHas('teamLeaders', function ($q) {
                    $q->where('user_id', Auth::user()->id);
                });
            }

            if ($teamID) {
                $users->wherehas('officialUser', function($q) use($teamID) {
                    $q->where('team_id', $teamID);
                });
            }

            if($request->filled('user')) {
                $users->where(function ($query) {
                    $query->where('first_name', 'LIKE', '%' . request()->user . '%')
                    ->orwhere('last_name', 'LIKE', '%' . request()->user . '%')
                    ->orwhere('full_name', 'LIKE', '%'. request()->user . '%');
                });
            }

            $users = $users->get();


            foreach ($users as $key => $user) {
                $leaves = [];
                foreach($user['leaves'] as $leaveItem) {
                    $leaveData = Helper::getDatesRange($leaveItem->start_date, $leaveItem->end_date, 'Y-m-d');
                    foreach ($leaveData as $keyZ => $leaveValue) {
                        $leaves[$leaveValue] = array(
                            'leave_date' => $leaveValue,
                            'type' => $leaveItem->type
                        );
                    }
                }
                $users[$key]['leaveData'] = $leaves;
            }

            foreach ($users as $key => $user) {
                $wfhData = [];
                foreach($user['workFromHome'] as $wfh) {
                    $dateRange = Helper::getDatesRange($wfh->start_date, $wfh->end_date, 'Y-m-d');
                    foreach ($dateRange as $keyZ => $value) {
                        $wfhData[$value] = array(
                            'wfh_date' => $value,
                            'type' => $wfh->wfh_type
                        );
                    }
                }
                $users[$key]['wfhData'] = $wfhData;
            }

            $rangeHTML = '';
            $range = [];
            foreach ($period as $date) {
                $rangeHTML .= '<th class="date-cell"><div><span>'.$date->format('D'). '</span> <span>' .$date->format('d') . '</span></div></th>';

                $range[] =  $date->format('Y-m-d');
            }

            $weekendList = Helper::getMonthWeekendDays($from_date, $to_date);
            foreach($weekendList as $key => $weekend) {
                $weekendList[$key] = Carbon::parse($weekend)->format('Y-m-d');
            }
            $publicHoliday = Holiday::select('id',\DB::raw("DATE_FORMAT(date, '%d-%m-%Y') as date"))->whereDate('date', ">=",$from_date)->whereDate('date', "<=",$to_date)->pluck('date','id')->toArray();

            $returnHTML = view('resource-managment.time-entry-record')->with(compact('users','weekendList', 'publicHoliday','range'))->render();
            return ['returnHTML'=>$returnHTML, 'rangeHTML'=>$rangeHTML];
        } catch (\Throwable $th) {
            \Log::error('Getting error while getting month time entry:- '.$th);
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

}
