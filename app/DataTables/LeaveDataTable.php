<?php

namespace App\DataTables;

use App\Helpers\Helper;
use App\Http\Controllers\commonController;
use App\Models\Leave;
use App\Models\LeaveComment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class LeaveDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $requestFrom = [Auth::id()];
        $count_query = clone $query;
        $leaveCounts = Helper::getLeaveCounts($this->applyFilter($count_query, true));
        $leaveCountsHtml = view('leaves.common.filter_leave_counts',compact('leaveCounts'))->render();
        return datatables()
            ->eloquent($query)
            ->with(['leaveCounts' => $leaveCountsHtml ])
            ->addIndexColumn()
            ->editColumn('userLeave.full_name', function ($row) {
                return ucwords($row->userLeave->full_name);
            })
            ->editColumn('type', function ($row) {
                return ($row->type == "full")  ? "Full Day" : "Half Day";
            })
            ->editColumn('comments', function ($row) {
                $html = '';
                if ($row->comments_count) {
                    $show_url = route('leaves-comments-view', $row->id);
                    $html .= "<button class='btn btn-info show-comments' data-url='{$show_url}'> View ({$row->comments_count}) </button>";
                }
                return $html;
            })
            ->editColumn('status', function ($row) {
                // return $row->status;
                if ($row->status == "pending") {
                    return "<span class='badge badge-light-warning'>Pending</span>";
                }
                if ($row->status == "approved") {
                    return "<span class='badge badge-light-success'>Approved</span>";
                }
                if ($row->status == "rejected") {
                    return "<span class='badge badge-light-danger'>Rejected</span>";
                }
                if ($row->status == "cancelled") {
                    return "<span class='badge badge-light-info'>Cancelled</span>";
                }

            })->addColumn('action', function ($row) {
                return $this->actionColumn($row);
            })->rawColumns(['status', 'action', 'comments'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Leave $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Leave $model)
    {
        $data = $model->with('userLeave')
        ->financialYear(request('fyear'))
        ->select('leaves.*','leaves.id as leave_id')
        ->withCount('comments')
        ->where('request_from', Auth::id());

        return $data;
    }

     //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query, $skip_status_filter = false)
    {
        if(request()->leaveType != null && !$skip_status_filter){
            $query->where(function($qry){
                $qry = $qry->where('status','=',request()->leaveType);
            });
        }
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order ]['name'];
            if(Schema::hasColumn('leaves',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }else{
                $query->WhereHas('userLeave', function ($qry) {
                    $qry->orderBy('full_name', request()->order[0]['dir']);
                });
            }
        }
        return $query;
    }

    public function actionColumn($row)
    {
        $leaveComment = LeaveComment::where('leave_id', $row->id)->count();

        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'leave.edit', 'link' => route('leave-add-view', $row->id),'disablePopupClass' => true],
            ['btn_file_name' => 'cancel', 'permission' => 'leave.destroy', 'link' => route('leave-cancel', $row->id),],
            ['btn_file_name' => 'view', 'permission' => 'leave.view', 'link' => route('leave-view', $row->id)],
        ]);

        if($leaveComment > 0)
            $options->forget(0);

        if($row->status == 'cancelled'){
            $options->forget(0);
            $options->forget(1);
        }

        if($row->status == 'approved'){
            $options->forget(0);
            $options->forget(1);
        }

        return view('common.action-dropdown', compact('options'))->render();
    }
}
