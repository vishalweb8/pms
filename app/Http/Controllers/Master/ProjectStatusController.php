<?php

namespace App\Http\Controllers\Master;

use App\DataTables\ProjectStatusDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProjectStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ProjectStatusController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|project-status.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|project-status.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|project-status.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|project-status.destroy'])->only(['destroy']);
        view()->share('module_title', 'project status');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectStatusDataTable $dataTable)
    {
        return $dataTable->render('master.project-status.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.project-status.create');
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
            'name' => 'required|unique:App\Models\ProjectStatus,name',
        ]);
        try {
            $projectStatus = new ProjectStatus();
            $projectStatus->name = $request->get('name') ?? null;
            $projectStatus->status = $request->filled('project-status') ? 1 : 0;
            $projectStatus->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Project status'])], 200);
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
    public function edit(Request $request, ProjectStatus $projectStatus)
    {
        return view('master.project-status.edit', compact('projectStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectStatus $projectStatus)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('App\Models\ProjectStatus')->ignore($projectStatus->id),
            ],
        ]);
        try {
            $projectStatus->name = $request->get('name') ?? null;
            $projectStatus->status = $request->filled('project-status') ? 1 : 0;
            $projectStatus->save();

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Project status'])], 200);
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
            $projectStatus = ProjectStatus::where('id','=',$id)->first();
            if($projectStatus) {
                $projectStatus->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Project status'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
