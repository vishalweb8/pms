<?php

namespace App\Http\Controllers\Master;

use App\DataTables\ProjectPriorityDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProjectPriority;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ProjectPriorityController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|project-priority.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|project-priority.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|project-priority.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|project-priority.destroy'])->only(['destroy']);
        view()->share('module_title', 'project priority');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectPriorityDataTable $dataTable)
    {
        return $dataTable->render('master.project-priority.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.project-priority.create');
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
            'name' => 'required|unique:App\Models\ProjectPriority,name',
        ]);
        try {
            $projectPriority = new ProjectPriority();
            $projectPriority->name = $request->get('name') ?? null;
            $projectPriority->status = $request->filled('priority-status') ? 1 : 0;
            $projectPriority->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Project priority'])], 200);
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
    public function edit(Request $request, ProjectPriority $projectPriority)
    {
        return view('master.project-priority.edit', compact('projectPriority'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectPriority $projectPriority)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('App\Models\ProjectPriority')->ignore($projectPriority->id),
            ],
        ]);
        try {
            $projectPriority->name = $request->get('name') ?? null;
            $projectPriority->status = $request->filled('priority-status') ? 1 : 0;
            $projectPriority->save();

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Project priority'])], 200);
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
            $projectPriority = ProjectPriority::where('id','=',$id)->first();
            if($projectPriority) {
                $projectPriority->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Project priority'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
