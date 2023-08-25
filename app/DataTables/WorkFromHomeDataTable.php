<?php

namespace App\DataTables;

use App\Http\Controllers\commonController;
use App\Models\WorkFromHome;
use App\Models\WfhComment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class WorkFromHomeDataTable extends DataTable
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
            ->editColumn('userWfh.full_name', function ($row) {
                return $full_name = $row->userWfh->full_name;
            })
            ->editColumn('wfh_type', function ($row) {
                return ($row->wfh_type == "full")  ? "Full Day" : "Half Day";
            })
            ->editColumn('comments', function ($row) {
                $html = '';
                if($row->comments_count) {
                    $show_url = route('wfh-comments-view', $row->id);
                    $html .= "<button class='btn btn-info show-comments' data-url='{$show_url}'> View ({$row->comments_count}) </button>";
                }
                return $html;
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
            })->rawColumns(['status', 'action', 'comments'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\WorkFromHome $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(WorkFromHome $model)
    {
        $data = $model->with(['userWfh', 'comments'])->where('user_id', Auth::user()->id)
                    ->select('wfh_requests.*', 'wfh_requests.id as wfh_id')
                    ->withCount('comments');
        return $data;
    }

    public function applyFilter($query)
    {
        $model = new WorkFromHome();
        $userIDs = (new commonController)->listTeamRequests(Auth::user()->id,$model);


        if(request()->fyear != null){
            $financial_year = explode('-',request()->fyear);
            $startYear = $financial_year[0];
            $endYear = $financial_year[1];

            $query->where(function($qry) use($startYear,$endYear){
                $qry = $qry->whereYear('start_date',$startYear)
                ->orWhereYear('start_date',$endYear);
            })
            ->where('user_id',Auth::user()->id);
        }else{
            $query->where(function($qry){
                $qry = $qry->whereYear('start_date',Carbon::now()->addYear()->format('Y'))
                ->orWhereYear('start_date',Carbon::now()->format('Y'));
            })->where('user_id',Auth::user()->id);
        }
        if(request()->search['value'] != null){
            $query
            ->orWhereHas('userWfh',function($qry){
                $qry->where('full_name','LIKE','%'.request()->search['value'].'%');
            })
            ->where('user_id',Auth::user()->id)
            ->orWhereYear('start_date','LIKE','%'.request()->search['value'].'%')
            ->whereIn('wfh_requests.id', $userIDs);
        }
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order ]['name'];
            if(Schema::hasColumn('wfh_requests',$column_name)){
                if($column_name == 'duration'){
                    $query->orderByRaw('CAST('.$column_name.' AS DECIMAL)'.request()->order[0]['dir']);
                }else{
                    $query->orderBy($column_name, request()->order[0]['dir']);
                }
            }else{
                $query->WhereHas('userWfh', function ($qry) {
                    $qry->orderBy('full_name', request()->order[0]['dir']);
                });
            }
        }
        return $query;
    }

    public function actionColumn($row)
    {
        // $wfhComment = WfhComment::where('wfh_id', $row->id)->count();
        $wfhComment = $row->comments->count();
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'wfh.edit', 'link' => route('wfh-edit-view', $row->id),'disablePopupClass' => true],
            ['btn_file_name' => 'cancel', 'permission' => 'wfh.destroy', 'link' => route('wfh-cancel', $row->id)],
            ['btn_file_name' => 'view', 'permission' => 'wfh.view', 'link' => route('wfh-view', $row->id)],
        ]);

        if ($wfhComment > 0)
            $options->forget(0);

        if($row->status == 'cancelled'){
            $options->forget(0);
            $options->forget(1);
        }

        if($row->status == 'approved'){
            $options->forget(1);
        }

        return view('common.action-dropdown', compact('options'))->render();
    }
}
