<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Exception;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|permission.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|permission.edit'])->only(['create', 'store']);
    }

    public function create(Request $request, Role $role)
    {
        $permissions = collect(Config::get('module-permission'))->sortKeys();
        return view('master.role-permission.create', compact('role', 'permissions'));

    }

    public function store(Request $request, Role $role)
    {
        try{
            // convert the data of the key
            $permissions = collect($request->except('_token'))->map(function($item, $key){
                return Str::replace('_', '.', $key);
            });
            $role->syncPermissions($permissions);
            return back()->with('msg','Permission Updated.');

        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
