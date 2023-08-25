<?php

namespace App\Http\Controllers;

use App\DataTables\ExpectedRevenueDataTable;
use App\Http\Requests\ExptRevRequest;
use Illuminate\Http\Request;
use App\Models\ExpectedRevenue;
use Illuminate\Support\Facades\Log;

class ExpectedRevenueController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        view()->share('module_title', 'Expected Revenue');
        $this->middleware(['role_or_permission:Super Admin|expected-revenue.list'])->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExpectedRevenueDataTable $dataTable)
    {
        return $dataTable->render('expected-revenue.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = $this->getExternalProjects();
        return view('expected-revenue.create',compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExptRevRequest $request)
    {
        try {
            $data = $request->all();
            $query = $request->only(['project_id','month','year']);
            ExpectedRevenue::updateOrCreate($query, $data);

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Expected Revenue'])], 200);
        } catch (\Throwable $th) {
            Log::error('getting error while creating expected revenue:- '.$th);
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $months = [
            date('n'),
            date('n',strtotime("+1 Months")),
            date('n',strtotime("+2 Months"))
        ];
        $revenues = ExpectedRevenue::where('year','>=',date('Y'))->whereIn('month',$months)->with('project')->orderBy('month')->orderBy('year')->get()->groupBy('project_id');
        return view('expected-revenue.show',compact('revenues','months'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpectedRevenue  $expectedRevenue
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpectedRevenue $expectedRevenue)
    {
        $projects = $this->getExternalProjects();
        return view('expected-revenue.edit',compact('projects','expectedRevenue'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpectedRevenue  $expectedRevenue
     * @return \Illuminate\Http\Response
     */
    public function update(ExptRevRequest $request, ExpectedRevenue $expectedRevenue)
    {
        $request->validate([
            'project_id' => 'unique:expected_revenues,project_id,'.$expectedRevenue->id.',id,month,'.$request->month.',year,'.$request->year,
        ],[
            'project_id.unique' => 'Expected revenue already created for this project, month and year.'
        ]);
        try {
            $data = $request->all();
            $expectedRevenue->update($data);
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Expected Revenue'])], 200);
        } catch (\Throwable $th) {
            Log::error('getting error while updating expected revenue:- '.$th);
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpectedRevenue  $expectedRevenue
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpectedRevenue $expectedRevenue)
    {
        try {
            $expectedRevenue->delete();
            return response()->json(['success' => true, 'message' => trans('messages.MSG_DELETE',['name' => 'Expected Revenue'])]);
        } catch (\Throwable $th) {
            Log::error('getting error while deleting expected revenue:- '.$th);
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
}
