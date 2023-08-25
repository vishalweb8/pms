<?php

namespace App\DataTables;

use App\Models\DailyTask;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DailyTaskManagementAdminDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('userTask.first_name',function($row){
                return ($row->userTask) ? $row->userTask->full_name : '';
            })
            ->editColumn('emp_status', function ($row) {
                return (config('constant.emp_status.' . $row->emp_status)) ? config('constant.emp_status.' . $row->emp_status) : '';
            })
            ->editColumn('project_status', function ($row) {
                return (config('constant.project_type.' . $row->project_status)) ? config('constant.project_type.' . $row->project_status) : '';
            })
            ->addColumn('verified_by_tl',function($row){
                $status = ($row->verified_by_TL == 1) ?  "Yes" : 'No';
                return '<div class="form-check mb-3">'.$status.'</div>';
            })
            ->addColumn('verified_by_admin',function($row){
                $status = ($row->verified_by_Admin == 1) ? "Yes" : 'No';
                return '<div class="form-check mb-3">'.$status.'</div>';
            })
            ->addColumn('action', function ($row) {
                return $this->actionColumn($row);
            })
            ->rawColumns(['sod_description','eod_description','verified_by_tl','verified_by_admin','action'])->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DailyTaskManagementAdminDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DailyTask $model)
    {
        return $model->with('userTask','userTask.officialUser','userTask.officialUser.userTeam')->select('daily_tasks.*');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('dailytaskmanagementadmindatatable-table')
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
        return 'DailyTaskManagementAdmin_' . date('YmdHis');
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'super-admin-project.edit', 'link' => route('edit-daily-task',$row->id),],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }

    public function applyFilter($query)
    {
        if(request()->order[0]['column'] == 1) {
            $query->orderBy('id', 'desc');
        }
        if(request()->project_status != '') {
            $query->where('project_status',request()->project_status);
        }

        if(request()->emp_status != '') {
            $query->where('emp_status',request()->emp_status);
        }
        if(request()->team != '') {
            $query->whereHas('userTask.officialUser.userTeam',function($q){
                return $q->where('id',request()->team);
            });
        }
        if(request()->selected_date != '') {
            $splitedDate = explode('-',request()->selected_date);
            $query->whereYear('current_date',$splitedDate[2])
            ->whereMonth('current_date',$splitedDate[1])
            ->whereDay('current_date',$splitedDate[0]);
        }
        return $query;
    }
}
