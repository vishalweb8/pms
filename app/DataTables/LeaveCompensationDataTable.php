<?php

namespace App\DataTables;

use App\Http\Controllers\commonController;
use App\Models\LeaveCompensation;
use App\Models\LeaveCompensationComment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class LeaveCompensationDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        // dd(request()->route()->getName());
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('userLeave.full_name', function ($row) {
                return ucwords($row->userLeave->full_name);
            })
            ->editColumn('type', function ($row) {
                return ($row->type == "full")  ? "Full Day" : "Half Day";
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
            // return "action";

                return $this->actionColumn($row);
            })->rawColumns(['status', 'action'])
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
    public function query(LeaveCompensation $model)
    {
        // $endYear = Carbon::now()->addYear()->format('Y');
        // $startYear = Carbon::now()->format('Y');
        $data = $model->with('userLeave')->select('leave_compensation.*')
        // ->where(function($qry) use($startYear,$endYear){
        //     $qry = $qry->whereYear('start_date',$startYear)
        //     ->orWhereYear('end_date',$endYear);
        // })
        ->where('request_from', Auth::user()->id);
        // ->orderBy('id','DESC');
        // $data = $model->select();
        return $data;
    }

     //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {
        $model = new LeaveCompensation();
        $userIDs = (new commonController)->listTeamRequests(Auth::user()->id,$model);

        if(request()->fyear != null){
            $financial_year = explode('-',request()->fyear);
            $startYear = $financial_year[0];
            $endYear = $financial_year[1];

            $query->where(function($qry) use($startYear,$endYear){
                $qry = $qry->whereYear('start_date',$startYear)
                ->orWhereYear('start_date',$endYear);
            })
            ->where('request_from',Auth::user()->id);
        }
        else{
            $query->where(function($qry){
                $qry = $qry->whereYear('start_date',Carbon::now()->addYear()->format('Y'))
                ->orWhereYear('start_date',Carbon::now()->format('Y'));
            })->where('request_from',Auth::user()->id);
        }

        if(request()->search['value'] != null){
            $query
            ->orWhereHas('userLeave',function($qry){
                $qry->where('full_name','LIKE','%'.request()->search['value'].'%');

            })
            ->where('request_from',Auth::user()->id)
            ->orWhereYear('start_date','LIKE','%'.request()->search['value'].'%')
            ->whereIn('leaves.id', $userIDs);
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
                    $qry->orderBy('full_name', request()->order[0]['dir']);
                });
            }
        }
        return $query;
    }

    public function actionColumn($row)
    {
        $leaveComment = LeaveCompensationComment::where('leave_compensation_id', $row->id)->count();

        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'leavecompensation.edit', 'link' => route('leave-add-compensation-view', $row->id),],
            ['btn_file_name' => 'cancel', 'permission' => 'leavecompensation.destroy', 'link' => route('leave-compensation-cancel', $row->id),],
            ['btn_file_name' => 'view', 'permission' => 'leavecompensation.view', 'link' => route('leave-compensation-view', $row->id)],
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
