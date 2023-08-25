<?php

namespace App\Http\Controllers\Master;

use App\DataTables\DepartmentDataTable;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class DepartmentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|department.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|department.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|department.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|department.destroy'])->only(['destroy']);
        view()->share('module_title', 'department');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DepartmentDataTable $dataTable)
    {
        return $dataTable->render('master.department.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.department.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'department' => 'required|unique:App\Models\Department,department',
        ]);
        try {
            $department = new Department();
            $department->department = $request->get('department') ?? null;
            $department->status = $request->filled('status-department') ? 1 : 0;
            $department->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Department'])], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Department $department)
    {
        return view('master.department.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $this->validate($request, [
            'department' => [
                'required',
                Rule::unique('App\Models\Department')->ignore($department->id),
            ],
        ]);
        try {
            $department->department = $request->get('department') ?? null;
            $department->status = $request->filled('status-department') ? 1 : 0;
            $department->save();

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Department'])], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $department = Department::where('id','=',$id)->first();
            if($department) {
                $department->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Department'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
