<?php

namespace App\Http\Controllers\Works;

use App\DataTables\DailyTaskManagementAdminDatatable;
use App\DataTables\DailyTaskManagementDatatable;
use App\Events\ManageSodEodDate;
use App\Http\Controllers\commonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyTask;
use App\Models\Project;
use App\Models\Team;
use App\Models\UserProjects;
use Auth;
use Exception;
use DB;
use App\Helpers\Helper;
use App\Models\User;
use App\Models\UserOfficialDetail;
use App\Models\FreeResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;
use Spatie\Permission\Exceptions\UnauthorizedException;

class DailyTaskManagementController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->module_title = "All Employee";
    }

    // For Employee
    public function index(DailyTaskManagementDatatable $dataTable, Request $request)
    {
        $date = ($request->ajax()) ? $request->date : Carbon::now()->format('Y-m-d');
        $joinDate = UserOfficialDetail::where('user_id',Auth::user()->id)->select('joining_date')->first();
        $isLeave = ($request->ajax()) ? $request->isLeave : false;
        $isWeekend = ($request->ajax()) ? $request->isWeekend : false;
        $dailyTaskForUser = DailyTask::where('user_id', Auth::user()->id)->where('current_date', 'LIke', $date . '%')->first();
        // $dailyTaskForUser = DailyTask::where('user_id', Auth::user()->id)->latest('id')->first();
        if ($request->ajax()) {
            $returnHTML = view("works.partials.daily-task-list", compact(['dailyTaskForUser','date', 'isLeave','isWeekend','joinDate']))->render();
            $data['html'] = $returnHTML;
            return response()->json(['message' => 'Data Fetch Successfully ', 'data' => $data], 200);
        } else {
            view()->share('module_title', 'my sod');
            return $dataTable->render('works.index', compact(['dailyTaskForUser', 'date', 'isLeave','isWeekend','joinDate']));
        }
    }

    public function create($date = null)
    {
        $projects = [];
        $userId = Auth::user();
        if (Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {
            $projects = Project::with(['projectStatus'])->whereHas('projectStatus', function ($query) {
                $query->where('name', '<>', 'closed');
                $query->OrWhere('name', '<>', 'Closed');
            })->get()->toArray();
        } else {
            $projectdata = UserProjects::with(['projectList', 'projectList.projectStatus'])
                    ->where('user_id', $userId->id)->where('status', 1)
                    ->whereHas('projectList.projectStatus', function ($query) {
                        $query->where('name', '<>', 'closed');
                        $query->OrWhere('name', '<>', 'Closed');
                    })
                    ->get(['project_id'])->toArray();
            foreach ($projectdata as $key => $value) {
                $projects[] = $value['project_list'][0];
            }
        }
        return view('works.create', compact('projects', 'date'));
    }

    public function edit($id)
    {
        $projects = [];
        if (Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {
            $projects = Project::with(['projectStatus'])->whereHas('projectStatus', function ($query) {
                $query->where('name', '<>', 'closed');
                $query->OrWhere('name', '<>', 'Closed');
            })->get()->toArray();
        } else {
            $projectdata = UserProjects::with(['projectList', 'projectList.projectStatus'])
            ->where('user_id', Auth::id())->where('status', 1)
            ->whereHas('projectList.projectStatus', function ($query) {
                $query->where('name', '<>', 'closed');
                $query->OrWhere('name', '<>', 'Closed');
            })
            ->get(['project_id'])->toArray();
            foreach ($projectdata as $key => $value) {
                $projects[] = $value['project_list'][0];
            }
        }
        $dailyTask = DailyTask::find($id);
        if(!Helper::hasAccess($dailyTask, 'user_id')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        return view('works.create', compact(['dailyTask', 'projects']));
    }

    public function editDailyTask($id)
    {
        if( !Auth::user()->hasRole('Super Admin') ) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        $projects = [];
        if (Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {
            $projects = Project::get()->toArray();
        } else {
            $projectdata = UserProjects::where('user_id', Auth::id())->where('status', 1)->with('projectList')->get(['project_id'])->toArray();
            foreach ($projectdata as $key => $value) {
                $projects[] = $value['project_list'][0];
            }
        }
        $dailyTask = DailyTask::with('userTask')->find($id);
        return view('works.partials.edit-daily-task', compact(['dailyTask', 'projects']));
    }

    public function adminIndex(DailyTaskManagementAdminDatatable $dataTable)
    {
        if( ! Helper::hasAnyPermission('daily-tasks-all-emp.list') && (count((new commonController)->getMentorUsers())) < 0) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        // For check the role and team of loggedIn user
        $loggedInUser = Auth::user()->load('officialUser.userTeam');
        $myTeamId = isset($loggedInUser->officialUser->userTeam) ? $loggedInUser->officialUser->userTeam->id : 0;

        // Select today's date for date filter
        $today = today()->format('d-m-Y');

        // For get daily tasks
        $dailyTask = DailyTask::with('userTask', 'userTask.officialUser.userTeam')->get();
        $teams = (new commonController)->getTeams();
        $projects = (new commonController)->getProjects();
        view()->share('module_title', 'all employees');
        return view('works.admin_index', compact('dailyTask', 'teams', 'myTeamId','today','loggedInUser','projects'));
    }

    /**
     * For verify sod eod tasks by admin
     *
     * @param Request $request
     * @return json
     * @date 08-09-2021 
     * @author Ravi Chauhan <ravichauhan.inexture@gmail.com> 
     */
    public function taskVerifiedByAdmin(Request $request)
    {
        try {
            if ($request->ajax()) {
                $input = $request->except('_token');
                $task = DailyTask::updateOrCreate(['id' => $request->id], $input);
                return response()->json($task);
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * This method is used for store SOD details of loggedIn user
     *
     * @param Request $request
     * @return void
     * @date 08-09-2021 
     * @author Ravi Chauhan <ravichauhan.inexture@gmail.com> 
     */
    public function addTask(Request $request)
    {
        try {
            $input = $request->except('_token');
            if (empty($input['id'])) {
                $msg = trans('messages.MSG_SUCCESS',['name' => 'Daily Task Detail']);
                $input['current_date'] = ($request->sod_eod_date != null) ? Carbon::parse($request->sod_eod_date) : Carbon::now();
            } else {
                $input['sod_description'] = !empty($input['sod_description']) ? $input['sod_description'] : NULL;
                $msg = trans('messages.MSG_UPDATE',['name' => 'Daily Task Detail']);
            }
            $entry = DailyTask::updateOrCreate(['id' => $input['id']], $input);
            if(($entry->sod_description && $entry->current_date == now()->format('d-m-Y') ) ||
            ($entry->sod_description && $entry->current_date != now()->format('d-m-Y') )) {
                ManageSodEodDate::dispatch('remove', $input['user_id'], $entry->current_date);
            }
            return redirect()->route('work-index')->with('msg', $msg);
        } catch (Exception $e) {
            return redirect()->route('work-index')->with('error', $e->getMessage());
        }
    }

    public function editDailyTaskStore(Request $request)
    {
        $loggedInUser = Auth::user();
        $loggedInUser->load('officialUser.userTeam');
        $team_leaders = [];
        if(!empty($loggedInUser->userTeam)) {
            $team_leaders = explode(',',$loggedInUser->userTeam->team_lead_id);
        }

        $has_permission_to_edit = Helper::hasAnyPermission('super-admin-project.edit');
        $has_permission_to_edit = !$has_permission_to_edit ? in_array(Auth::id(), $team_leaders) : $has_permission_to_edit;

        if(!$has_permission_to_edit) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        try {
            $input = $request->except('_token', 'task_entry_date', 'user_id');

            $input['verified_by_TL'] = $request->filled('verified_by_tl') ? 1 : 0;
            if(in_array(Auth::user()->userRole->code, ['ADMIN'])) {
                $input['verified_by_Admin'] = $request->filled('verified_by_admin') ? 1 : 0;
            }

            $msg = trans('messages.MSG_UPDATE',['name' => 'Daily Task Detail']);
            DailyTask::where('id', $input['id'])
                ->update($input);

            if($request->filled('verified_by_admin') == 1 && $request->get('emp_status') == 'free') {
                $task_date = Carbon::parse($request->get('task_entry_date'))->format('Y-m-d');
                $freeResourceRepo = FreeResource::updateOrCreate([
                    'user_id'       => $request->get('user_id'),
                    'daily_task_id' => $request->get('id'),
                    'task_date'     => $task_date
                ],[]);
            }
            if($request->ajax()) {
                return response()->json(['message' => $msg, 'action_title' => $this->module_title], 200);
            }
            return redirect()->route('admin-index')->with('msg', $msg);
        } catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => $e->getMessage(), 'action_title' => $this->module_title]);
            }
            return redirect()->route('admin-index')->with('error', $e->getMessage());
        }
    }

    public function fetchRecord(Request $request)
    {
        $dailyTaskData = DailyTask::where('user_id', Auth::id())->whereYear('current_date', $request->year)->whereMonth('current_date', $request->month)->whereNotNull('sod_description')->pluck('current_date');
        return response()->json(['dailyTaskData' => $dailyTaskData]);
    }

    public function filterDailyTask(Request $request)
    {
        $dailyTask = DailyTask::with('userTask', 'userTask.officialUser.userTeam')->orderBy('id','DESC');
        $usersArray = (new commonController)->getMentorUsers();
        if ($request->resourceStatus != '') {
            $dailyTask->where('emp_status', $request->resourceStatus);
        }
        if ($request->projectStatus != '') {
            $dailyTask->where('project_status', $request->projectStatus);
        }
        if ($request->date != '') {
            $date = date('Y-m-d', strtotime($request->date));
            $dailyTask->whereYear('current_date', explode('-', $date)[0])
                ->whereMonth('current_date', explode('-', $date)[1])
                ->whereDay('current_date', explode('-', $date)[2]);
        }
        if ($request->team != '') {
            $dailyTask->whereHas('userTask.officialUser.userTeam', function ($q) use ($request) {
                return $q->where('id', $request->team);
            });
        }
        if ($request->searchEmployee != '') {
            if (strlen($request->searchEmployee) > 1) {
                $dailyTask->whereHas('userTask', function ($q) use ($request) {
                    return $q->whereRaw("concat(first_name, ' ', last_name) like '%" .$request->searchEmployee. "%' ");
                });
            }
        }
        if ($request->verifiedByTL != '') {
            $dailyTask->where('verified_by_TL', $request->verifiedByTL);
        }
        if ($request->verifiedByAdmin != '') {
            $dailyTask->where('verified_by_Admin', $request->verifiedByAdmin);
        }
        if(!Helper::hasAnyPermission('daily-tasks-all-emp.list')) {
            $dailyTask = $dailyTask->whereIn('user_id',$usersArray);
        }
        $dailyTask = $dailyTask->groupBy('user_id')->paginate(10);

        $html = view('works.partials.daily-task-accordian', compact('dailyTask'))->render();

        return $html;
        // return response()->json(['message' => 'daily task filtered successfully.', 'data' => $html], 200);

    }

    public function defaulters(Request $request){
        $teams = (new commonController)->getTeams();
        $totalTeam = Team::where('status', 1)->count();
        $totalUsers = User::count();
        $totalProject = Project::count();
        $completedProject = Project::where('status', 3)->count();
        return view('works.defaulters', compact('teams', 'totalTeam', 'totalUsers', 'totalProject', 'completedProject'));
    }

    public function employeeEntryList(Request $request)
    {
        if ($request->team_id == null || $request->team_id == '') {
            $allEmployees = User::select('first_name', 'last_name', 'id')->paginate(10);
        } else {
            $allEmployees = User::whereHas('officialUser', function ($qry) {
                $qry->where('team_id', '=', request()->team_id);
            })->select('first_name', 'last_name', 'id')->paginate(10);
        }

        foreach ($allEmployees as $employee) {
            $projectIds = $employee->userProjects()->where('status', 1)->get(['project_id'])->toArray();
            $projects = Project::whereIn('id', $projectIds)->select('name', 'project_code')->get()->toArray();
            $employee->projects = $projects;
            $userTeamId = $employee->officialUser()->first(['team_id']);
            if (!empty($userTeamId)) {
                $team = Team::where('id', $userTeamId->team_id)->first(['name']);
                $employee->team_name = !empty($team) ? $team->name : '';
            } else {
                $employee->team_name = '';
            }
        }

        $employees = '';
        $non_assigned_project = '';
        $assigned_project = '';
        if ($request->ajax()) {
            if (($allEmployees->count() > 0)) {
                foreach ($allEmployees as $employee) {
                    $employees .= '
                            <tr>
                                <td class="avtar-td">

                                    <!-- <img src="/public/images/users/avatar-1.jpg" class="rounded-circle avatar-sm" alt="" /> -->

                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-12">
                                            ' . $employee->profile_picture . '
                                        </span>
                                    </div>

                                    <div class="ps-2 name-info text-truncate">
                                        <h5 class="font-size-14 m-0 pl-3 text-truncate">
                                            ' . $employee->full_name . '
                                        </h5>
                                        <span class="font-size-12 text-muted pl-3">
                                            ' . $employee->team_name . '
                                        </span>
                                    </div>
                                </td>';
                    if (!empty($employee->projects)) {
                        $assigned_project .= '<td><div>';
                        foreach ($employee->projects as $project) {
                            $assigned_project .= '<span  class="badge font-size-12 font-color-black">' . $project['project_code'] . ' - ' . $project['name'] . '</span>';
                        }
                        $assigned_project .= '</td></div>';
                    } else {
                        $non_assigned_project .= '<td><div><span class="badge bg-danger font-size-12">
                                                        No Project Assigned
                                                        </span></div></td>';
                    }

                    $employees .= (!empty($employee->projects)) ? $assigned_project . '</tr>' : $non_assigned_project . '</tr>';
                    $non_assigned_project = '';
                    $assigned_project = '';
                }
            }

            return $employees;
        }
    }
}
