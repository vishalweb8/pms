<?php

namespace App\DataTables;

use App\Http\Controllers\commonController;
use App\Models\LeaveCompensation;
use App\Models\LeaveCompensationComment;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AllLeaveCompensationDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        // return datatables()
        //     ->eloquent($query)
        //     ->addColumn('action', 'allleavedatatable.action');

        return datatables()
            ->eloquent($query)
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
            ->editColumn('status', function ($row) {
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
            })->rawColumns(['status', 'action'])
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
    public function query(LeaveCompensation $model)
    {
        $data = $model->with('userLeave')->select('leave_compensation.*');
        return $data;
    }

    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {
        $model = new LeaveCompensation();
        // $userIDs = (new commonController)->listTeamRequests(Auth::user()->id, $model);

        if (request()->fyear != null) {
            $financial_year = explode('-', request()->fyear);
            $startYear = $financial_year[0];
            $endYear = $financial_year[1];

            $query->where(function ($qry) use ($startYear, $endYear) {
                $qry = $qry->whereYear('start_date', $startYear)
                    ->orWhereYear('start_date', $endYear);
            });
        }
        else {
            $query->where(function ($qry) {
                $qry = $qry->whereYear('start_date', Carbon::now()->addYear()->format('Y'))
                    ->orWhereYear('start_date', Carbon::now()->format('Y'));
            });
        }

        //Query for set leave status
        if (request()->status != "nothing") {
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
        if(request()->leaveType != null){
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
        $leaveComment = LeaveCompensationComment::where('leave_compensation_id', $row->id)->count();
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'leavecompensationallemp.edit', 'link' => route('leave-add-compensation-all', $row->id),'disablePopupClass' => true],
            // ['btn_file_name' => 'cancel', 'permission' => 'leaveallemp.destroy', 'link' => route('leave-cancel-all', $row->leave_id),],
            ['btn_file_name' => 'cancelWithCommentLeaveCompensation', 'permission' => 'leavecompensationallemp.destroy', 'link' => $row->id ],
            ['btn_file_name' => 'view', 'permission' => 'leavecompensationallemp.view', 'link' => route('leave-view-compensation-all', $row->id)],
        ]);
        if($row->status == 'cancelled'){
            $options->forget(0);
            $options->forget(1);
        }
        return view('common.action-dropdown', compact('options'))->render();
    }
}
