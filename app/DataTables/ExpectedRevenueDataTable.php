<?php

namespace App\DataTables;

use App\Models\ExpectedRevenue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Services\DataTable;

class ExpectedRevenueDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        $count_query = clone $query;
        $revenueCountHTML = $this->getRevenueCounts($this->applyFilter($count_query, true));

        return datatables()
            ->eloquent($query)
            ->with(['revenueCount' => $revenueCountHTML ])
            ->addIndexColumn()
            ->addColumn('month_year',function($row){
                $monthYear = date('M',mktime(0,0,0,$row->month)).'-'.$row->year;
                return $monthYear;
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
     * @param \App\Models\ExpectedRevenue $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ExpectedRevenue $model)
    {
        return $model->select('expected_revenues.*')->has('project')->with('project');
    }

    /* This method is used to add datatable scope like if we want to add extra search field value then use this method */
    public function applyFilter($query)
    {
        return $query->where('month',request('month'))->where('year',request('year'));
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'expected-revenue.edit', 'link' => route('expectedRevenue.edit', $row->id)],
            ['btn_file_name' => 'delete', 'permission' => 'expected-revenue.destroy', 'link' => route('expectedRevenue.destroy', $row->id)],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }


    public static function getRevenueCounts($query)
    {
        $revenueCounts = [
            'actual' => 0,
            'expected' => 0,
        ];
        try {
            $query->addSelect(DB::raw('sum(expected_revenue) as total_expected'),DB::raw('sum(actual_revenue) as total_actual'));
            $revenueEntry = $query->first();
        } catch (\Throwable $th) {
            Log::error("getting error while count leaves:-".$th);
        }
        if(!empty($revenueEntry)) {
            $revenueCounts = [
                'actual' => $revenueEntry->total_actual ?? 0,
                'expected' => $revenueEntry->total_expected ?? 0,
            ];
        }
        $revenueCountsHtml = view('expected-revenue.partials.filter_leave_counts',compact('revenueCounts'))->render();
        return $revenueCountsHtml;
    }
}
