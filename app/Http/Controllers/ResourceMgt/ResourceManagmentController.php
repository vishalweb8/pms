<?php

namespace App\Http\Controllers\ResourceMgt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\commonController;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\ResourceManagement;
use App\Models\Team;
use App\Models\User;
use DB;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ResourceManagmentController extends Controller
{
    public function allEmployeeView()
    {
        if(!Helper::hasAnyPermission('all-employee.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }

        $allEmployeeData = Helper::resourceMgtCount();
        
        $statsCount = Helper::getResourceBillableStatus();
        return view('resource-managment.all-resource-manage', compact('allEmployeeData', 'statsCount'));
    }

    /**
     * for get user list with projects of daily basis
     *
     * @param  mixed $request
     * @return void
     */
    public function allEmployeeList(Request $request)
    {

        $allEmployees = User::with(['officialUser.userTeam','resourceProjects']);

        if (!empty($request->team_id) ) {
            $allEmployees->whereHas('officialUser', function ($qry) use($request) {
                $qry->where('team_id', $request->team_id);
            });
        }
        if (!empty($request->status) && $request->status == 'no_project') {
            $allEmployees->doesntHave('resourceProjects');
        } else if (!empty($request->status) ) {
            $allEmployees->whereHas('resourceProjects', function ($qry) use($request) {
                $qry->where('status',$request->status);
            });
        }

        $allEmployees = $allEmployees->orderBy('first_name')->get();
        if(!empty($request->sortBy)) {
            if($request->sortBy == 'desc') {
                $allEmployees = $allEmployees->sortByDesc('experience');
            } else {
                $allEmployees = $allEmployees->sortBy('experience');
            }
        }
        
        $employees = '';
        $non_assigned_project = '';
        $assigned_project = '';
        if ($request->ajax()) {
            if ($allEmployees->isNotEmpty()) {
                foreach ($allEmployees as $employee) {
                    if(!empty($request->experience)) {
                        [$minExpr, $maxExpr] = explode('-',$request->experience);
                        if( $minExpr <= $employee->experience &&  $employee->experience < $maxExpr) {
                            
                        } else {
                            continue;
                        }
                    }
                    $team_name = $employee->officialUser->userTeam->name ?? '';
                    $employees .= '<tr><td class="avtar-td" style="width:315px;">'.userNameWithIcon($employee, $team_name).
                        '<a href="#" class="plus-icon add-btn" data-url="'.route('add-resource-project',$employee->id).'"  ><i class="fa fa-plus fa-sm" aria-hidden="true"></i></a></td>';

                    if ($employee->resourceProjects->isNotEmpty()) {
                        $projects = $employee->resourceProjects;
                        $assigned_project .= '<td><div>';
                        foreach ($employee->resourceProjects as $resProject) {
                            $status = $resProject->status;
                            $project = $resProject->project;
                            if (!empty($request->status) && $request->status != $status) {
                                continue;
                            }
                            $basicClass = 'badge font-size-12 font-color-black ';
                            $bgClass = ($status == 1) ? "bg-external" : ($status == 2  ? "bg-internal ": 'bg-hold');
                            $title = ($status == 1) ? "External" : ($status == 2  ? "Internal": 'On Hold');

                            $removeIcon = '<i class="fa fa-times unassigned-project cursor-pointer" aria-hidden="true" data-project-id="'.$project->id.'" data-user-id="'.$employee->id.'" title="Remove"></i>';

                            $assigned_project .= '<span  class="'.$basicClass.$bgClass.'" title="'.$title.'"><a class="project-lable" target="_blank" href="'.route('super-admin-project-activity',$project->id).'">' . $project->project_code . ' - ' . $project->name . '</a> '.$removeIcon.'</span>';
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

    /**
     * open modal for fill form detail of resorces
     *
     * @param  mixed $userId
     * @return void
     */
    public function addResourceProject($userId)
    {
        $projects = (new commonController)->getProjects();
        return view('resource-managment.partials.resource-manage-form', compact('projects','userId'));
    }

    /**
     * store project of resources on daily basis
     *
     * @param  mixed $request
     * @return void
     */
    public function storeResourceProject(Request $request)
    {
        $request->validate([
            'project_id' => 'required'
        ]);
        try {
            $query = $request->only(['user_id','project_id']);
            ResourceManagement::updateOrCreate($query,$request->all());
            return response()->json(['message' => trans('messages.MSG_SUCCESS_ASSIGNED',['name' => 'Project'])], 200);
        } catch (\Throwable $th) {
            \Log::error('Getting error while storing resource projects:- '.$th);
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * for remove assigned project from user
     *
     * @param  mixed $request
     * @return void
     */
    public function unassignProjects(Request $request)
    {
        try {

            $query = $request->except('_token');
            ResourceManagement::where($query)->delete();

            return response()->json(['success' => true,'message' => trans('messages.UNASSIGN_PROJECT')], 200);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function totalTeamsView()
    {
        if(!Helper::hasAnyPermission('all-teams.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        $allEmployeeData = Helper::resourceMgtCount();
        $statsCount = Helper::getResourceBillableStatus();
        return view('resource-managment.total-teams',compact('allEmployeeData', 'statsCount'));
    }

    public function allProjectsView()
    {
        if(!Helper::hasAnyPermission('all-project.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }

        $allEmployeeData = Helper::resourceMgtCount();
        $statsCount = Helper::getResourceBillableStatus();
        return view('resource-managment.all-projects',compact('allEmployeeData', 'statsCount'));
    }

    public function allBDEsView()
    {
        if(!Helper::hasAnyPermission('all-BDEs.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }

        $allEmployeeData = Helper::resourceMgtCount();
        $statsCount = Helper::getResourceBillableStatus();
        return view('resource-managment.all-bdes',compact('allEmployeeData', 'statsCount'));
    }

    public function employeeExp()
    {
        if(!Helper::hasAnyPermission('employee-exp.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        $teams = (new commonController)->getTeams();
        return view('resource-managment.employee-experience',compact('teams'));
    }

    public function getMonthlyWorkLog() {

        $today = Carbon::now(); //Current Date and Time

        $from_date = Carbon::parse($today)->startOfMonth();
        $to_date = Carbon::parse($today)->endOfMonth();
        $period = CarbonPeriod::create($from_date, $to_date);
        $user_type = [
            'active' => "Active Employee",
            'all' => "All Employee",
        ];
        $range = [];
        foreach ($period as $date) {
            $range[$date->format('D d')] =  [];
        }
        $teams = (new commonController)->getTeams();
        $users = User::withBlocked()->where('id', '<>', Auth::id())->orderBy('first_name')->get()->pluck('full_name','id');
        $projects = (new commonController)->getProjects();
        $selectedTab = Session::get('selectedTabWorklog');
        if ($selectedTab == null) {
            Session::put('selectedTabWorklog', '1');
            $selectedTab = Session::get('selectedTabWorklog');
        }

        return view('resource-managment.all-worklog', compact('range', 'teams', 'user_type','selectedTab','users','projects'));

    }

    /**
     * for show time entry of all employee monthly wise
     *
     * @return void
     */
    public function monthlyTimeEntry()
    {
        if(!Helper::hasAnyPermission('all-emp-time-entry.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }

        $today = Carbon::now(); //Current Date and Time

        $from_date = Carbon::parse($today)->startOfMonth();
        $to_date = Carbon::parse($today)->endOfMonth();
        $period = CarbonPeriod::create($from_date, $to_date);
        $user_type = [
            'active' => "Active Employee",
            'all' => "All Employee",
        ];
        $range = [];
        foreach ($period as $date) {
            $range[$date->format('D d')] =  [];
        }
        $teams = (new commonController)->getTeams();

        return view('resource-managment.time-entry', compact('range', 'teams', 'user_type'));
    }

}
