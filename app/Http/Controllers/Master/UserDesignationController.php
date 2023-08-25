<?php

namespace App\Http\Controllers\Master;

use App\DataTables\UserDesignationDataTable;
use App\Http\Controllers\Controller;
use App\Models\UserDesignation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class UserDesignationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|designation.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|designation.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|designation.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|designation.destroy'])->only(['destroy']);
        view()->share('module_title', 'user designation');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(UserDesignationDataTable $dataTable)
    {
        return $dataTable->render('master.designation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.designation.create');
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
            'name' => 'required|unique:App\Models\UserDesignation,name',
            'designation_code' => "required | unique:App\Models\UserDesignation,designation_code",
        ]);
        try {
            $designationData = new UserDesignation();
            $designationData->name = $request->get('name') ?? null;
            $designationData->status = $request->filled('status-designation') ? 1 : 0;
            $designationData->designation_code = $request->get('designation_code') ?? null;
            $designationData->save();
            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Designation']) ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, UserDesignation $designation)
    {
        return view('master.designation.edit', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserDesignation $designation)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('App\Models\UserDesignation')->ignore($designation->id),
            ],
            'designation_code' => [
                'required',
                Rule::unique('App\Models\UserDesignation')->ignore($designation->id),
            ],
        ]);
        try {
            $designation->name = $request->get('name') ?? null;
            $designation->status = $request->filled('status-designation') ? 1 : 0;
            $designation->designation_code = $request->get('designation_code') ?? null;
            $designation->save();
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Designation'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
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
            $designation = UserDesignation::where('id','=',$id)->first();
            if($designation) {
                $designation->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Designation'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
