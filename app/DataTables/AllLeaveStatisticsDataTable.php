<?php

namespace App\DataTables;

use App\Http\Controllers\commonController;
use App\Models\User;
use App\Models\LeaveComment;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Helpers\Helper;
use App\Models\LeaveAllocation;

class AllLeaveStatisticsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $loggedIn_user_roles  = Auth::user()->userRole->code;

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('full_name', function ($row) use ($loggedIn_user_roles) {
                $fullName = $row->full_name;
                if (in_array($loggedIn_user_roles, ['ADMIN'])) {
                    $fullName .= "<a href='javascript:void(0);' class='sync-statics' data-id='{$row->id}'> <i class='mdi mdi-sync'></i></a>";
                }
                return $fullName;
            })
            ->editColumn('total_leaves', function ($row) {
                $userLeaves = $row->allocatedLeaves;
                return number_format(getTotalLeaves($userLeaves) ?? config('constant.leaves.total_leave'),1);
            })
            ->editColumn('compensated_leave', function($row){
                $userLeaves = $row->allocatedLeaves;
                return ($userLeaves && !empty($userLeaves->compensation_leaves)) ? $userLeaves->compensation_leaves : '0';
            })
            ->editColumn('used_leaves', function ($row) {
                $used_leaves = $row->leaves->sum('duration');
                return ($used_leaves != 0) ? '<a data-url='.route('view-emp-leave-modal',[$row->id, request('fyear')]).' href="javascript: void(0);" class="add-btn">'.$used_leaves.'</a>' : $used_leaves ;
            })
            ->editColumn('remaining_leaves', function ($row) {
                return getRemainingLeaves($row->allocatedLeaves,$row->leaves);
            })->rawColumns(['used_leaves', 'full_name']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AllLeaveDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        if (Auth::user()->isManagement()) {
            $model = $model->withBlocked();
        }
        $model = $model->with(['allocatedLeaves','leaves' => function ($query) {
            $query->financialYear(request('fyear'))->where('status','approved');
        }]);

        return $model->select('users.*');
    }

    //This method is used to add datatable scope like if we want to add extra search field value then use this method
    public function applyFilter($query)
    {

        if (request()->search['value'] != null) {
            $query->orWhere('full_name', 'LIKE', '%' . request()->search['value'] . '%');
                       }
        if(request()->order[0]['column'] != null){
            $column_order = request()->order[0]['column'];
            $column_name = request()->columns[$column_order]['name'];
            if(Schema::hasColumn('users',$column_name)){
                $query->orderBy($column_name, request()->order[0]['dir']);
            }
        }
        return $query;
    }
}
