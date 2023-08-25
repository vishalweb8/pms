<?php

namespace App\DataTables;

use App\Helpers\Helper;
use App\Models\WorkFromHome;
use App\Models\WfhComment;
use App\Http\Controllers\commonController;
use Yajra\DataTables\Services\DataTable;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeamWfhDataTable extends DataTable
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
        DB::enableQueryLog();
        $wfhCounts = Helper::getLeaveCounts($this->applyFilter($count_query));
        // dump(DB::getQueryLog()); exit();
        $wfhCountsHtml = view('wfh.common.wfh-count-box',compact('wfhCounts'))->render();

        return datatables()
            ->eloquent($query)
            ->with(['wfhCounts' => $wfhCountsHtml ])
            ->addIndexColumn()
            ->editColumn('userWfh.full_name', function ($row) {
                if((isset($row->userWfh->first_name) || isset($row->userWfh->last_name))){
                    $full_name =  isset($row->userWfh->first_name) ? $row->userWfh->first_name : '';
                    if(isset($row->userWfh->last_name) && !empty($row->userWfh->last_name)) {

                        $full_name .=  ' ' . $row->userWfh->last_name;
                    }
                    return ucwords($full_name);
                }else{
                    return '';
                }
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
                    $html = "<span class='badge badge-light-warning'>Pending</span>";
                    if(in_array(Auth::user()->userRole->code, ['ADMIN', 'PM', 'HR']) && $row->comments_count) {
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
    public function query(WorkFromHome $model)
    {
        $userIDs = (new commonController)->listTeamRequests(Auth::user()->id,$model);
        $data = $model->with('userWfh')->where('user_id','!=', Auth::user()->id)->select('wfh_requests.*', 'wfh_requests.id as wfh_id')->withCount('comments');

        if (!in_array(Auth::user()->userRole->code, ['ADMIN', 'PM', 'HR'])) {
            $data = $data->whereIn('wfh_requests.id', $userIDs);
        }
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
            });
        }else{
            $query->where(function($qry){
                $qry = $qry->whereYear('start_date',Carbon::now()->addYear()->format('Y'))
                ->orWhereYear('start_date',Carbon::now()->format('Y'));
            });
        }
        if(request()->wfhType != null){
            $query->where('status','=',request()->wfhType);
        }

        if(request()->search['value'] != null){
            $query
            ->whereHas('userWfh',function($qry){
                $qry->where('full_name','LIKE','%'.request()->search['value'].'%');
            })
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
        $wfhComment = WfhComment::where('wfh_id', $row->id)->count();

        $options = collect([
            // ['btn_file_name' => 'edit', 'permission' => 'wfh.edit', 'link' => route('wfh-edit-team', $row->id)],
            // ['btn_file_name' => 'cancel', 'permission' => 'wfh.cancel', 'link' => route('wfh-cancel', $row->id)],
            ['btn_file_name' => 'view', 'permission' => 'wfhteam.view', 'link' => route('wfh-team-view', $row->id)],
        ]);

        return view('common.action-dropdown', compact('options'))->render();
    }
}
