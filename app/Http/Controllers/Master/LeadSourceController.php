<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadSource;
use Exception;
use Illuminate\Validation\Rule;
use App\DataTables\LeadSourceDataTable;

class LeadSourceController extends Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|lead-source.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|lead-source.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|lead-source.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|lead-source.destroy'])->only(['destroy']);
        view()->share('module_title', 'lead source');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LeadSourceDataTable $dataTable)
    {
        return $dataTable->render('master.lead-source.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.lead-source.create');
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
            'name' => 'required|unique:App\Models\LeadSource,name',
        ]);
        try {
            $leadSource = new LeadSource();
            $leadSource->name = $request->get('name') ?? null;
            $leadSource->status = $request->filled('lead-source-status') ? 1 : 0;
            $leadSource->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Lead source'])], 200);
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
        $leadSource= LeadSource::withBlocked()->find($id);
        return view('master.lead-source.edit', compact('leadSource'));
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
        $leadSource = LeadSource::withBlocked()->find($id);
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('App\Models\LeadSource')->ignore($leadSource->id),
            ],
        ]);
        try {
            $leadSource->name = $request->get('name') ?? null;
            $leadSource->status = $request->filled('lead-source-status') ? 1 : 0;
            $leadSource->save();

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Lead source'])], 200);
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
            $leadSource = LeadSource::where('id','=',$id)->first();
            if($leadSource) {
                $leadSource->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Lead source'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
