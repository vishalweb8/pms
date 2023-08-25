<?php

namespace App\DataTables;

use App\Models\ProjectTask;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Http\Controllers\commonController;
use Helper;
use Auth;
use Illuminate\Http\Request;

class ProjectTaskDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $logged_in_user = Auth::user();
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('name', function($row){
                return '<a href="'.route("view-project-task", [$row->project_id, $row->id]).'">'.$row->id.' - '.$row->name.'</a>';
                // return '<a href="'.route("edit-project-task", [$row->project_id, $row->id]).'">'.$row->id.' - '.$row->name.'</a>';
            })
            ->editColumn('assignee_ids', function($row){
                // return isset($row->assignee_ids) ? implode(', ',(new commonController)->getUserByIds(explode(',',$row->assignee_ids))) : '-';
                $assigneeIds = $row->assignees->pluck('full_name')->toArray();
                $assigneeNames = implode(', ', $assigneeIds);
                return $assigneeNames;
            })
            ->editColumn('date', function($row){
                return $row->created_at->format('d-m-Y');
            })
            ->editColumn('priority', function($row){
                if(isset($row->taskPriority)){
                    switch($row->taskPriority->name){
                        case 'Low' :
                            return '<badge class="badge badge-light-default">'.$row->taskPriority->name.'</badge>';
                            break;
                        case 'Medium' :
                            return '<badge class="badge badge-light-info">'.$row->taskPriority->name.'</badge>';
                            break;
                        case 'High' :
                            return '<badge class="badge badge-light-danger">'.$row->taskPriority->name.'</badge>';
                            break;
                        case 'Done' :
                            return '<badge class="badge badge-light-success">'.$row->taskPriority->name.'</badge>';
                            break;
                        default :
                            return '<badge class="badge badge-light-info">'.$row->taskPriority->name.'</badge>';
                            break;
                    }
                }
                else
                {
                    return '';
                }
            })
            ->editColumn('status', function($row){
                if ($row->status == "todo") {
                    return "<span class='badge badge-light-default'>To Do</span>";
                }
                if ($row->status == "inprogress") {
                    return "<span class='badge badge-light-info'>In Progress</span>";
                }
                if ($row->status == "completed") {
                    return "<span class='badge badge-light-success'>Completed</span>";
                }
            })
            ->addColumn('action', function ($row) use ($logged_in_user) {
                return $this->actionColumn($row, $logged_in_user);
            })
            ->rawColumns(['name', 'priority','status', 'action'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProjectTaskDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProjectTask $model, Request $request)
    {
        // return $model->newQuery();
        $project_id = $request->route('id');
        $data = $model->with(['assignees'])->where('project_id', $project_id);
        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('projecttaskdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('add your columns'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ProjectTask_' . date('YmdHis');
    }

    public function applyFilter($query)
    {
        // dd(request());
        $model = new ProjectTask();

        if(request()->search['value'] != null){
           $query->where('name','LIKE','%'.request()->search['value'].'%');
        }

        if(request()->status != null){
           $query->where('status', request()->status);
        }

        if(request()->priority != null){
           $query->where('priority_id', request()->priority);
        }

        if (request()->date != null) {
            $string = strtotime(request()->date);
            $date = date('Y-m-d', $string);
            $query->where('created_at', 'like', $date.'%');
        }

        return $query;
    }

    public function actionColumn($row, $logged_in_user)
    {
        // dump($row); exit();
        // Check the current user is the assignee of the project task or not
        if($logged_in_user->roles()->whereIn('code', ['EMP'])->count() && ($row->assignees->count())) {
            $assignee_list = $row->assignees->pluck('id')->toArray();
            if(!in_array($logged_in_user->id, $assignee_list)) {
                return '';
            }
        }
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'project-task.edit', 'link' => route('edit-project-task',[$row->project_id, $row->id]),'disablePopupClass' => true],
            ['btn_file_name' => 'delete', 'permission' => 'project-task.destroy', 'link' => route('delete-project-task', $row->id)],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }
}
