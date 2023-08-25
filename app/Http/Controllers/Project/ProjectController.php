<?php

namespace App\Http\Controllers\Project;

use App\DataTables\ProjectDataTable;
use App\DataTables\SuperAdminProjectDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\UserProjects;
use App\Models\User;
use Carbon\Carbon;
use App\Models\ProjectStatus;
use App\Models\UserDesignation;
use App\Http\Controllers\commonController;
use App\Models\ProjectFiles;
use App\Models\ProjectPriority;
use Auth;
use App\Models\ProjectTask;
use App\DataTables\ProjectTaskDatatable;
use App\Http\Controllers\AutomationController;
use App\Models\ProjectTaskWorkLog;
use App\Models\TaskAssignee;
use Validator;
use Session;
use DB;
use Log;

class ProjectController extends Controller
{
    public function dashboard(ProjectDataTable $dataTable)
    {
        $allProjects = Project::all()->count();
        $runningProjects = Project::whereHas('projectStatus',function($query) {
            $query->where('name', '!=','Closed');
        })->count();
        $closedProjects = Project::whereHas('projectStatus',function($query) {
            $query->where('name', 'Closed');
        })->count();
        Session::put('selectedTab', '1');

        return $dataTable->render('projects.dashboard', compact(['allProjects', 'runningProjects', 'closedProjects']));
    }

    public function view($id)
    {
        $projectId = $id;
        $projects = Project::find($projectId);
        $memberIds = UserProjects::where('project_id', $projectId)->pluck('user_id');
        $members = User::find($memberIds);
        return view('projects.view')->with(['members' => $members, 'projectId' => $projectId]);
    }

    public function allMembers($id)
    {
        $projectId = $id;
        $projects = Project::find($projectId);
        $memberIds = UserProjects::where('project_id', $projectId)->pluck('user_id');
        $members = User::with('userRole', 'officialUser.userTeam')->find($memberIds);
        return view('projects.members')->with(['members' => $members]);
    }

    public function projectDetails($id, ProjectTaskDatatable $dataTable)
    {
        $projectId = $id;
        $project = Project::find($projectId);

        $check = UserProjects::where('project_id', $projectId)->pluck('user_id')->toArray();

        // For backup :
        // if ($project->members_ids != '') {
        //     $memberIds = explode(',', $project->members_ids);
        // } else {
        //     $memberIds = [];
        // }
        // $members = User::whereIn('id',$memberIds)->get();

        if (isset($check) && !empty($check)) {
            $members = User::whereIn('id',$check)->get();
        } else {
            $members = [];
        }


        $project->created_at = date('l, F d y h:i:s',strtotime($project->created_at));
        if(strtotime($project->end_date) != strtotime($project->start_date)){
            $days = (strtotime($project->end_date) - strtotime($project->start_date)) / (60 * 60 * 24);
            $project->total_hours = $days * 8;
        }else{
            $project->total_hours = 8 ;
        }
        if($project->created_by != null){
            $project->created_by_name = (new User)->getUserDetailById($project->created_by)->full_name;
        }
        if($project->status){
            $project_status = ProjectStatus::where('id',$project->status)->first('name');
            $project->status = $project_status->name;
        }
         foreach($members as $key=>$value){
             if (isset($value->officialUser->designation_id) && !empty($value->officialUser->designation_id)) {
                 $designation_id = $value->officialUser->designation_id;
                 $designation = UserDesignation::find($designation_id);
                 $members[$key]['designation_name'] = $designation->name;
             }
         }
         $priorities = (new commonController)->getProjectPriority();//ProjectPriority::all();
         unset($priorities[""]);
         $project_files = ProjectFiles::where('project_id',$id)->orderBy('id', 'DESC')->get();
         foreach($project_files as $key=>$files){
             if (isset($files->created_by) && !empty($files->created_by)) {
                 $project_files[$key]['created_by_name'] = (new User)->getUserDetailById($files->created_by)->full_name;
             } else {
                 $project_files[$key]['created_by_name'] = '-';
             }
         }
         $project->files = $project_files->all();

         $allUsers = (new commonController)->getUsers();
         unset($allUsers['']);

        // For get assigned project members list without duplications
        $projectMembersArr = (new commonController)->getMembers($check);
        $projectMembersKeys = array_keys($projectMembersArr);
        $projectMembers = implode(',', $projectMembersKeys);

        //  dd(implode(',', $projectMembers));
        // For project priority
        $projectPriority = (new commonController)->getProjectPriority();
        $projectPriority[''] = 'Select';

        $projectTasks = ProjectTask::where('project_id', $projectId)->get();

        $statusCounts = ProjectTask::where('project_id', $projectId)->groupBy('status')->select('status', DB::raw('count(*) as count'))->pluck('count', 'status');
        $todoTaskCount = $statusCounts['todo'] ?? 0;
        $inprogressTaskCount = $statusCounts['inprogress'] ?? 0;
        $completedTaskCount = $statusCounts['completed'] ?? 0;
        $today = null;

        $taskIds = $projectTasks->pluck('id')->toArray();
        $taskLogs = ProjectTaskWorkLog::whereIn('task_id', $taskIds)->pluck('log_time')->toArray();
        $totalHours = array_sum($taskLogs);

        $selectedTab = Session::get('selectedTab');
        if ($selectedTab == null) {
            $selectedTab = Session::put('selectedTab', '1');
        }

        return $dataTable->render('projects.project-details', compact('members','project','priorities', 'allUsers', 'projectPriority', 'projectMembers', 'projectTasks', 'todoTaskCount', 'inprogressTaskCount', 'completedTaskCount', 'today', 'selectedTab', 'totalHours'));
    }

