<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\Client;
use App\Models\Currency;
use App\Models\DailyTask;
use App\Models\Team;
use App\Models\User;
use App\Models\UserDesignation;
use App\Models\Department;
use App\Models\Leave;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\ProjectAllocation;
use App\Models\ProjectPaymentType;
use App\Models\ProjectPriority;
use App\Models\ProjectStatus;
use App\Models\State;
use App\Models\Technology;
use App\Models\UserOfficialDetail;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;

class commonController extends Controller
{
    /* get all employees role */
    public function getEmployeeRoles(){
        $roleList = Role::all()->where('status', '=', 1);

        $roles = [];
        $roles[''] = 'Please select';
        foreach ($roleList as $key => $value) {
            $roles[$value->id] = $value->name;
        }

        return $roles;
    }

    /* get all Team leaders */
    public function getTeamLeader($userId = null)
    {
        $teamLeaderObject = User::whereHas('userRole', function ($q) {
            $q->where('code', '=', 'TL');
        });
        if($userId){
            $teamLeaderObject->where('id','<>', $userId);
        }
        $teamLeaderList = $teamLeaderObject->get();
        $teamLeaders = [];
        $teamLeaders[''] = 'Please select';
        foreach ($teamLeaderList as $key => $value) {
            $teamLeaders[$value->id] = $value->full_name;
        }

        return $teamLeaders;
    }

    public function getTeamLeaderByIds($ids)
    {
        $teamLeaders = User::whereIn('id',$ids)->get();
        $reportings = [];
        foreach ($teamLeaders as $key => $value) {
            $reportings[$value->id] = $value->full_name;
        }
        return $reportings;
    }

    /* get all Team users */
    public function getUsers($userId = null,$isMentorMember=false)
    {
        $userList = User::orderBy('first_name');
        if ($userId) {
           $userList = User::where('id', '<>', $userId)->orderBy('first_name');
        }
        if($isMentorMember){
            $userList =  $userList->whereHas('roles', function ($query) {
                $query->whereNotIn("type", ['ADMIN','PM','TL']);
            });
        }
        $userList = $userList->get();
        $users[''] = 'Please select';
        foreach ($userList as $key => $value) {
            $users[$value->id] = $value->full_name;
        }
        return $users;
    }

    // Get user by id
    public function getUserByIds($ids)
    {
        $allUsers = User::whereIn('id',$ids)->get();
        $user = [];
        foreach ($allUsers as $key => $value) {
            $user[$value->id] = $value->full_name;
        }
        return $user;
    }

    /* get all Team users */
    public function getClients($clientId = null)
    {
        $clientList = Client::all();
        if ($clientId) {
           $clientList = Client::where('id', '<>', $clientId)->get();
        }
        $clients[''] = 'Please select';
        foreach ($clientList as $key => $value) {
            $clients[$value->id] = $value->full_name;
        }
        return $clients;
    }

    /* get user designation */
    public function getUserDesignation()
    {
        $userDesignationList = UserDesignation::where('status', '=', 1)->get();

        $userDesignation = [];
        $userDesignation[''] = 'Please select';
        foreach ($userDesignationList as $key => $value) {
            $userDesignation[$value->id] = $value->name;
        }

        return $userDesignation;
    }

    /* get all Teams */
    public function getTeams()
    {
        $teamsList = Team::where('status', '=', 1)->get();

        $teams = [];
        $teams[''] = 'Select Team';
        foreach ($teamsList as $key => $value) {
            $teams[$value->id] = $value->name;
        }

        return $teams;
    }

    /* get departments */
    public function getDepartments()
    {
        $departmentList = Department::where('status', '=', 1)->get();

        $department = [];
        $department[''] = 'Please select';
        foreach ($departmentList as $key => $value) {
            $department[$value->id] = $value->department;
        }

        return $department;
    }

