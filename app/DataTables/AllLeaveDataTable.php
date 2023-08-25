<?php

namespace App\DataTables;

use App\Helpers\Helper;
use App\Http\Controllers\commonController;
use App\Models\Leave;
use App\Models\LeaveComment;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AllLeaveDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $count_query = clone $query;
        $leaveCounts = Helper::getLeaveCounts($this->applyFilter($count_query, true));
        $leaveCountsHtml = view('leaves.common.filter_leave_counts',compact('leaveCounts'))->render();
        $loggedIn_user_roles  = Auth::user()->userRole->code;
        return datatables()
            ->eloquent($query)
            ->with(['leaveCounts' => $leaveCountsHtml ])
            ->addIndexColumn()
            ->editColumn('userLeave.full_name', function ($row) {
                if (isset($row->userLeave)) {
                    $fullName = $row->userLeave->full_name ? $row->userLeave->full_name : '';
                } else {
                    $fullName = '';
                }
                return ucwords($fullName);
            })
            ->editColumn('type', function ($row) {
                return ($row->type == "full")  ? "Full Day" : "Half Day";
            })
            ->editColumn('status', function ($row) use ($loggedIn_user_roles) {
                if ($row->status == "pending") {
                    $html = "<span class='badge badge-light-warning'>Pending</span>";
                    if(in_array($loggedIn_user_roles, ['ADMIN', 'PM', 'HR']) && $row->comments_count) {
                        $html .= "<i class='mdi mdi-alert-circle text-danger'></i>";
                    }
                    return $html;
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
            })->rawColumns(['status', 'action', 'userLeave.full_name'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AllLeaveDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Leave $model)
    {
        $data = $model->with('userLeave')->select('leaves.*', 'leaves.id as leave_id')->where('request_from','!=', Auth::user()->id)->withCount('comments')->financialYear(request('fyear'));
        return $data;
    }

    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query, $skip_status_filter = false)
    {

        //Query for set leave status
        if (request()->status != "nothing" && !$skip_status_filter) {
            $query = $query->where('status', request()->status);
        }

        // Query for set team
        if (request()->team != 0) {
            $query =  $query->whereHas('userTeam', function ($qry) {
                    $qry->where('team_id', request()->team);
                });
        }

        // Query for set date
        if (request()->date != null) {
            $date = Carbon::parse(request()->date)->format('Y-m-d');
            $query = $query->where("start_date", "<=", $date)->where("end_date", ">=", $date);
        }

        // Query for set financial year
         if (request()->search['value'] != null) {
            $query = $query->whereHas('userLeave', function ($qry) {
                $qry->where('full_name', 'LIKE', '%' . request()->search['value'] . '%');
                });
                // ->whereIn('leaves.id', $userIDs);
                // $query = $query->where('last_name', 'LIKE', '%' . request()->search['value'] . '%');
        }
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
                    $qry->orderBy('first_name', request()->order[0]['dir']);
                });
            }
        }
        return $query;
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'leaveallemp.edit', 'link' => route('leave-add-all', $row->leave_id),'disablePopupClass' => true],
            // ['btn_file_name' => 'cancel', 'permission' => 'leaveallemp.destroy', 'link' => route('leave-cancel-all', $row->leave_id),],
            ['btn_file_name' => 'cancelWithCommentLeave', 'permission' => 'leaveallemp.destroy', 'link' => $row->leave_id ],
            ['btn_file_name' => 'view', 'permission' => 'leaveallemp.view', 'link' => route('leave-view-all', $row->leave_id)],
        ]);
        if($row->status == 'cancelled'){
            $options->forget(0);
            $options->forget(1);
        }
        return view('common.action-dropdown', compact('options'))->render();
    }
}
