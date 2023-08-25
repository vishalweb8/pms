<?php

namespace App\DataTables;

use App\Helpers\Helper;
use App\Http\Controllers\commonController;
use App\Models\WfhComment;
use App\Models\WorkFromHome;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AllEmpWFHDatatable extends DataTable
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
        $wfhCounts = Helper::getWFHCounts();
        $wfhCountsHtml = view('wfh.common.wfh-count-box',compact('wfhCounts'))->render();

        return datatables()
            ->eloquent($query)
            ->with(['wfhCounts' => $wfhCountsHtml ])
            ->addIndexColumn()
            ->editColumn('userWfh.full_name', function ($row) {
                return $row->userWfh ? ucwords($row->userWfh->full_name) : '';
            })
            ->editColumn('wfh_type', function ($row) {
                return ($row->wfh_type == "full")  ? "Full Day" : "Half Day";
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
            })->rawColumns(['status', 'action'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AllEmpWFHDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(WorkFromHome $model)
    {
        // $userIDs = (new commonController)->listTeamRequests(Auth::user()->id,$model);
        $data = $model->with('userWfh')->where('user_id','!=', Auth::user()->id)->select('wfh_requests.*', 'wfh_requests.id as wfh_id')->withCount('comments');

        // if (!in_array(Auth::user()->userRole->code, ['ADMIN', 'PM', 'HR'])) {
        //     $data = $data->whereIn('id', $userIDs);
        // }
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
                    ->setTableId('allempwfhdatatable-table')
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
        return 'AllEmpWFH_' . date('YmdHis');
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
        if(request()->order[0]['column'] == 0) {
            $query->orderBy('id', 'desc');
        }

        //Query for set wfh status
        if (request()->status != "nothing") {
            $query->where('status', request()->status);
        }

         // Query for set team
         if (request()->team != 0) {
            $query->whereHas('userTeam', function ($qry) {
                    $qry->where('team_id', request()->team);
                });
        }
        // Query for set date
        if (request()->date != null) {
            $date = Carbon::parse(request()->date)->format('Y-m-d');
            $query = $query->where("start_date", "<=", $date)->where("end_date", ">=", $date);
        }
        if(request()->wfhType != null){
            $query->where('status','=',request()->wfhType);
        }
        if(request()->search['value'] != null){
            $query->whereHas('userWfh',function($qry){
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
            ['btn_file_name' => 'edit', 'permission' => 'wfhallemp.edit', 'link' => route('wfh-all-emp-edit', $row->id)],
            // ['btn_file_name' => 'cancel', 'permission' => 'wfhallemp.destroy', 'link' => route('wfh-all-emp-cancel', $row->id)],
            ['btn_file_name' => 'cancelWithCommentWFH', 'permission' => 'wfhallemp.destroy', 'link' => $row->id ],
            ['btn_file_name' => 'view', 'permission' => 'wfhallemp.view', 'link' => route('wfh-all-emp-view', $row->id)],
        ]);

        if($row->status == 'cancelled'){
            $options->forget(0);
            $options->forget(1);
        }
        return view('common.action-dropdown', compact('options'))->render();
    }
}
