<?php

namespace App\DataTables;

use App\Models\OrganizationChart;
use Yajra\DataTables\Services\DataTable;

class OrganizationChartDataTable extends DataTable
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
            ->editColumn('is_top_level',function($query){
                return $query->is_top_level ? 'Yes' : 'No';
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
     * @param \App\Models\OrganizationChart $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(OrganizationChart $model)
    {
        return $model->select('organization_charts.*')->has('user')->with('user');
    }

    /* This method is used to add datatable scope like if we want to add extra search field value then use this method */
    public function applyFilter($query)
    {        
        return $query;
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'organization-chart.edit', 'link' => route('organizationChart.edit', $row->id),],
            ['btn_file_name' => 'delete', 'permission' => 'organization-chart.destroy', 'link' => route('organizationChart.destroy', $row->id),],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }
}
