<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\Helper;
use App\DataTables\SuperAdminProjectDataTable;
use App\Http\Controllers\commonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\UserProjects;
use App\Models\User;
use App\Models\ProjectAllocation;
use App\Models\Technology;
use App\Models\ProjectPriority;
use App\Models\ProjectPaymentType;
use App\Models\Client;
use App\Models\ProjectStatus;
use App\Models\ProjectActivity;
use App\Models\projectTechnology;
use App\Models\UserDesignation;
use App\Models\UserOfficialDetail;
use App\Models\ProjectBillableResources;
use Auth;
use DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\Catch_;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Exceptions\UnauthorizedException;

class SuperProjectController extends Controller
{

    public function __construct() {
        parent::__construct();
        $this->ProjectBillableResources = new ProjectBillableResources;
    }
    /**
     * This method is used for project listing page for super admin
     */
    public function dashboard(SuperAdminProjectDataTable $dataTable)
    {
        if( !in_array(Auth::user()->userRole->code, ['ADMIN', 'PM']) && !Helper::hasAnyPermission('super-admin-project.list') ) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        session(['selectedTab'=>1]);
        // try {
            $allProjects = Project::all()->count();
            $runningProjects = Project::whereHas('projectStatus',function($query) {
                $query->where('name', '!=','Closed');
            });
            $closedProjects = Project::whereHas('projectStatus',function($query) {
                $query->where('name', 'Closed');
            });

            $runningInternal = clone $runningProjects;
            $runningInternal = $runningInternal->where('project_type', 0)->count();
            $runningProjects = $runningProjects->count();
            $runningExternal = $runningProjects - $runningInternal;

            $closedInternal = clone $closedProjects;
            $closedInternal = $closedInternal->where('project_type', 0)->count();
            $closedProjects = $closedProjects->count();
            $closedExternal = $closedProjects - $closedInternal;

            $projectCountByType = Project::groupBy('project_type')->select('project_type', DB::raw('count(*) as total'))->orderby('project_type')->pluck('total', 'project_type')->toArray();

            $totalInternal = $runningInternal + $closedInternal;
            $totalExternal = $runningExternal + $closedExternal;

            $projectStatus = ProjectStatus::pluck('name', 'id')->toArray();
            view()->share('module_title', 'project management');
            return $dataTable->render('super-projects.dashboard', compact(['allProjects', 'runningProjects', 'closedProjects', 'runningInternal', 'runningExternal', 'closedInternal', 'closedExternal', 'totalInternal', 'totalExternal', 'projectCountByType', 'projectStatus']));
            // $projects = Project::with('projectClient', 'projectAllocation', 'projectTeamLead', 'projectReviewer', 'projectPriority', 'projectPaymentType')->get();
            // // $projects->technologies = (new commonController)->getTechnology($projects->technologies_ids);
            // // dd($projects);
            // return view('super-projects.dashboard')->with('projects', $projects);
        // } catch (Exception $e) {

        //     return view('super-projects.dashboard')->with('message', 'Something went wrong!!');

        // }
    }