    /* get all country list */
    public function getCountries()
    {
        $countryList = Countries::orderBy('name', 'ASC')->get();
        $countries = [];
        $countries[''] = 'Select Country';
        foreach ($countryList as $key => $value) {
            $countries[$value->id] = $value->name;
        }

        return $countries;
    }

    /* get state as per selected country */
    public function fetchState($options = [])
    {

        $filters = [];
        $request = request();
        if($request->filled('country_id')) {
            $filters[] = Helper::makeFilter('country_id', '=', $request->get('country_id'));
        }elseif($request->old('country_id')) {
            $filters[] = Helper::makeFilter('country_id', '=', $request->old('country_id'));
        }elseif(!empty($options['country_id'])){
            $filters[] = Helper::makeFilter('country_id', '=', $options['country_id']);
        }
        $query = States::select();

        if(!empty($filters)) {
            foreach ($filters as $key => $filter) {
                $query->where($filter['field'], $filter['operator'], $filter['value']);
            }
        }
        $data['states'] = $query->orderBy('name', 'ASC')->pluck('name', 'id');

        if($request->ajax() && !isset($options['from_controller'])) {
            return response()->json($data['states']);
        }
        return $data['states']->toArray();
    }

    /* get city as per selected state */
    public function fetchCity($options = [])
    {
        $filters = [];
        $request = request();
        if($request->filled('state_id')) {
            $filters[] = Helper::makeFilter('state_id', '=', $request->get('state_id'));
        }elseif($request->old('state_id')) {
            $filters[] = Helper::makeFilter('state_id', '=', $request->old('state_id'));
        }elseif(!empty($options['state_id'])){
            $filters[] = Helper::makeFilter('state_id', '=', $options['state_id']);
        }

        $query = Cities::select();

        if(!empty($filters)) {
            foreach ($filters as $key => $filter) {
                $query->where($filter['field'], $filter['operator'], $filter['value']);
            }
        }
        $data['cities'] = $query->orderBy('name', 'ASC')->pluck('name', 'id');
        if($request != null){
            if($request->ajax() && !isset($options['from_controller'])) {
                return response()->json($data['cities']);
            }
        }
        return $data['cities']->toArray();
    }

    public function getReportings($id)
    {
        $reportingUsers = User::whereIn('id',$id)->get();
        $reportings = [];
        foreach ($reportingUsers as $key => $value) {
            $reportings[$value->id] = $value->full_name;
        }
        return $reportings;
    }

    public function getMembers($id)
    {
        $memberUsers = User::whereIn('id',$id)->get();
        $members = [];
        foreach ($memberUsers as $key => $value) {
            $members[$value->id] = $value->full_name;
        }
        return $members;
    }

    public function getTechnology($id)
    {
        return Technology::whereIn('id',$id)->pluck('technology','id')->toArray();
    }

    public function getAllTechnology()
    {
        return Technology::where('status', '=', 1)->pluck('technology','id');
    }

    public function getProjectAllocation()
    {
        $projectAllocation = ProjectAllocation::select('type','id')->where('status', '=', 1)->get();
        $allocation[''] = 'Please select';
        foreach ($projectAllocation as $key => $value) {
            $allocation[$value->id] = $value->type;
        }
        return $allocation;
    }

    public function getProjectPriority()
    {
        $projectPriority = ProjectPriority::select('name','id')->where('status', '=', 1)->get();
        $priority[''] = 'Please select';
        foreach ($projectPriority as $key => $value) {
            $priority[$value->id] = $value->name;
        }
        return $priority;
    }

    public function getProjectPaymentType()
    {
        return ProjectPaymentType::where('status', '=', 1)->pluck('type','id');
    }

    public function getProjectStatus()
    {
        $projectStatus = ProjectStatus::select('name','id')->where('status', '=', 1)->get();
        $status[''] = 'Please select';
        foreach ($projectStatus as $key => $value) {
            $status[$value->id] = $value->name;
        }
        return $status;
    }

