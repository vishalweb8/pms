<?php

namespace App\DataTables;

use App\Http\Controllers\commonController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Helpers\Helper;
use DB;
use Illuminate\Support\Facades\Log;

class AllWFHStatisticsDataTable extends DataTable
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
            ->editColumn('full_name', function ($row) {
               return $row->full_name;
            })

            ->editColumn('approved', function ($row) {
                return $row->approved_wfh;
                $used = Helper::usedWFH($row->id)->total_duration  ?
                Helper::usedWFH($row->id)->total_duration  : '0';
                return $used;
            })
            ->editColumn('rejected', function ($row) {
                return $row->rejected_wfh;
                $rejected = Helper::rejectedWFH($row->id)->total_duration  ?
                Helper::rejectedWFH($row->id)->total_duration  : '0';
                return $rejected;
            })
            ->editColumn('cancelled', function ($row) {
                return $row->cancelled_wfh;
                $cancel = Helper::cancelledWFH($row->id)->total_duration  ?
                Helper::cancelledWFH($row->id)->total_duration  : '0';
                return $cancel;
            })
            ->editColumn('pending', function ($row) {
                return $row->pending_wfh;
                $pending = Helper::pendingWFH($row->id)->total_duration  ?
                Helper::pendingWFH($row->id)->total_duration  : '0';
                return $pending;
            })
            ->editColumn('total', function ($row) {
                return $row->total_wfh;
                $total = Helper::totalReqWFH($row->id)->total_duration  ?
                Helper::totalReqWFH($row->id)->total_duration  : '0';
                return $total;
            })
            ->rawColumns(['used']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AllEmpWFHDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
     /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AllLeaveDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        if (Auth::user()->isManagement()) {
            $model = $model->withTrashed();
        }
        $financial_year_dates = Helper::getFinancialYearDates();

        if(request()->fyear != null){
            $financial_year = explode('/',request()->fyear);
            $startYear = $financial_year[0];
            $endYear = $financial_year[1];

        }else{
            $startYear = $financial_year_dates['start_date'];
            $endYear = $financial_year_dates['end_date'];
        }

        return $model->select('id','first_name','last_name', 'full_name', 'created_at')
                    ->withcount(['workFromHome as approved_wfh' => function ($query) use($startYear, $endYear) {
                        $query->select(DB::raw("COALESCE(SUM(duration), 0) as approved_wfh"), 'start_date')->where('status', 'approved')
                        ->whereDate('start_date', '>=', $startYear)
                        ->whereDate('start_date', '<=', $endYear);
                    }])
                    ->withcount(['workFromHome as pending_wfh' => function ($query) use($startYear, $endYear) {
                        $query->select(DB::raw("COALESCE(SUM(duration),0) as pending_wfh"))->where('status', 'pending')
                        ->whereDate('start_date', '>=', $startYear)
                        ->whereDate('start_date', '<=', $endYear);
                    }])
                    ->withcount(['workFromHome as rejected_wfh' => function ($query) use($startYear, $endYear) {
                        $query->select(DB::raw("COALESCE(SUM(duration),0) as rejected_wfh"))->where('status', 'rejected')
                        ->whereDate('start_date', '>=', $startYear)
                        ->whereDate('start_date', '<=', $endYear);
                    }])
                    ->withcount(['workFromHome as cancelled_wfh' => function ($query) use($startYear, $endYear) {
                        $query->select(DB::raw("COALESCE(SUM(duration),0) as cancelled_wfh"))->where('status', 'cancelled')
                        ->whereDate('start_date', '>=', $startYear)
                        ->whereDate('start_date', '<=', $endYear);
                    }])
                    ->withcount(['workFromHome as total_wfh' => function ($query) use($startYear, $endYear) {
                        $query->select(DB::raw("COALESCE(SUM(duration),0) as total_wfh"))
                        ->whereDate('start_date', '>=', $startYear)
                        ->whereDate('start_date', '<=', $endYear);
                    }]);
    }

    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {

        if (request()->search['value'] != null) {
            $query->orWhere('full_name', 'LIKE', '%' . request()->search['value'] . '%');
                       }
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order]['name'];
            if(Schema::hasColumn('users',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }
        }
        return $query;
    }

}
