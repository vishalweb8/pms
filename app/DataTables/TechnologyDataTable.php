<?php

namespace App\DataTables;

use App\Models\Technology;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Schema;

class TechnologyDataTable extends DataTable
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
            })
            ->addColumn('action', function($row) {
                return $this->actionColumn($row);
            })
            ->rawColumns(['status', 'action'])
            ->filter(function ($query){
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Technology $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Technology $model)
    {
        // DB::statement(DB::raw('set @rownum=0'));
        // return $model->select(['technology.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return $model->select();
    }

     /* This method is used to add datatable scope like if we want to add extra search field value then use this method */
     public function applyFilter($query)
     {
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order ]['name'];
            if(Schema::hasColumn('technology',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }
        }
        return $query;
     }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'technology.edit', 'link' => route('technology.edit', $row->id),],
            // ['btn_file_name' => 'delete', 'permission' => 'technology.destroy', 'link' => route('technology.destroy', $row->id),],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }

}