    /**
     * For assign team member/user to particular project
     *
     * @param Request $request
     * @return void
     */
    public function saveProjectMembers(Request $request)
    {
        try {

            $memberIds = $request->members_ids;
            $project = Project::find($request->project_id);
            $project->projectUser()->sync($memberIds);  // Here sync method is used for add or update members in particular project

            Session::put('selectedTab', '1');
            return response()->json(['message' => trans('messages.ASSIGN_MEMBERS')], 200);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    
    /**
     * for remove assigned project from user
     *
     * @param  mixed $request
     * @return void
     */
    public function removeProjectMembers(Request $request)
    {
        try {

            $memberIds = $request->members_ids;
            $project = Project::find($request->project_id);
            $project->projectUser()->detach($memberIds);

            return response()->json(['success' => true,'message' => trans('messages.UNASSIGN_PROJECT')], 200);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * For change priority of project from project-details page
     *
     * @param Request $request
     * @return void
     */
    public function changeProjectPriority(Request $request)
    {
        try {

            $project = Project::where('id', $request->project_id)->first();
            $project->priority_id = $request->priority_id;
            $project->save();
            Session::put('selectedTab', '1');

            return response()->json(['message' => trans('messages.CHANGE_PRIORITY')], 200);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * For save file link google docs, drive etc..
     *
     * @param Request $request
     * @return void
     */
    public function saveFileLinks(Request $request)
    {
        try {

            $filesLink = new ProjectFiles;
            $filesLink->project_id = $request->project_id;
            $filesLink->name = $request->name;
            $filesLink->link = $request->link;
            $filesLink->created_by = Auth::user()->id;
            $filesLink->save();
            Session::put('selectedTab', '1');

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'File Links'])], 200);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * For remove or delete uploaded links of google drive, docs etc..
     *
     * @param Request $request
     * @return void
     */
    public function deleteFileLinks(Request $request)
    {
        try {

            $filesLink = ProjectFiles::where('id', $request->id)->where('project_id', $request->project_id)->first();
            $filesLink->delete();
            Session::put('selectedTab', '1');

            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'File Links'])], 200);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * For view page of add task details
     *
     * @param Request $request
     */
    public function addTask(Request $request)
    {

        $project_id = $request->route('id');
        $projectDetails = Project::where('id', $project_id)->first();
        $check = UserProjects::where('project_id', $project_id)->pluck('user_id')->toArray();
        $projectMembersArr = (new commonController)->getMembers($check);
        $projectMembers = array_keys($projectMembersArr);
        $assignees = (new commonController)->getUserByIds($projectMembers);
        unset($assignees['']);
        $priorities = (new commonController)->getProjectPriority();
        $statuses = [
            "todo" => "To Do",
            "inprogress" => "In Progress",
            "completed" => "Completed"
        ];

        if ($request->task_id != null) {
            $task = ProjectTask::where('id', $request->task_id)->with('assignees')->first();
            $selectedAssignee = $task->assignees->pluck('id')->toArray();
        } else {
            $task = null;
            $userId = Auth::id();
            $selectedAssignee = (in_array($userId, $check)) ? $userId : null;

        }

        return view('projects.add-task')->with([
            'assignees' => $assignees,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'project_id' => $project_id,
            'project_name' => $projectDetails->project_code.' - '.$projectDetails->name,
            'task' => $task,
            'selectedAssignee' => $selectedAssignee
        ]);
    }

    /**
     * This method is used for detailed view page of particular task
     *
     * @param Request $request
     * @return void
     */
    public function viewTask($id, $taskId)
    {
        $task = ProjectTask::where('id', $taskId)->with('assignees')->first();
        $workLogs = ProjectTaskWorkLog::where('task_id', $taskId)->with('logProjectTask', 'logUser')->orderBy('log_date', 'DESC')->orderBy('created_at', 'DESC')->get();

        $checkComments = ProjectTaskWorkLog::where('task_id', $taskId)->with('logUser')->groupBy('user_id')->pluck('user_id')->toArray();
        $logUsers = (new commonController)->getUserByIds($checkComments);
        $taskLogs = ProjectTaskWorkLog::where('task_id', $taskId)->pluck('log_time')->toArray();
        $totalHours = array_sum($taskLogs);

        if (Auth::user()->role_id != 1) {
            $getAssignee = $task->assignees->pluck('id')->toArray();
            if (in_array(Auth::user()->id, $getAssignee)) {
                $diasbled = '';
            } else {
                $diasbled = 'disabled';
            }
        } else {
            $diasbled = '';
        }
        // dd($getAssignee);
        // dd($diasbled);
        return view('projects.task-details')->with([
            'task' => $task,
            'workLogs' => $workLogs,
            'totalHours' => $totalHours,
            'logUsers' => $logUsers,
            'diasbled' => $diasbled,
        ]);
    }

    /**
     * For delete/remove particular task
     *
     * @param Request $request
     * @return void
     */
    public function deleteTask($id)
    {
        try {
            $task = ProjectTask::where('id', $id)->first();
            $task->delete();

            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Task'])], 200);
        } catch (Exception $e) {

        }
    }

    /**
     * This method is used to store data of task at the time of add and update
     *
     * @param Request $request
     * @return void
     */
    public function storeTask(Request $request)
    {
        try {

            $validate = $this->validationOfStoreTask($request);
            if ($validate->fails()) {
                return back()->withInput()->withErrors($validate);
            }

            if ($request->task_id) {
                $task = ProjectTask::where('id', $request->task_id)->with('assignees')->first();
                $message = trans('messages.MSG_UPDATE',['name' => 'Task Details']);
                // dd($task);
                $task->name = $request->name;
                $task->description = $request->description;
                $task->project_id = $request->project_id;
                $task->assignee_ids = null;
                $task->priority_id = $request->priority;
                $task->status = $request->status;
                $task->save();

                $task->assignees()->sync($request->assignees);

            } else {
                $task = new ProjectTask;
                $message = trans('messages.MSG_SUCCESS',['name' => 'Task Details']);

                $task->name = $request->name;
                $task->description = $request->description;
                $task->project_id = $request->project_id;
                // $task->assignee_ids = $request->assignees ? implode(',', $request->assignees) : null;
                $task->assignee_ids = null;
                $task->priority_id = $request->priority;
                $task->status = $request->status;
                $task->save();

                if (isset($request->assignees) && !empty($request->assignees)) {
                    foreach ($request->assignees as $assignee) {
                        $saveAssignee = new TaskAssignee;
                        $saveAssignee->task_id = $task->id;
                        $saveAssignee->assignee_id = $assignee;
                        $saveAssignee->save();
                    }
                }
            }



            Session::put('selectedTab', '2');
            return redirect()->route('project-details', $request->project_id)->with([
                'message' => trans($message)
            ]);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Validation for save/update task
     *
     * @param Request $request
     * @return void
     */
    public function validationOfStoreTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'description' => 'required',
            'assignees' => 'required',
            'priority' => 'required',
        ], [
            'assignees.required' => 'The assignee field is required.'
        ]);

        return $validator;
    }

    /**
     * For save work log data of particular task in database at the time of add/update
     *
     * @param Request $request
     * @return void
     */
    public function saveWorkLogOfTask(Request $request)
    {
        try {

            $validate = $this->validationOfSaveWorkLog($request);
            $validate->after(function ($validate) use($request) {
                if ($request->log_hours == "0" && $request->log_minutes == "00") {
                    $validate->errors()->add('log_time', 'Please Select Log Time.');
                }
            });
            $validate->validate();

            if ($request->log_id) {
                $workLog = ProjectTaskWorkLog::where('id', $request->log_id)->first();
                $message = trans('messages.MSG_UPDATE',['name' => 'Work Log of Task']);
            } else {
                $workLog = new ProjectTaskWorkLog;
                $message = trans('messages.MSG_SUCCESS',['name' => 'Work Log of Task']);
            }
            $workLog->task_id = $request->task_id;
            $workLog->user_id = $request->user_id ? $request->user_id : Auth::user()->id;
            $workLog->log_date = $request->log_date;
            $workLog->log_time = number_format($request->log_hours+$request->log_minutes,2);
            $workLog->description = $request->description;
            $is_date_changed = $workLog->isDirty('log_date'); // Check if the joining date is
            if($is_date_changed && !empty($workLog->getOriginal('log_date'))) {
                $old_date = Carbon::parse($workLog->getOriginal('log_date'))->format('Y-m-d');
            }
            $workLog->save();

            // Start: Update the EOD entry in daily_task table entry
            $new_request = new Request; // Create new request object for maintain the list of inputs for the automationController's method
            $automationController = new AutomationController();

            $new_request['date'] = (!empty($workLog->log_date)) ? $workLog->log_date->format('Y-m-d') : '';
            $new_request['user_id'] = $workLog->user_id;
            $automationController->updateEodEntry($new_request);

            // When the date change at that time need to update the old date entry as well
            if($is_date_changed && isset($old_date)) {
                $new_request['date'] = $old_date;
                $automationController->updateEodEntry($new_request);
            }
            // End: Update the EOD entry in daily_task table entry

            if ($request->ajax()) {
                return response()->json(['message' => $message], 200);
            } else {
                return redirect()->route('view-project-task', [$request->project_id, $request->task_id])->with(['message' => $message]);
            }

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Validation for save/update worklog
     *
     * @param Request $request
     * @return void
     */
    public function validationOfSaveWorkLog($request)
    {
        $messages = [
            'log_date.required' => 'Please Select Log Date.',
            'log_time.required' => 'Please Enter Log Time.',
            'description.required' => 'Please Enter Work Description.'
        ];
        $validator = Validator::make($request->all(), [
            'log_date' => 'required',
            'description' => 'required',
        ], $messages);

        return $validator;
    }

    /**
     * For get details of particular work log of task
     *
     * @param Request $request
     * @return void
     */
    public function getWorkLogOfTask(Request $request)
    {
        try {
            $workLog = ProjectTaskWorkLog::where('id', $request->id)->first();
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $workLog->log_date)->format('d-m-Y');
            $workLog->modified_log_date = $date;
            return response()->json($workLog);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * For delete particular work log of task
     *
     * @param Request $request
     * @return void
     */
    public function deleteWorkLogOfTask(Request $request)
    {
        try {
            $workLog = ProjectTaskWorkLog::where('id', $request->id)->first();
            // Start: Update the EOD entry in daily_task table entry
            $new_request = new Request; // Create new request object for maintain the list of inputs for the automationController's method
            $automationController = new AutomationController();
            $new_request['date'] = (!empty($workLog->log_date)) ? $workLog->log_date->format('Y-m-d') : '';
            $new_request['user_id'] = $workLog->user_id;
            $workLog->delete();

            $automationController->updateEodEntry($new_request); // Update the EOD entry

            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Work Log of Task'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * For manage selected tab on view
     *
     * @param Request $request
     * @return void
     */
    public function changeValueOfSelectedTab(Request $request)
    {
        try {
            $tabValue = $request->tab_no;
            Session::put($request->tab_name, $tabValue);

            return response()->json(['success' => $tabValue], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * This method is used for check new counts of status after ajax call of delete
     *
     * @param [type] $projectId
     * @return void 
     */
    public function getNewDetailsOfTask($projectId)
    {
        try {
            $projectTasks = ProjectTask::where('project_id', $projectId)->get();
            $taskIds = $projectTasks->pluck('id')->toArray();
            $taskLogs = ProjectTaskWorkLog::whereIn('task_id', $taskIds)->pluck('log_time')->toArray();
            $updatedTaskHours = array_sum($taskLogs);

            $statusCounts = ProjectTask::where('project_id', $projectId)->groupBy('status')->select('status', DB::raw('count(*) as count'))->pluck('count', 'status');
            $todoTaskCount = $statusCounts['todo'] ?? 0;
            $inprogressTaskCount = $statusCounts['inprogress'] ?? 0;
            $completedTaskCount = $statusCounts['completed'] ?? 0;

            $data = [
                'updatedTaskHours' => $updatedTaskHours,
                'todoTaskCount' => $todoTaskCount,
                'inprogressTaskCount' => $inprogressTaskCount,
                'completedTaskCount' => $completedTaskCount,
            ];
            return response()->json(['data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * This method is used for get details of worklog of particular user
     * @param [type] $taskId
     * @param Request $request
     * @return void
     */
    public function worklogOfParticularUser($taskId, Request $request)
    {
        try {
            $workLogs = ProjectTaskWorkLog::where('task_id', $taskId)
                ->with('logProjectTask', 'logUser');
            if ($request->user_id <> 0) {
                $userId = $request->user_id;
                $workLogs->whereHas('logUser', function ($query) use ($userId) {
                    $query->where('id',  $userId);
                });
            }

            if(!empty($request->start_date)) {
                $date = Carbon::parse($request->start_date)->format('Y-m-d');
                $workLogs->where('log_date','>=',$date);
            }

            if(!empty($request->end_date)) {
                $date = Carbon::parse($request->end_date)->format('Y-m-d');
                $workLogs->where("log_date","<=",$date);
            }

            $workLogs =  $workLogs->orderBy('id', 'DESC')->get();
            $totalHours = $workLogs->sum('log_time');

            $data = [
                'totalHours' => $totalHours,
                'html' => view("projects.task-comments", compact('workLogs'))->render()
            ];
            return response()->json(['data' => $data], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function setDataInPivot(Request $request)
    {
        try {
            $tasks = ProjectTask::get();
            foreach ($tasks as $task) {
                if ($task->assignee_ids != null) {
                    $assigneeIds = explode(',', $task->assignee_ids);
                    foreach ($assigneeIds as $assignee) {
                        // For revert :-
                        // $task->assignees()->sync($request->assignees);
                        $find = TaskAssignee::where('task_id', $task->id)->where('assignee_id', $assignee)->first();
                        if (isset($find) && !empty($find)) {
                            Log::info("Already exist in TaskAssignee table ");
                        } else {
                            // Log::info("Not exist in TaskAssignee table : Task Id:" .$task->id.' Assignee Id: '.$assignee);
                            $save = new TaskAssignee;
                            $save->task_id = $task->id;
                            $save->assignee_id = $assignee;
                            $save->save();
                            Log::info("New entry created");
                        }
                    }
                } else {
                    Log::info("assignee_ids is null for this task.");
                }
            }
            return "success!!";
        } catch (Exception $e) {
            Log::error("error : ".$e);
            return "error : ".$e;
        }

    }

}
