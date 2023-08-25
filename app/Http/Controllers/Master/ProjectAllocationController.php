<?php

namespace App\Http\Controllers\Master;

use App\DataTables\ProjectAllocationDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProjectAllocation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ProjectAllocationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|allocation.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|allocation.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|allocation.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|allocation.destroy'])->only(['destroy']);
        view()->share('module_title', 'project allocation');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectAllocationDataTable $dataTable)
    {
        return $dataTable->render('master.project-allocation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.project-allocation.create');
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
            'type' => 'required|unique:App\Models\ProjectAllocation,type',
        ]);
        try {
            $projectAllocation = new ProjectAllocation();
            $projectAllocation->type = $request->get('type') ?? null;
            $projectAllocation->status = $request->filled('status-allocation') ? 1 : 0;
            $projectAllocation->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Project allocation'])], 200);
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

    public function edit(Request $request, ProjectAllocation $allocation)
    {
        return view('master.project-allocation.edit', compact('allocation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectAllocation $allocation)
    {
        $this->validate($request, [
            'type' => [
                'required',
                Rule::unique('App\Models\ProjectAllocation')->ignore($allocation->id),
            ],
        ]);
        try {
            $allocation->type = $request->get('type') ?? null;
            $allocation->status = $request->filled('status-allocation') ? 1 : 0;
            $allocation->save();

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Project allocation'])], 200);
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
            $projectAllocation = ProjectAllocation::where('id','=',$id)->first();
            if($projectAllocation) {
                $projectAllocation->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Project allocation'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
