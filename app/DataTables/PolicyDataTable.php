<?php

namespace App\DataTables;

use App\Models\Policy;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PolicyDataTable extends DataTable
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
            ->editColumn('title', function($row){
                return (!$row->file_url == NULL) ? '<a href="'.Storage::url("upload/policy/$row->file_url").'" target="_blank">'.$row->title.'</a>' : $row->title ;
            })
            ->editColumn('status', function ($row) {
                $options = ['class' => "danger", 'title' => "Inactive"];
                if ($row->status == 1) {
                    $options = ['class' => "success", 'title' => "Active"];
                }
                return view('utils.labels.span')->with($options)->render();
            })
            ->addColumn('action', function($row) {
                return $this->actionColumn($row);
            })
            ->rawColumns(['title','status','action'])
            ->filter(function ($query){
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Policy $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Policy $model)
    {
        return $model->select();
    }

    /* This method is used to add datatable scope like if we want to add extra search field value then use this method */
    public function applyFilter($query)
    {
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order]['name'];
            if(Schema::hasColumn('policies',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }
        }
        return $query;
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'policy.edit', 'link' => route('policy.edit', $row->id)],
            ['btn_file_name' => 'delete', 'permission' => 'policy.destroy', 'link' => route('policy.destroy', $row->id),],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }
}
