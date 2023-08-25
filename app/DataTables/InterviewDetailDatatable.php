<?php

namespace App\DataTables;

use App\Models\InterviewDetail;
use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Helpers\Helper;

class InterviewDetailDataTable extends DataTable
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
            ->editColumn('reference_id', function ($row) {
                return $row->users->first_name;
            })
            ->editColumn('status', function ($row) {
                if($row->status == 1){
                    return '<span class="badge badge-light-warning">Pending</span>';
                }else if($row->status == 2){
                    return '<span class="badge badge-light-success">Selected</span>';
                }else{
                    return '<span class="badge badge-light-danger">Rejected</span>';
                }
            })
            ->addColumn('action', function($row) {
                return $this->actionColumn($row);
            })
            ->rawColumns(['status', 'action','reference_id']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\InterviewDetail $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InterviewDetail $model)
    {
        // DB::statement(DB::raw('set @rownum=0'));
        // return $model->select(['technology.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return $model->select();
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'interview-detail.edit', 'link' => route('interview-detail.edit', $row->id),],
            ['btn_file_name' => 'delete', 'permission' => 'interview-detail.destroy', 'link' => route('interview-detail.destroy', $row->id),],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }

}
