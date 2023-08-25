<?php

namespace App\DataTables;

use App\Http\Controllers\commonController;
use App\Models\Lead;
use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use App\Helpers\Helper;

class LeadStatisticsDataTable extends DataTable
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
            ->editColumn('leads_count', function ($row) {
                return $row->leads_count;
            })
            ->editColumn('open_lead_count', function ($row) {
                return $row->open_lead_count;
            })
            ->editColumn('converted_lead_count', function ($row) {
                return $row->converted_lead_count;
            })
            ->editColumn('rejected_lead_count', function ($row) {
                return $row->rejected_lead_count;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AllLeaveDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        if (in_array(Auth::user()->userRole->code, ['ADMIN', 'PM', 'HR'])) {
            $model = $model->withBlocked();
        }
        $financial_year = Helper::getFinancialYearDates();

        if(request()->fyear != null){
            $financial_year = explode('/',request()->fyear);
            $startDate = $financial_year[0];
            $endDate = $financial_year[1];

        } else {
            $startDate = $financial_year['start_date'];
            $endDate = $financial_year['end_date'];
        }
        $data = $model->select('id','first_name','last_name', 'full_name', 'created_at')
        ->has('officialUser.userTeam')->wherehas('officialUser.userTeam', function($query) {
            $query->where('name', 'like', 'Sales%');
        })
        ->withCount(['leads' => function($query) use($startDate, $endDate) {
            $query->whereBetween('created_at', [DB::raw("'$startDate'"), DB::raw("'$endDate'")]);
        }])
        ->withCount(['leads as open_lead_count' => function ($query) use($startDate, $endDate) {
            $query->wherehas('leadStatus', function ($query) {
                $query->where('name', 'Open');
            })
            ->whereBetween('created_at', [DB::raw("'$startDate'"), DB::raw("'$endDate'")]);
        }])
        ->withCount(['leads as converted_lead_count' => function ($query) use($startDate, $endDate) {
            $query->wherehas('leadStatus', function ($query) {
                $query->where('name', 'Converted');
            })
            ->whereBetween('created_at', [DB::raw("'$startDate'"), DB::raw("'$endDate'")]);
        }])
        ->withCount(['leads as rejected_lead_count' => function ($query) use($startDate, $endDate) {
            $query->wherehas('leadStatus', function ($query) {
                $query->where('name', 'Rejected');
            })
            ->whereBetween('created_at', [DB::raw("'$startDate'"), DB::raw("'$endDate'")]);
        }]);
        return $data;
    }

    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {

        if (request()->search['value'] != null) {
            $query->orWhere('full_name', 'LIKE', '%' . request()->search['value'] . '%');
        }
        if(request()->order[0]['column'] != null) {
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order]['name'];
            if(Schema::hasColumn('users',$column_name)) {
                $query->orderBy($column_name, request()->order[0]['dir']);
            }
        }
        return $query;
    }
}
