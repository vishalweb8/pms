<?php

namespace App\Http\Controllers\Master;

use App\DataTables\ProjectPaymentTypeDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProjectPaymentType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ProjectPaymentTypeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|project-payment.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|project-payment.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|project-payment.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|project-payment.destroy'])->only(['destroy']);
        view()->share('module_title', 'project payment type');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectPaymentTypeDataTable $dataTable)
    {
        return $dataTable->render('master.project-payment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.project-payment.create');
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
            'type' => 'required|unique:App\Models\ProjectPaymentType,type',
        ]);
        try {
            $projectPaymentType = new ProjectPaymentType();
            $projectPaymentType->type = $request->get('type') ?? null;
            $projectPaymentType->status = $request->filled('payment-status') ? 1 : 0;
            $projectPaymentType->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Project payment type'])], 200);
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

    public function edit(Request $request, ProjectPaymentType $project_payment)
    {
        return view('master.project-payment.edit', compact('project_payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectPaymentType $project_payment)
    {
        $this->validate($request, [
            'type' => [
                'required',
                Rule::unique('App\Models\ProjectPaymentType')->ignore($project_payment->id),
            ],
        ]);
        try {
            $project_payment->type = $request->get('type') ?? null;
            $project_payment->status = $request->filled('payment-status') ? 1 : 0;
            $project_payment->save();

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Project payment type'])], 200);
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
            $projectPaymentType = ProjectPaymentType::where('id','=',$id)->first();
            if($projectPaymentType) {
                $projectPaymentType->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Project payment type'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
