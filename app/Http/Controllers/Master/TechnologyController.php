<?php

namespace App\Http\Controllers\Master;

use App\DataTables\TechnologyDataTable;
use App\Http\Controllers\Controller;
use App\Models\Technology;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TechnologyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|technology.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|technology.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|technology.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|technology.destroy'])->only(['destroy']);
        view()->share('module_title', 'technology');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TechnologyDataTable $dataTable)
    {
        return $dataTable->render('master.technology.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.technology.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'technology' => ["required" , Rule::unique('technology', 'technology')->whereNull('deleted_at')],
            'description'  => 'required'
        ]);
        try {
            $technology = new Technology();
            $technology->technology = $request->get('technology') ?? null;
            $technology->description = $request->get('description') ?? null;
            $technology->status = $request->filled('status-technology') ? 1 : 0;
            $technology->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Technology'])], 200);
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
    public function edit(Request $request, Technology $technology)
    {
        return view('master.technology.edit', compact('technology'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Technology $technology)
    {
        $this->validate($request, [
            'technology' => [
                'required',
                Rule::unique('technology')->whereNull('deleted_at')->ignore($technology->id),
            ],
            'description'  => 'required'
        ]);
        try {
            $technology->technology = $request->get('technology') ?? null;
            $technology->description = $request->get('description') ?? null;
            $technology->status = $request->filled('status-technology') ? 1 : 0;
            $technology->save();
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Technology'])], 200);
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
            $technology = Technology::findOrFail($id);
            if ($technology) {
                $technology->delete();
                return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Technology'])], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
