<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Industry;
use Exception;
use Illuminate\Validation\Rule;
use App\DataTables\IndustryDataTable;

class IndustryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|industry.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|industry.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|industry.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|industry.destroy'])->only(['destroy']);
        view()->share('module_title', 'industry');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndustryDataTable $dataTable)
    {
        return $dataTable->render('master.industry.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.industry.create');
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
            'name' => 'required|unique:App\Models\Industry,name',
        ]);
        try {
            $industry = new Industry();
            $industry->name = $request->get('name') ?? null;
            $industry->status = $request->filled('industry-status') ? 1 : 0;
            $industry->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Industry'])], 200);
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
    public function edit(Request $request, $id)
    {
        $industry= Industry::withBlocked()->find($id);
        return view('master.industry.edit', compact('industry'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $industry = Industry::withBlocked()->find($id);
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('App\Models\Industry')->ignore($industry->id),
            ],
        ]);
        try {
            $industry->name = $request->get('name') ?? null;
            $industry->status = $request->filled('industry-status') ? 1 : 0;
            $industry->save();

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Industry'])], 200);
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
            $industry = Industry::where('id','=',$id)->first();
            if($industry) {
                $industry->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Industry'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
