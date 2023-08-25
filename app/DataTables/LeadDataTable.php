<?php

namespace App\DataTables;

use App\Http\Controllers\commonController;
use App\Models\Lead;
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


class LeadDataTable extends DataTable
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
            ->editColumn('user.first_name', function ($row) {
                return (isset($row->user->full_name)) ? ucwords($row->user->full_name) : 'N/A';
            })
            ->editColumn('full_name', function ($row) {
                return ucwords($row->first_name) . ' ' . ucwords($row->last_name);
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y');
            })
            ->editColumn('lead_status_id', function ($row) {
                if(isset($row->leadStatus->name)) {
                    if ($row->leadStatus->name == "Open") {
                        return "<span class='badge badge-light-warning'>Open</span>";
                    } elseif ($row->leadStatus->name == "Converted") {
                        return "<span class='badge badge-light-success'>Converted</span>";
                    } elseif ($row->leadStatus->name == "Rejected") {
                        return "<span class='badge badge-light-danger'>Rejected</span>";
                    } else {
                        return "<span class='badge badge-light-info'>Unknown</span>";
                    }
                } else {
                    return "<span class='badge badge-light-info'>Unknown</span>";
                }
            })
            ->editColumn('lead_source_id', function ($row) {
                return (isset($row->leadSource->name)) ? $row->leadSource->name : 'N/A';
            })
            ->editColumn('lead_industry_id', function ($row) {
                return (isset($row->industry->name)) ? $row->industry->name : 'N/A';
            })->addColumn('action', function ($row) {
                return $this->actionColumn($row);
            })->rawColumns(['lead_status_id', 'action'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Lead $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Lead $model)
    {

        $data = $model->with(['user', 'leadStatus', 'industry', 'leadSource']);

        return $data;
    }

     //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {

        if(request()->fyear != null && request()->fyear != 'all'){
            $filter_date = request()->fyear;

            $from_date = now();
            $to_date = now();
            $software_start_date = Carbon::parse('2022-01-31');
            if($filter_date == 'week') {
                $from_date = $from_date->startOfWeek(Carbon::MONDAY);
                $to_date = $to_date->endOfWeek(Carbon::SUNDAY);
            }elseif($filter_date == 'this_month') {
                $from_date = $from_date->startOfMonth();
                $to_date = $to_date->endOfMonth();
            }elseif($filter_date == 'last_month') {
                $from_date = $from_date->subMonth()->startOfMonth();
                $to_date = $to_date->subMonth()->endOfMonth();
            }elseif($filter_date == 'this_year') {
                $from_date = $from_date->startOfYear();
                $to_date = $to_date->endOfYear();
            }elseif($filter_date == 'last_year') {
                $from_date = $from_date->subYear()->startOfYear();
                $to_date = $to_date->subYear()->endOfYear();
            }

            if($from_date->lessThan($software_start_date)){
                $from_date = $software_start_date;
            }

            $from_date = $from_date->format('Y-m-d');
            $to_date  = $to_date->format('Y-m-d');


            $query->whereDate('created_at', '>=', DB::raw("'$from_date'"));
            $query->whereDate('created_at', '<=', DB::raw("'$to_date'"));

        }

        if(request()->lead_owner_id != null && request()->lead_owner_id != 0){
            $query->where('lead_owner_id',request()->lead_owner_id);
        }

        if(request()->lead_status_id != null && request()->lead_status_id != 0){
            $query->where('lead_status_id',request()->lead_status_id);;
        }

        if(request()->search['value'] != null){
            $query->where(function($que) {
                $que->orwhere(DB::raw('CONCAT(first_name, " ",last_name)'), 'LIKE', '%' . request()->search['value'] . '%')
                ->orwhere('lead_title', 'like', '%'.request()->search['value'].'%');
            });
        }

        if(isset(request()->order[0]['column']) != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order ]['name'];
            if(Schema::hasColumn('leads',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            } else if($column_name == 'full_name') {
                $query->orderBy('first_name', request()->order[0]['dir']);
            } else{
                $query->WhereHas('user', function ($qry) {
                    $qry->orderBy('first_name', request()->order[0]['dir']);
                });
            }
        } else {
            $query->orderby('id','desc');
        }
        return $query;
    }

    public function actionColumn($row)
    {

        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'lead.edit', 'link' => route('lead.edit', $row->id),],
            ['btn_file_name' => 'delete', 'permission' => 'lead.destroy', 'link' => route('lead.destroy', $row->id),],
            ['btn_file_name' => 'view', 'permission' => 'lead.view', 'link' => route('lead.view', $row->id)],
        ]);

        if(request()->route()->getName() == 'lead.all' && !Auth::user()->hasRole('Super Admin')) {
            unset($options[0]);
            unset($options[1]);
        }

        return view('common.action-dropdown', compact('options'))->render();
    }
}
