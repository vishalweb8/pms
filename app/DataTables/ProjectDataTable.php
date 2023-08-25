<?php

namespace App\DataTables;

use App\Helpers\Helper;
use App\Models\Project;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Schema;
use Auth;

class ProjectDataTable extends DataTable
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
            ->editColumn('name', function($row){
                // Uncomment below line for phase-2
                return '<a href="'.route("project-details",$row->id).'">'.$row->name.'</a>';

                // Remove below line from phase-2 is deployed
                // return $row->name;
            })
            ->editColumn('projectReviewer.first_name', function ($row) {
                return ($row->projectReviewer) ? $row->projectReviewer->full_name : '';
            })
            ->editColumn('projectPriority.name', function ($row) {
                if(isset($row->projectPriority)){
                    switch($row->projectPriority->name){
                        case 'Law' :
                            return '<badge class="badge badge-light-default">'.$row->projectPriority->name.'</badge>';
                            break;
                        case 'Medium' :
                            return '<badge class="badge badge-light-info">'.$row->projectPriority->name.'</badge>';
                            break;
                        case 'High' :
                            return '<badge class="badge badge-light-danger">'.$row->projectPriority->name.'</badge>';
                            break;
                        case 'Done' :
                            return '<badge class="badge badge-light-success">'.$row->projectPriority->name.'</badge>';
                            break;
                        default :
                            return '<badge class="badge badge-light-info">'.$row->projectPriority->name.'</badge>';
                            break;
                    }
                }
                else
                {
                    return '';
                }
                // return ($row->projectPriority) ? $row->projectPriority->name : '';
            })
            ->editColumn('projectStatus.name', function ($row) {
                if(isset($row->projectStatus)){
                    switch($row->projectStatus->name){
                        case 'On Track' :
                            return '<badge class="badge badge-light-success">'.$row->projectStatus->name.'</badge>';
                            break;
                        case 'Off Track' :
                            return '<badge class="badge badge-light-default">'.$row->projectStatus->name.'</badge>';
                            break;
                        case 'On Hold' :
                            return '<badge class="badge badge-light-danger">'.$row->projectStatus->name.'</badge>';
                            break;
                        case 'Done' :
                            return '<badge class="badge badge-light-success">'.$row->projectStatus->name.'</badge>';
                            break;
                        case 'Ready' :
                            return '<badge class="badge badge-light-success">'.$row->projectStatus->name.'</badge>';
                            break;
                        case 'Blocked' :
                            return '<badge class="badge badge-light-danger">'.$row->projectStatus->name.'</badge>';
                            break;
                        case 'Critical' :
                            return '<badge class="badge badge-light-info">'.$row->projectStatus->name.'</badge>';
                            break;
                        case 'Delayed' :
                            return '<badge class="badge badge-light-danger">'.$row->projectStatus->name.'</badge>';
                            break;
                        default :
                            return '<badge class="badge badge-light-info">'.$row->projectStatus->name.'</badge>';
                            break;
                    }
                }
                else
                {
                    return '';
                }
                // return ($row->projectStatus) ? $row->projectStatus->name : '';
            })
            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                // return Helper::setDateFormat($row->created_at);
            })
            ->rawColumns(['name','projectStatus.name','projectPriority.name'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProjectDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Project $model)
    {
        return $model->with('projectReviewer','projectPriority','projectStatus')->select('projects.*','projects.id as project_id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('projectdatatable-table')
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

    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {
        if (Auth::user()->role_id != 1) {
            $query->where('status', '!=', '13')->whereHas('user',function($qry){
                $qry->where('user_projects.user_id', Auth::user()->id);
            });
        }
        if(request()->search['value'] != null){
           $query->leftJoin('technology','technology.id','=','projects.technologies_ids')
           ->orWhereIn('technology.technology',array(request()->search['value']))
            ->orWhereHas('projectReviewer',function($qry){
               $qry->where('last_name','LIKE','%'.request()->search['value'].'%');
           })
           ->orWhereHas('projectStatus',function($qry){
               $qry->where('name','LIKE','%'.request()->search['value'].'%');
           })
           ->orWhereHas('projectPriority',function($qry){
               $qry->where('name','LIKE','%'.request()->search['value'].'%');
           });
        }
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order ]['name'];
            if(Schema::hasColumn('projects',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }else{
                $query->orWhereHas('projectStatus', function ($qry) {
                    $qry->orderBy('name', request()->order[0]['dir']);
                })
                   ->orWhereHas('projectPriority', function ($qry) {
                    $qry->orderBy('name', request()->order[0]['dir']);
                });
            }
        }
        return $query;
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
        return 'Project_' . date('YmdHis');
    }
}
