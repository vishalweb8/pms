<?php

namespace App\DataTables;

use App\Models\Client;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Schema;

class ClientDataTable extends DataTable
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
             ->editColumn('name', function ($row) {
                return $row->first_name .' '.$row->last_name;
            })
            ->editColumn('address1', function ($row) {
                return $row->full_address;
            })
            ->addColumn('action', function($row) {
                return $this->actionColumn($row);
            })
            ->editColumn('getProjectByClient.name',function($row){
                return isset($row->getProjectByClient) ? (implode(', ',array_column($row->getProjectByClient->toArray(),'name'))) : '-';
            })
            ->rawColumns(['status', 'action'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Client $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Client $model)
    {
         return $model->with('getProjectByClient')->select('clients.*');
    }

    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {
        if(request()->search['value'] != null){
            $query->orWhere('last_name','LIKE','%'.request()->search['value'].'%')
            ->orWhere('address1','LIKE','%'.request()->search['value'].'%')
            ->orWhere('address2','LIKE','%'.request()->search['value'].'%')
            ->orWhere('zipcode','LIKE','%'.request()->search['value'].'%')
            ->orWhereHas('city',function($qry){
                $qry->where('name','LIKE','%'.request()->search['value'].'%');
            })
            ->orWhereHas('state',function($qry){
                $qry->where('name','LIKE','%'.request()->search['value'].'%');
            })
            ->orWhereHas('country',function($qry){
                $qry->where('name','LIKE','%'.request()->search['value'].'%');
            });
        }
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order ]['name'];
            if(Schema::hasColumn('clients',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }
        }
        return $query;
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'client.edit', 'link' => route('client.edit', $row->id),],
            ['btn_file_name' => 'delete', 'permission' => 'client.destroy', 'link' => route('client.destroy', $row->id),],
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }

}
