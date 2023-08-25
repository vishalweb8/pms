<?php

namespace App\Http\Controllers\Master;

use App\DataTables\ConsultancyDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultancy;
use Exception;
use Illuminate\Validation\Rule;

class ConsultancyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|consultancy.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|consultancy.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|consultancy.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|consultancy.destroy'])->only(['destroy']);
        view()->share('module_title', 'consultancies');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ConsultancyDataTable $dataTable)
    {
        return $dataTable->render('master.consultancy.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.consultancy.create');
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
            'name' => 'required|unique:App\Models\Consultancy,name,NULL,id,deleted_at,NULL',
        ]);
        try {
            $consultancy = new Consultancy();
            $consultancy->name = $request->get('name') ?? null;
            $consultancy->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Consultancy'])], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Consultancy $consultancy)
    {
        return view('master.consultancy.edit', compact('consultancy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consultancy $consultancy)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('App\Models\Consultancy')->ignore($consultancy->id),
            ],
        ]);
        try {
            $consultancy->name = $request->get('name') ?? null;
            $consultancy->save();
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Consultancy'])], 200);
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
            $consultancy = Consultancy::where('id', '=', $id)->first();
            if ($consultancy) {
                $consultancy->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Consultancy'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
