<?php

namespace App\DataTables;

use App\Http\Controllers\commonController;
use App\Models\Project;
use App\Models\SuperAdminProject;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Schema;

class SuperAdminProjectDataTable extends DataTable
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
        ->editColumn('projectPaymentType.type',function($row){
            $tes = ($row->projectPaymentType) ? $row->projectPaymentType->type . (($row->amount) ?  strtoupper( $row->projectCurrency()->exists() ?  ' - '. '<span class="currency-tag">( ' . $row->projectCurrency->symbol : ' - <span class="currency-tag">( ').$row->amount .' )</span>' : ' - <span class="CURRENCY-TAG">( $0.00 )</span>')  : '';
            $submit = route('manage-billable-resource', $row->id);
            if($row->project_type) {
                return '<a href="'.$submit.'" class="get-project-resource-popup edit-record">'.$tes .'</a>';
            }
            return $row->projectPaymentType->type;
        })
        // ->editColumn('amount',function($row){
        //     return ($row->amount) ?  strtoupper( $row->projectCurrency()->exists() ? $row->projectCurrency->code . '(' . $row->projectCurrency->symbol : '').') ' .$row->amount : '';
        // })
        ->editColumn('projectClient.first_name', function ($row) {
            return ($row->projectClient) ? $row->projectClient->full_name : '';
        })
        ->editColumn('projectAllocation.type', function ($row) {
            return ($row->projectAllocation) ? $row->projectAllocation->type : '';
        })
        ->editColumn('projectTeamLead.first_name', function ($row) {
            return ($row->projectTeamLead) ? $row->projectTeamLead->full_name : '';
        })
        ->editColumn('projectReviewer.first_name', function ($row) {
            return ($row->projectReviewer) ? $row->projectReviewer->full_name : '';
        })
        ->editColumn('project_type', function ($row) {
            if($row->project_type === 1) {
                return 'External';
            } elseif($row->project_type === 0) {
                return 'Internal';
            }
            return '';
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
                        return '<badge class="badge badge-light-default">'.$row->projectPriority->name.'</badge>';
                        break;
                }
            }
            else
            {
                return '';
            }
            // return ($row->projectPriority) ? $row->projectPriority->name : '';
        })
        ->editColumn('projectAllocation.type', function ($row) {
            return ($row->projectAllocation) ? $row->projectAllocation->type : '';
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
                    case 'Closed' :
                        return '<badge class="badge badge-light-default">'.$row->projectStatus->name.'</badge>';
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
        ->editColumn('technologies_ids', function ($row) {
            $technologies = $row->projectTechnology->pluck('technology')->toArray();
            return implode(',',$technologies);
        })
        ->addColumn('action', function ($row) {
            return $this->actionColumn($row);
        })
        ->rawColumns(['projectStatus.name','projectPriority.name','action','projectPaymentType.type'])
        ->filter(function ($query) {
            $this->applyFilter($query);
        }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SuperAdminProject $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Project $model)
    {
        return  $model->with('projectClient','projectAllocation','projectTeamLead','projectReviewer','projectPriority','projectStatus','projectPaymentType','projectTechnology')->select('projects.*','projects.id as project_id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('superadminproject-table')
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

     //This method is used to add datatable scope like if we want to add extra search field value then use this method
     public function applyFilter($query)
     {
         if(request()->search['value'] != null){
            $query->leftJoin('technology','technology.id','=','projects.technologies_ids')
            ->orWhereIn('technology.technology',array(request()->search['value']))
             ->orWhereHas('projectTeamLead',function($qry){
                 $qry->where('last_name','LIKE','%'.request()->search['value'].'%');
             })
             ->orWhereHas('projectReviewer',function($qry){
                $qry->where('last_name','LIKE','%'.request()->search['value'].'%');
            })
            ->orWhereHas('projectStatus',function($qry){
                $qry->where('name','LIKE','%'.request()->search['value'].'%');
            })
            ->orWhereHas('projectPriority',function($qry){
                $qry->where('name','LIKE','%'.request()->search['value'].'%');
            })
            ->orWhereHas('projectTechnology',function($qry){
                $qry->where('technology','LIKE','%'.request()->search['value'].'%');
            })
            ->orWhereHas('projectPaymentType',function($qry){
                $qry->where('type','LIKE','%'.request()->search['value'].'%');
            });
         }
         if(request()->filled('project_status')){
            $query->where('status',request()->project_status);
        }
        if(request()->project_type != null && request()->project_type != ''){
            $query->where('project_type',request()->project_type);
        }
         if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order ]['name'];
            if(Schema::hasColumn('projects',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }else{
                $query->orWhereHas('projectPaymentType', function ($qry) {
                    $qry->orderBy('type', request()->order[0]['dir']);
                });
            }
        }
        return $query;
     }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'SuperAdminProject_' . date('YmdHis');
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'super-admin-project.edit', 'link' => route('super-admin-project-activity', $row->project_id), 'disablePopupClass' => true],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }
}