    /**
     * Used for show particular project details and activity portion
     * @param $id
     */
    public function activityBoard($id)
    {
        if( !in_array(Auth::user()->userRole->code, ['ADMIN', 'PM']) && !Helper::hasAnyPermission('super-admin-project.edit')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        try {
            $projectId = $id;
            $project = Project::with('projectClient', 'projectAllocation', 'projectTeamLead', 'projectReviewer', 'projectPriority', 'projectPaymentType', 'user', 'activities')->find($projectId);
            if(!empty($project->start_date)) {
                $project->start_date = Carbon::parse($project->start_date)->format('d-m-Y');
            }
            if (!empty($project->end_date)) {
                $project->end_date = Carbon::parse($project->end_date)->format('d-m-Y');
            }

            $project->date = Carbon::parse($project->created_at)->format('d-m-Y');
            if($project->created_by != null){
                $project->created_by_name = (new User)->getUserDetailById($project->created_by)->full_name;
            }
            $allocations = (new commonController)->getProjectAllocation();//ProjectAllocation::all();
            $priorities = (new commonController)->getProjectPriority();//ProjectPriority::all();
            $paymentTypes = (new commonController)->getProjectPaymentType();//ProjectPaymentType::pluck('type','id');
            $teamLeads = (new commonController)->getUsers();//User::pluck('first_name','id');
            $reviewers = (new commonController)->getUsers();//User::pluck('first_name','id');
            $members = (new commonController)->getUsers();
            $project_types = ['0' => "Internal", '1' => 'External'];
            unset($members['']);

            // For check all members are associate with this project or not : 1 for yes, 0 for no
            $selected_members = null;
            $project_members = $project->user->pluck('id');
            if(!empty($project_members)) {
                $selected_members = $project_members->toArray();
            }
            $membersKey = array_keys($members);
            $differenceArray = array_diff($membersKey, $selected_members);
            if ($differenceArray == null) {
                $allMembers = "1";
            } else {
                $allMembers = "0";
            }

            $currencies = (new commonController)->getCurrencyName();
            $clients = (new commonController)->getClients();//Client::pluck('first_name','id');
            $technologies = (new commonController)->getAllTechnology();//(new commonController)->getTechnology($project->technologies_ids);//

            $selectedTechnology = (!empty($project->projectTechnology)) ? $project->projectTechnology->pluck('id')->toArray() : null;
            $statuses = (new commonController)->getProjectStatus();//ProjectStatus::all();
            unset($statuses['']); // For not show the default "Please Select"

            $activities = (new commonController)->getProjectActivity($projectId);//ProjectActivity::with('activityUser', 'activityProject')->where('project_id',$projectId)->orderBy('id', 'desc')->get();
            $getRoleWiseUsers = (new commonController)->getRoleWiseUsers('BDE',10);
            $roleUsers[''] = 'Please select';
            foreach ($getRoleWiseUsers as $key => $value) {
                $roleUsers[$value->id] = $value->full_name;
            }
            return view('super-projects.activityboard')->with([
                'project' => $project,
                // 'members' => $members,
                'allocations' => $allocations,
                'priorities' => $priorities,
                'paymentTypes' => $paymentTypes,
                'teamLeads' => $teamLeads,
                'reviewers' => $reviewers,
                'clients' => $clients,
                'technologies' => $technologies,
                'statuses' => $statuses,
                'activities' => $activities,
                'selectedTechnology' => $selectedTechnology,
                'allMembers' => $allMembers,
                'members' => $members,
                'roleUsers' => $roleUsers,
                'currencies' => $currencies,
                'project_types' => $project_types,
                'selected_members' => $selected_members
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function addProjectDetails(Request $request)
    {
        if( !in_array(Auth::user()->userRole->code, ['ADMIN', 'PM']) && !Helper::hasAnyPermission('super-admin-project.create')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        $allocations = (new commonController)->getProjectAllocation();//ProjectAllocation::all();
        $priorities = (new commonController)->getProjectPriority();//ProjectPriority::all();
        $paymentTypes = (new commonController)->getProjectPaymentType();//ProjectPaymentType::pluck('type','id');
        $teamLeads = (new commonController)->getUsers();//User::pluck('first_name','id');
        $reviewers = (new commonController)->getUsers();//User::pluck('first_name','id');
        $members = (new commonController)->getUsers();
        $project_types = ['0' => "Internal", '1' => 'External'];
        unset($members['']);
        $currencies = (new commonController)->getCurrencyName();
        $clients = (new commonController)->getClients();//Client::pluck('first_name','id');
        $technologies = (new commonController)->getAllTechnology();//(new commonController)->getTechnology($project->technologies_ids);//
        $statuses = (new commonController)->getProjectStatus();//ProjectStatus::all();
        unset($statuses['']); // For not show the default "Please Select"
        $getRoleWiseUsers = (new commonController)->getRoleWiseUsers('BDE',10);
        $roleUsers[''] = 'Please select';
        foreach ($getRoleWiseUsers as $key => $value) {
            $roleUsers[$value->id] = $value->full_name;
        }
        return view('super-projects.create')->with([
            'allocations' => $allocations,
            'priorities' => $priorities,
            'paymentTypes' => $paymentTypes,
            'teamLeads' => $teamLeads,
            'reviewers' => $reviewers,
            'clients' => $clients,
            'technologies' => $technologies,
            'statuses' => $statuses,
            'members' => $members,
            'allMembers' => '0',
            'roleUsers' => $roleUsers,
            'add_form' => 1,
            'currencies' => $currencies,
            'project_types' => $project_types,
        ]);
    }
/**
 * For Add Project details (Only for super admin)
 */
    public function storeProjectDetails(Request $request)
    {
        try {
            $input = $request->except('_token','technologies_ids');
            if($request->members_ids){
                $input['members_ids'] = implode(',',$request->members_ids);
            }
            // If all members are associate with this project
            if ($request->all_members == "1") {
                $allMembers = User::pluck('id')->toArray();
                $input['members_ids'] = implode(',', $allMembers);
            }

            // Uncomment below line when need start auto generate project code
            // $lastProjectCode = Helper::getNextAutoCode(New Project, 'project_code');

            $input['created_by'] = Auth::user()->id;
            // $input['project_code'] = $lastProjectCode;
            if (!empty($input['start_date'])) {
                $input['start_date'] = Carbon::parse($input['start_date'])->format('Y-m-d');
            }
            if (!empty($input['end_date'])) {
                $input['end_date'] = Carbon::parse($input['end_date'])->format('Y-m-d');
            }
            unset($input['all_members']);
            $project = Project::updateOrCreate($input);

            if($request->technologies_ids){
                foreach($request->technologies_ids as $val){
                    $data['project_id'] = $project->id;
                    $data['technology_id'] = $val;
                    projectTechnology::updateOrCreate($data);
                }
            }
            if($request->members_ids){
                foreach($request->members_ids as $val){
                    $projectData['project_id'] = $project->id;
                    $projectData['user_id'] = $val;
                    UserProjects::updateOrCreate($projectData);
                }
            }

            $message = trans('messages.MSG_SUCCESS',['name' => 'New Project']);
            return redirect()->route('super-admin-project-dashboard')->with([
                'message' => trans($message)
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * For edit Project details (Only for super admin)
     * @param $request
     */
    public function editProjectDetails(Request $request)
    {
        if( !in_array(Auth::user()->userRole->code, ['ADMIN', 'PM']) && !Helper::hasAnyPermission('super-admin-project.edit')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        // dd($request->all());
        try {
            $input = $request->except('_token','created_by_name');
            $data = [];
            if($request->filled('members_ids')){
                $input['members_ids'] = implode(',',$request->members_ids);
            }
            // If all members are associate with this project
            if ($request->all_members == "1") {
                $allMembers = User::pluck('id')->toArray();
                $input['members_ids'] = implode(',', $allMembers);
            }
            if(!empty($input['start_date'])) {
                $input['start_date'] = Carbon::parse($input['start_date'])->format('Y-m-d');
            }
            if (!empty($input['end_date'])) {
                $input['end_date'] = Carbon::parse($input['end_date'])->format('Y-m-d');
            }
            $project = Project::updateOrCreate(['id'=> $input['id']],$input);
            if($request->technologies_ids){
                $tempTechnology = $project->projectTechnology->pluck('id')->toArray();
                foreach($request->technologies_ids as $val){
                    $data['project_id'] = $input['id'];
                    $data['technology_id'] = $val;

                    unset($tempTechnology[array_search($val,$tempTechnology)]);
                    projectTechnology::updateOrCreate($data);
                }
            }
            if(!empty($tempTechnology)){
                projectTechnology::whereIn('technology_id',$tempTechnology)->where('project_id',$input['id'])->delete();
            }

            // delete already assign project to user
            $projects = UserProjects::where('project_id', '=', $input['id'])->get();
            if (count($projects) > 0) {
                foreach ($projects as $value) {
                    $value->forceDelete();
                }
            }
            // add new users
            if (!empty($input['members_ids'])) {
                $members = explode(',', $input['members_ids']);
                foreach ($members as $val) {
                    $projectData['project_id'] = $input['id'];
                    $projectData['user_id'] = $val;
                    UserProjects::updateOrCreate($projectData);
                }
            }

            $message = trans('messages.MSG_UPDATE',['name' => 'Project Details']);
            return redirect()->route('super-admin-project-activity', $request->id)->with([
                'message' => trans($message)
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Used for save activity(comment) of particular project
     * @param $request
     */
    public function saveActivity(Request $request)
    {
        try {

            $activity = new ProjectActivity;
            $activity->user_id = $request->user_id;
            $activity->project_id = $request->project_id;
            $activity->comments = $request->project_comment;
            $activity->save();

            return redirect()->route('super-admin-project-activity', $request->project_id);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }

    public function checkProjectCode(Request $request)
    {
        if($request->project_id){
            $rules = [
                'project_code' => 'required|unique:projects,project_code,'.$request->project_id,
            ];
        }else{
            $rules = [
                'project_code' => 'required|unique:projects'
            ];
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json(false);
        }else{
            return response()->json(true);
        }
    }

    public function manageBillableResource(Request $request, $project_id)
    {
        $billableResources = ProjectBillableResources::where('project_id', $project_id)->get();
        $project = Project::with('projectUser')->find($project_id);
        $users = $project->projectUser->pluck('full_name','id');
        $paymentTypes = (new commonController)->getProjectPaymentType();
        $users = ['' => "Select Resource"]+$users->toArray();
        $paymentTypes = ['' => "Select Payment Type"]+$paymentTypes->toArray();
        $currencies = (new commonController)->getCurrencyName();
        view()->share('module_title', 'Billable Resources');
        return view('super-projects.edit-billable-resource', compact('project','billableResources', 'project_id', 'users', 'paymentTypes','currencies'));
    }

    public function updateBillableResource(Request $request) {

        $rules = [
            'billable-group.*.user_id' => ['required'],
            'billable-group.*.payment_type_id' => 'required',
            'billable-group.*.currency_id' => 'required',
            'billable-group.*.amount' => 'required',
        ];

        $inputs = $request->input('billable-group');
        if(!empty($inputs)) {
            $inputs = collect($inputs)->whereNotNull('user_id')->pluck('user_id')->toArray();
            $duplicates = [];
            if(!empty($inputs)) {
                foreach(array_count_values($inputs) as $val => $c) {
                    if($c > 1) {
                        $duplicates[] = $val;
                    }
                }
            }
        }
        if(!empty($duplicates)) {
            foreach ($inputs as $key => $value) {
                if(in_array($value, $duplicates)) {
                    $rules["billable-group.$key.user_id"] = [Rule::unique('project_billable_resources', 'user_id')];
                }
            }
        }


        $this->validate($request, $rules, [
            'billable-group.*.user_id.required' => 'Resource is required.',
            'billable-group.*.payment_type_id.required' => 'Payment type is required.',
            'billable-group.*.currency_id.required' => 'Currency is required.',
            'billable-group.*.amount.required' => 'Amount is required.',
            'billable-group.*.user_id.unique' => 'Resource is already selected.',
        ]);
        try {
            $requestData = $request->all();

            $billable_resources = $requestData['billable-group'];
            $billable_resource_map = [];

            // for mapping related table
            foreach($billable_resources as $billable_resource) {
                $userId = $billable_resource['user_id'];
                unset($billable_resource['user_id']);
                $billable_resource_map[$userId] = $billable_resource;
            }

            $this->ProjectBillableResources->syncBillableResourceProject($billable_resource_map,$requestData['project_id']);
            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Billable Resources'])], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }

    public function editActivity(Request $request, $project_id, $id)
    {
        view()->share('module_title', "Comment");
        $activity = ProjectActivity::findOrFail($id);
        return view('super-projects.edit-activity', compact('activity', 'project_id'));
    }

    public function updateActivity(Request $request, $project_id, $id)
    {
        try {
            $activity = ProjectActivity::findOrFail($id);
            if(!empty($activity)) {
                $activity->comments = $request->comments;
                $activity->save();
                Session::flash('success', trans('messages.MSG_UPDATE',['name' => 'Comment']));
                return response()->json(['status' => 'success'], 200);
            }
            return response()->json(['status' => 'error'], 400);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function deleteActivity(Request $request, $project_id, $id)
    {
        try {
            $activity = ProjectActivity::findOrFail($id);
            if(!empty($activity)) {
                $activity->delete();
                Session::flash('success', trans('messages.MSG_DELETE',['name' => 'Comment']));
                return response()->json(['status' => 'success'], 200);
            }
            return response()->json(['status' => 'error'], 400);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
