<?php

namespace App\DataTables;

use App\Models\Performer;
use Yajra\DataTables\Services\DataTable;

class PerformerDataTable extends DataTable
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
            ->editColumn('revenue',function($query){
                return $query->revenue;
            })
            ->editColumn('status',function($query){
                return $query->status ? 'Active' : 'Inactive';
            })
            ->addColumn('action', function($row) {
                return $this->actionColumn($row);
            })
            ->rawColumns(['action'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Performer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Performer $model)
    {
        return $model->select('performers.*')->has('user')->with('user');
    }

    /* This method is used to add datatable scope like if we want to add extra search field value then use this method */
    public function applyFilter($query)
    {
        $monthYear = str_pad(request('month'),2,"0", STR_PAD_LEFT).'-'.request('year');
        info($monthYear);
        return $query->where('month_year',$monthYear);
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'performer.edit', 'link' => route('performer.edit', $row->id)],
            ['btn_file_name' => 'delete', 'permission' => 'performer.destroy', 'link' => route('performer.destroy', $row->id)],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }
}
