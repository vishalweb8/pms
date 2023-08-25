<?php

namespace App\DataTables;

use App\Models\PaymentHistory;
use Yajra\DataTables\Services\DataTable;

class CompanyRevenueDataTable extends DataTable
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
            ->editColumn('actual_revenue', function($row) {
                return ($row->actual_revenue) ? number_format($row->actual_revenue,2) : 0;
            })
            ->editColumn('expected_amount', function($row) {
                return ($row->expected_amount) ? number_format($row->expected_amount,2) : 0;
            })
            ->rawColumns(['action'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PaymentHistory $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PaymentHistory $model)
    {
        return $model->select('payment_histories.*',\DB::raw('SUM(amount_in_doller) as actual_revenue'))
        ->has('project')->with('project')->groupBy('project_id');
    }

    /* This method is used to add datatable scope like if we want to add extra search field value then use this method */
    public function applyFilter($query)
    {     
        return $query->whereYear('invoice_date',request('year'))->whereMonth('invoice_date',request('month'));
    }

    /* public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'view', 'permission' => 'payment-history.edit', 'link' => route('paymentHistory.edit',[$row->project_id,$row->id]),'disablePopupClass' => true]
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    } */
}
