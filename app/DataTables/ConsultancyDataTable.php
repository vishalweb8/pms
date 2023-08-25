<?php

namespace App\DataTables;

use App\Models\Consultancy;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Schema;
class ConsultancyDataTable extends DataTable
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
            ->addColumn('action', function($row) {
                return $this->actionColumn($row);
            })
            ->rawColumns(['action'])
            ->filter(function ($query){
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Consultancy $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Consultancy $model)
    {
        return $model->select();
    }

     /* This method is used to add datatable scope like if we want to add extra search field value then use this method */
     public function applyFilter($query)
     {
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order ]['name'];
            if(Schema::hasColumn('consultancies',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }
        }
        return $query;
     }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'consultancy.edit', 'link' => route('consultancy.edit', $row->id),],
            // ['btn_file_name' => 'delete', 'permission' => 'consultancy.destroy', 'link' => route('consultancy.destroy', $row->id),],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }

}
