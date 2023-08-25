<?php

namespace App\Http\Controllers;

use App\Models\ProjectTaskWorkLog;
use Illuminate\Http\Request;
use App\Models\TaskAssignee;
use App\Models\ProjectTask;
use App\Models\Project;
use Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskDashboardController extends Controller
{
    public function index()
    {
        $taskDetails = TaskAssignee::where('assignee_id', Auth::user()->id)->with('assigneeTask')->get();
        $task = ProjectTask::with('assignees')->whereHas('assignees', function ($query)
        {
            $query->where('assignee_id', Auth::user()->id);
        })->get();
        $modalData = [];

        $getCount = ProjectTask::with('assignees')->whereHas('assignees', function($q){
                        $q->where('assignee_id', Auth::user()->id);
                    })->select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');

        $totalTask = array_sum($getCount->toArray());
        $todoTask = $getCount['todo'] ?? 0;
        $inprogressTask = $getCount['inprogress'] ?? 0;
        $completedTask = $getCount['completed'] ?? 0;

        return view('task-dashboard.index')->with(['taskDetails' => $taskDetails, 'modalData' => $modalData, 'totalTask' => $totalTask, 'todoTask' => $todoTask, 'inprogressTask' => $inprogressTask, 'completedTask' => $completedTask]);
    }

    public function taskTableDetails()
    {
        $taskDetails = ProjectTask::where('status', '!=', 'completed')->with('assignees')->whereHas('assignees', function ($query)
        {
            $query->where('assignee_id', Auth::user()->id);
        })->orderBy('id', 'desc')->get();
        return Datatables::of($taskDetails)
                    ->addIndexColumn()
                    ->editColumn('id', function($taskDetails){
                        return $taskDetails->id;
                    })
                    ->editColumn('project_name', function($taskDetails){
                        return '<div class="project-name">'.$taskDetails->project->name.'</div>';
                    })
                    ->editColumn('name', function($taskDetails){
                        return '<a href="javascript:void(0);" data-project-id="'.$taskDetails->project_id.'" data-task-id="'.$taskDetails->id.'" class="task-name" data-url="'.route("save-work-log-of-task", $taskDetails->project_id).'" onclick="firstmodal(this)" data-project="'.$taskDetails->project->name .'" data-task="'.$taskDetails->name.'">'.$taskDetails->id.' - '.$taskDetails->name.'</a>';
                    })
                    ->editColumn('action', function($taskDetails){
                        return "<a href='".route('view-project-task', ['id' => $taskDetails->project_id, 'task_id' => $taskDetails->id])."' target='_blank' >View Task</a>";
                    })
                    ->rawColumns(['name','project_name','action'])
                    ->make(true);

    }

    /**
     * for get total working hours date wise of a month
     *
     * @param  mixed $request
     * @return void
     */
    public function getMonthlyWorkingHours(Request $request)
    {
        try {
            $month = $request->month;
            if(empty($month)) {
                $month = now()->month;
            }

            $workingHours = ProjectTaskWorkLog::has('logProjectTask')->select(DB::raw('DATE_FORMAT(log_date,"%Y-%m-%d") as date'),DB::raw('round(sum(log_time),2) as total_hours'))
                ->where('user_id',auth()->id())
                ->whereMonth('log_date',$month)
                ->groupBy('log_date')
                ->get();

            return response()->json($workingHours);
        } catch (\Throwable $th) {
            Log::error('Getting error while getting total working hours date wise:- '.$th);
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function getDateWiseTaskDetails(Request $request)
    {
        try {
            if ($request->ajax()) {
                $modalData = $this->getWorkLogDetails($request);
                $date = $request->date;
                $userId = ($request->id) ?$request->id :Auth::user()->id;
                $hours = ProjectTaskWorkLog::where('user_id', $userId)->whereDate('project_task_work_logs.log_date', $date)->pluck('log_time')->toArray();
                $totalHours = array_sum($hours);

                $data = [
                    'totalHours' => number_format($totalHours,2),
                    'html' => view("task-dashboard.details-modal-content", compact('modalData'))->render()
                ];
                return response()->json($data);
            }
        } catch (Exception $e) {

        }
    }

    public function getWorkLogDetails(Request $request)
    {
        $date = $request->date;
        $user_id = ($request->id) ? $request->id : $request->input('user_id', Auth::id());

        $modalData = Project::with(
        ['tasks' => function ($query) use($date, $user_id) {
            $query->whereHas('worklogs', function($q) use($date, $user_id){
                $q->whereDate('project_task_work_logs.log_date', $date)
                ->where('project_task_work_logs.user_id', $user_id);
            });
        },
        'tasks.assignees',
        'tasks.worklogs' => function ($query) use($date, $user_id) {
            $query->where('log_date', $date)->where('project_task_work_logs.user_id', $user_id);
        }])
        ->whereHas('tasks', function($q) use($date, $user_id) {
            $q->whereHas('worklogs', function($q1) use($date, $user_id){
                $q1->whereDate('project_task_work_logs.log_date', $date)
                ->where('project_task_work_logs.user_id', $user_id);
            });
        })
        ->whereHas('tasks.assignees', function($q) use($user_id){
            $q->where('assignee_id', $user_id);
        })
        ->whereHas('tasks.worklogs', function($q) use($date){
            $q->whereDate('project_task_work_logs.log_date', $date);
        })
        ->get();

        foreach ($modalData as $detail) {
            $taskTime = 0;
            foreach ($detail->tasks as $value) {
                $time = $value->worklogs->pluck('log_time')->toArray();
                $projectTime = array_sum($time);
                $taskTime += $projectTime;
            }
            $detail->worklog_time = $taskTime;
        }
        return $modalData;
    }
}
