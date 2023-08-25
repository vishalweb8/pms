<?php

namespace App\DataTables;

use App\Helpers\Helper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Schema;

class UsersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() metho
     * d.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $count_query = clone $query;
        $userTypes = Helper::getUserTypeCounts($this->applyFilter($count_query, true));
        $userTypesHtml = view('users.user_type_filter_view',compact('userTypes'))->render();
        return datatables()
            ->eloquent($query)
            ->with(['userTypes' => $userTypesHtml ])
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                return $row->full_name;
            })
            ->editColumn('officialUser.userDesignation.name', function ($row) {
                return ($row->officialUser) ? (($row->officialUser->userDesignation) ? $row->officialUser->userDesignation->name : '-') : '-';
            })
            ->editColumn('officialUser.userTeam.name', function ($row) {
                return ($row->officialUser) ? (($row->officialUser->userTeam) ? $row->officialUser->userTeam->name : '-') : '-';
            })
            // ->editColumn('officialUser.joining_date', function ($row) {
            //     return ($row->officialUser) ? (($row->officialUser->joining_date) ? $row->officialUser->joining_date : '-') : '-';
            // })
            ->editColumn('created_at', function ($row) {
                return ($row->created_at) ? Carbon::parse($row->created_at)->format('d-m-Y') : '-';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == "1") {
                    $html =  "<span class='badge badge-light-success'>Active</span>";
                }else {
                    $html =  "<span class='badge badge-light-danger'>Blocked</span>";
                }

                $html = "<a href='javascript:void(0);' class='status-update' data-value='". route('user.status', $row->id) ."' data-msg='{$row->status}' >{$html}</a>";

                return $html;
            })
            ->addColumn('action', function ($row) {
                return $this->actionColumn($row);
            })
            ->rawColumns(['deleted_at', 'action', 'status'])
            ->filter(function ($query) {
                $this->applyFilter($query);
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        if (in_array(Auth::user()->userRole->code, ['ADMIN', 'PM', 'HR'])) {
            $model = $model->withBlocked();
        }
        $model = $model->with('userRole','officialUser.userTeam','officialUser','officialUser.userDesignation');
        return $model->select('users.*');
    }


    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query , $skip_status_filter = false)
    {

        if (request()->search['value'] != null) {
            $query->orWhere('full_name', 'LIKE', '%' . request()->search['value'] . '%')
                ->orWhere('address1', 'LIKE', '%' . request()->search['value'] . '%')
                ->orWhere('address2', 'LIKE', '%' . request()->search['value'] . '%')
                ->orWhere('zipcode', 'LIKE', '%' . request()->search['value'] . '%')
                ->orWhereHas('cities', function ($qry) {
                    $qry->where('name', 'LIKE', '%' . request()->search['value'] . '%');
                })
                ->orWhereHas('states', function ($qry) {
                    $qry->where('name', 'LIKE', '%' . request()->search['value'] . '%');
                })
                ->orWhereHas('country', function ($qry) {
                    $qry->where('name', 'LIKE', '%' . request()->search['value'] . '%');
                })
                ->orWhereHas('officialUser.userTeam', function($qry){
                    $qry->where('name','LIKE','%'. request()->search['value']. '%');
                })
                ->orWhereHas('officialUser', function ($qry) {
                    $qry->where('joining_date', 'LIKE', '%' . request()->search['value'] . '%');
                })
                ->orWhereHas('officialUser.userTeamLeader', function ($qry) {
                    $qry->where('full_name', 'LIKE', '%' . request()->search['value'] . '%');
                });
        }
        if(request()->filled('team')) {
            $query->whereHas('officialUser', function ($qry) {
                $qry->where('team_id', request()->team);
            });
        }
        if(request()->userType != '2' && !$skip_status_filter){
            if(request()->userType == '1'){
                $query->where(function($qry){
                    $qry = $qry->where('status','=',request()->userType);
                });
            } else{
                $query->where(function($qry){
                    $qry = $qry->where('status','=',request()->userType)
                    ->orWhere('status','=', '');
                });
            }
        }
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order]['name'];
            if(Schema::hasColumn('users',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }
            // else{
            //     $query->orWhereHas('officialUser', function ($qry) {
            //         $qry->orderBy('joining_date', request()->order[0]['dir']);
            //     })->orWhereHas('officialUser.userDesignation', function ($qry) {
            //         $qry->orderBy('name', request()->order[0]['dir']);
            //     })->orWhereHas('officialUser.userTeam', function ($qry) {
            //         $qry->orderBy('name', request()->order[0]['dir']);
            //     });
            // }
        }

        return $query;
    }

    public function actionColumn($row)
    {
        $options = collect([
            ['btn_file_name' => 'edit', 'permission' => 'user.edit', 'link' => route('user.edit', $row->id),],
            // ['btn_file_name' => 'delete', 'permission' => 'user.destroy', 'link' => route('user.destroy', $row->id),],
            ['btn_file_name' => 'download', 'permission' => 'user.download', 'link' => route('user.download', $row->id),],
            // ['btn_file_name' => 'switch','link' => route('user.status', $row->id), 'check' => $row->status]
        ]);
        return view('common.action-dropdown', compact('options'))->render();
    }
}
