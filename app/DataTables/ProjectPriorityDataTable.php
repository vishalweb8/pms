<?php

namespace App\DataTables;

use App\Models\ProjectPriority;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Schema;

class ProjectPriorityDataTable extends DataTable
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
            ->editColumn('status', function ($row) {
                $options = ['class' => "danger", 'title' => "Inactive"];
                if ($row->status == 1) {
                    $options = ['class' => "success", 'title' => "Active"];
                }
                return view('utils.labels.span')->with($options)->render();
            })->addColumn('action', function($row) {
                return $this->actionColumn($row);
            })->rawColumns(['status','action'])
            ->filter(function ($query){
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProjectPriority $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProjectPriority $model)
    {
        return $model->select();
    }

    /* This method is used to add datatable scope like if we want to add extra search field value then use this method */
    public function applyFilter($query)
    {
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order ]['name'];
            if(Schema::hasColumn('project_priority',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }
        }
        return $query;
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'project-priority.edit', 'link' => route('project-priority.edit', $row->id),],
            // ['btn_file_name' => 'delete', 'permission' => 'project-priority.destroy', 'link' => route('project-priority.destroy', $row->id),],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }
}
