<?php

namespace App\DataTables;

use App\Models\PaymentHistory;
use Yajra\DataTables\Services\DataTable;

class PaymentHistoryDataTable extends DataTable
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
            ->editColumn('invoice_fullurl',function($row){
                $url = '';
                if($row->invoice_fullurl) {
                    $url = "<a href='{$row->invoice_fullurl}' target='_blank'>View</a>";
                }
                return $url;
            })
            ->addColumn('action', function($row) {
                return $this->actionColumn($row);
            })
            ->rawColumns(['action','invoice_fullurl'])
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
        return $model->select('payment_histories.*')
        ->where('project_id',request('project_id'))
        ->has('project')
        ->with('client','currency');
    }

    /* This method is used to add datatable scope like if we want to add extra search field value then use this method */
    public function applyFilter($query)
    {     
        return $query;
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'payment-history.edit', 'link' => route('paymentHistory.edit',[$row->project_id,$row->id]),'disablePopupClass' => true],
            ['btn_file_name' => 'delete', 'permission' => 'payment-history.destroy', 'link' => route('paymentHistory.destroy', [$row->project_id,$row->id]),'disablePopupClass' => true],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }
}
