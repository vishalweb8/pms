<?php

namespace App\DataTables;

use App\Models\TeamLeaderMember;
use App\Models\TimeEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TimeEntryDataTable extends DataTable
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
            ->addColumn('full_name',function($query){
                return ($query->officialDetail->userOfficial) ? $query->officialDetail->userOfficial->full_name : '';

            })->editColumn('log_date',function($query){
                return date('d-m-Y', strtotime($query->log_date));

            })->editColumn('log_in_time',function($query){
                return '<badge class="badge badge-light-info font-size-14 fw-fw-semibold mb-1">'.implode('</badge><br/><badge class="badge badge-light-info font-size-14 fw-fw-semibold mb-1">',explode(',',$query->log_in_time));

            })->editColumn('log_out_time',function($query){
                return '<badge class="badge badge-light-warning font-size-14 fw-fw-semibold mb-1">' .implode('</badge><br/><badge class="badge badge-light-warning font-size-14 fw-fw-semibold mb-1">',explode(',',$query->log_out_time));

            })->editColumn('duration',function($query){
                $durations = $query->duration;
                return '<badge class="badge badge-light-default font-size-14 fw-fw-semibold mb-1">'. implode('</badge><br/><badge class="badge badge-light-default font-size-14 fw-fw-semibold mb-1">',$durations);

            })->editColumn('total_duration',function($query){
                $totalTime = $query->total_duration;
                if($totalTime < '08:20:00'){
                    return '<badge class="badge badge-light-danger font-size-14 fw-fw-semibold d-inline-flex align-items-center">
                    <i class="bx bx-confused font-size-22 me-1"></i>' . $totalTime . '</badge>';
                }else {
                    return '<badge  class="badge badge-light-success font-size-14 fw-fw-semibold d-inline-flex align-items-center">
                    <i class="bx bx-happy-alt font-size-22 me-1"></i>' . $totalTime . '</badge>';
                }

            })->rawColumns(['log_in_time','log_out_time','duration','total_duration'])
                ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TimeEntry $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TimeEntry $model)
    {
        $users = $model::durations();
        if (!empty(request()->user_id)) {
            $users->whereHas('officialDetail', function ($qry) {
                $qry->where('user_id', request()->user_id);
            });
        }
        if(!empty(request()->date)){
            $date = Carbon::parse(request()->date);
            $users->whereDate('LogDateTime',$date);
        }
        if(!empty(request()->month)){
            $month = request()->month;
            $users->whereMonth('LogDateTime',$month);
        }
        if(!empty(request()->fyear)){
            $year = request()->fyear;
            $users->whereyear('LogDateTime',$year);
        }

        if(Auth::user()->roles[0]->type == "EMP" && \Request::route()->getName() == 'all-employee-time-entry') {
            $teamMembers = TeamLeaderMember::where('user_id', Auth::user()->id)->pluck('member_id');
            $users->whereHas('officialDetail', function ($qry) use ($teamMembers) {
                $qry->whereIn('user_id', $teamMembers->toArray());
            });
        }

        $users->has('officialDetail.userOfficial')
            ->with('officialDetail.userOfficial');
        return $users;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('allemptimeentriesdatatable-table')
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
        return 'AllEmpTimeEntries_' . date('YmdHis');
    }

    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {

         // Query for set team
        if (request()->team != 0) {
            $query->whereHas('officialDetail', function ($qry) {
                $qry->where('team_id', request()->team);
            });
        }

        if(request()->order[0]['column'] == 1) {
            $query->orderBy('id', 'desc');
        }
        return $query;
    }
}
