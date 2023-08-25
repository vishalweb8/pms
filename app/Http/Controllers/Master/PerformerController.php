<?php

namespace App\Http\Controllers\Master;

use App\DataTables\PerformerDataTable;
use App\Http\Controllers\commonController;
use App\Http\Controllers\Controller;
use App\Models\Performer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerformerController extends Controller
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        view()->share('module_title', 'Performer');
        $this->middleware(['role_or_permission:Super Admin|performer.list'])->only('index');
    }
    
    /**
     * for get list of top 5 and worst 5 perfomers
     *
     * @param  mixed $request
     * @return void
     */
    public function index(PerformerDataTable $dataTable)
    {
        return $dataTable->render('performer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inputs = $this->getInputs();
        return view('performer.create',$inputs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        try {
            $data = $request->all();
            $data['status'] = $request->input('status',0);
            Performer::updateOrCreate(['user_id'=>$request->user_id],$data);

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Performer'])], 200);
        } catch (\Throwable $th) {
            Log::error('getting error while creating performer:- '.$th);
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * for get list of top 5 and worst 5 perfomers
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        $lastMonthYear = lastMonthYear();
        $performers = Performer::select('performers.*',DB::raw("(revenue-expense) as net_income"))
        ->has('user')
        ->with(['user.userTeam','user' => function($query){
            $query->withCount([
                'userProjects as project_count'
            ]);
        }])
        ->where('month_year',$lastMonthYear)
        ->where('status',1)
        ->limit(5);
        $wrostsPerformers = clone $performers;
        $topUserIds = $performers->pluck('user_id')->toArray();
        $tops = $performers->orderBy('net_income','desc')->get();
        $wrosts = $wrostsPerformers->whereNotIn('user_id',$topUserIds)->orderBy('net_income','asc')->get();
        return view('performer.show',compact('tops','wrosts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Performer  $performer
     * @return \Illuminate\Http\Response
     */
    public function edit(Performer $performer)
    {
        $inputs = $this->getInputs();
        $inputs['performer'] = $performer;

        return view('performer.edit',$inputs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Performer  $performer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Performer $performer)
    {
        try {
            $data = $request->only(['expense','revenue']);
            $data['status'] = $request->input('status',0);
            $performer->update($data);
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Performer'])], 200);
        } catch (\Throwable $th) {
            Log::error('getting error while updating performer:- '.$th);
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Performer  $performer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Performer $performer)
    {
        try {
            $performer->delete();
            return response()->json(['success' => true, 'message' => trans('messages.MSG_DELETE',['name' => 'Performer'])]);
        } catch (\Throwable $th) {
            Log::error('getting error while deleting performer:- '.$th);
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * for get data of drop-down fields
     *
     * @return void
     */
    public function getInputs()
    {
        $inputs['users'] = (new commonController)->getUsers();
        $inputs['teams'] = (new commonController)->getTeams();
        $inputs['designations'] = (new commonController)->getUserDesignation();
        return $inputs;
    }
}
