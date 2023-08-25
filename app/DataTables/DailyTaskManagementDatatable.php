<?php

namespace App\DataTables;

use App\Models\DailyTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DailyTaskManagementDatatable extends DataTable
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
            ->editColumn('emp_status', function ($row) {
                return (config('constant.emp_status.' . $row->emp_status)) ? config('constant.emp_status.' . $row->emp_status) : '';
            })
            ->editColumn('project_status', function ($row) {
                return (config('constant.project_type.' . $row->project_status)) ? config('constant.project_type.' . $row->project_status) : '';
            })
            ->addColumn('action', function ($row) {
                return $this->actionColumn($row);
            })
            ->rawColumns(['sod_description','eod_description', 'emp_status','project_status','action'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DailyTaskManagementDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DailyTask $model)
    {


        return $model->with('userTask')->where('user_id',Auth::user()->id)->select();
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'DailyTaskManagement_' . date('YmdHis');
    }
    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'daily-tasks.edit', 'link' => route('work-edit',$row->id), 'disablePopupClass' => true],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }

    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {

        $currentDate = Carbon::now();
        if(request()->day != null){

            $query->whereYear('current_date',request()->year)
                ->whereMonth('current_date',request()->month)
                ->whereDay('current_date',request()->day);
        }
        else if(request()->ready == 'false'){
            $query->whereYear('current_date',request()->year)
                ->whereMonth('current_date',request()->month);
            // $query->whereYear('current_date',$currentDate->format('Y'))
            // ->whereMonth('current_date',$currentDate->format('m'))
            // ->whereDay('current_date',$currentDate->format('d'))
            // ->where('user_id',Auth::user()->id);
        }
        else{
            $query->whereYear('current_date',$currentDate->format('Y'))
            ->whereMonth('current_date',$currentDate->format('m'))
            ->whereDay('current_date',$currentDate->format('d'));

        }
        if(request()->order[0]['column'] == 1) {
            $query->orderBy('id', 'desc');
        }
        return $query;
    }
}