    public function getProjects()
    {
        $projects = Project::select('name','id', 'project_code')->orderBy('project_code', 'desc')->get();
        $status[''] = 'Please select';
        foreach ($projects as $key => $value) {
            $status[$value->id] = "{$value->project_code} - " . $value->name;
        }
        return $status;
    }

    public function getProjectActivity($pid)
    {
        return ProjectActivity::with('activityUser', 'activityProject')->where('project_id',$pid)->orderBy('id', 'desc')->get();
    }

    public function listTeamRequests($userId,$model){
        $table = $model->getTable();
        $employees = DB::table($table)->whereRaw("find_in_set('". $userId . "',request_to)")->pluck('id');
        return $employees;
    }

    public function listAllRequests($model)
    {
        $table = $model->getTable();
        $employees = DB::table($table)->pluck('id');
        return $employees;
    }

    public function getRoleWiseUsers($role,$teamId=null)
    {

        if($teamId != null){
            $user = User::whereHas('officialUser.userTeam',function($q) use($teamId){
                $q->where('id','=',$teamId);
            });
        } else{
            $user = User::whereHas('userRole',function($q) use($role){
                $q->where('code','=',$role);
            });
        }
        $user = $user->where('status',1)->orderBy('first_name')->get();
        return $user;
    }

    public function fetchUserLeaves(){
        try {
            $leaves = Leave::where('request_from',Auth::user()->id)->where('status', 'approved')->select('start_date','end_date')->get();
            $dailyTaskAdded = false;
            $allLeaves = $this->checkPastRequest($leaves);
            $currentDayTask = DailyTask::where('user_id',Auth::user()->id)->whereDay('current_date',Carbon::now()->format(('d')))->whereYear('current_date',Carbon::now()->format(('Y')))->whereMonth('current_date',Carbon::now()->format(('m')))->first();
            if($currentDayTask){
                $dailyTaskAdded = $currentDayTask->id;
            }

            return response()->json(['allLeaves' => $allLeaves,'dailyTaskAdded' => $dailyTaskAdded]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function checkPastRequest($requestArr){
        $requests = [];
        try {
            foreach ($requestArr as $request) {
                if($request->start_date == '' || $request->end_date == ''){
                    continue;
                }
                $period = CarbonPeriod::create($request->start_date, $request->end_date);

                // Iterate over the period
                foreach ($period as $date) {
                    $requestDates =  $date->format('d-m-Y');
                    $requests[] = $requestDates;
                }
            }
            $allRequests= array_unique($requests);
            return $allRequests;

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getCurrencyName()
    {
        $currencyList = Currency::orderBy('name','ASC')->get();
        $currencies = [];
        $currencies[''] = 'Select Currency';
        foreach ($currencyList as $key => $value) {
            $currencies[$value->id] = $value->code.'  '.'('.$value->symbol.')';
        }
        return $currencies;
    }

    /* get all reporting persons  */
    public function getReportingUsers($userId = null)
    {
        $users = [];
        $userData = UserOfficialDetail::where('user_id', $userId)->first();
        $userList = User::whereHas('userRole',function($q){
            $q->whereIn('code', ['ADMIN', 'HR']);
        })->get();
        if ($userData) {
            $teamLeader = UserOfficialDetail::where('user_id', $userId)->with('userTeam.teamLeaders')->first()->toArray();
            if(!empty($teamLeader['user_team']['team_leader'])){
                $userList[] = $teamLeader['user_team']['team_leader'];
            }
        }
        $users = $userList->pluck('full_name','id')->toArray();
        return $users;
    }

    public function getUserDesigTeam(Request $request)
    {
        $user = UserOfficialDetail::where('user_id', $request->user_id)->with('userDesignation','userTeam')->first();
        return response()->json([ 'data' => $user]);
    }

    public function getMentorUsers() {
        $usersArray = User::find(Auth::user()->id)->teamLeaderMembers()->pluck('member_id')->toArray();
        return $usersArray;
    }

}
