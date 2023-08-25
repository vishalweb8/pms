<?php

namespace App\Http\Controllers\Master;

use App\DataTables\RolesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|roles.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|roles.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|roles.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|roles.destroy'])->only(['destroy']);
        view()->share('module_title', 'role');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('master.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.roles.create');
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
            'name'  => 'required',
            'code' => "required | unique:App\Models\Role,code",
            'type'  => 'required'
        ]);

        try {
            $roles = new Role();
            $roles->name = $request->get('name') ?? null;
            $roles->code = $request->get('code') ?? null;
            $roles->type = $request->get('type') ?? null;
            $roles->status = $request->filled('status-role') ? 1 : 0;
            $roles->save();
            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Role'])], 200);
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
    public function edit(Request $request, Role $role)
    {
        return view('master.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name'  => 'required',
            'code' => [
                'required',
                Rule::unique('App\Models\Role')->ignore($role->id),
            ],
            'type' => 'required'
        ]);
        try {
            $role->name = $request->get('name') ?? null;
            $role->code = $request->get('code') ?? null;
            $role->type = $request->get('type') ?? null;
            $role->status = $request->filled('status-role') ? 1 : 0;
            $role->save();
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Role'])], 200);
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
            $role = Role::where('id', '=', $id)->first();
            if ($role) {
                $role->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Role'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
