<?php

namespace App\Http\Controllers\Master;

use App\DataTables\TeamsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\commonController;
use App\Models\UserOfficialDetail;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|teams.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|teams.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|teams.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|teams.destroy'])->only(['destroy']);
        view()->share('module_title', 'team');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TeamsDataTable $dataTable)
    {
        return $dataTable->render('master.teams.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // To show only team lead in teamleaders dropdown
        // $teamLeaders = (new commonController)->getTeamLeader();

        // To show all users in teamleaders dropdown
        $teamLeaders = (new commonController)->getUsers();
        unset($teamLeaders['']);
        return view('master.teams.create', compact('teamLeaders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            "team_lead_id.required" => "Please select Team leader.",
        ];
        $this->validate($request, [
            'name' => "required | unique:App\Models\Team,name",
            'team_lead_id'  => 'required'
        ], $messages);
        try {
            $teams = new Team();
            $teams->name = $request->get('name') ?? null;
            $teams->team_lead_id = implode(',',$request->get('team_lead_id')) ?? null;
            $teams->status = $request->filled('status-teams') ? 1 : 0;
            $teams->save();
            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Team'])], 200);
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
    public function edit(Request $request, Team $team)
    {
        // To show only team lead in teamleaders dropdown
        // $teamLeaders = (new commonController)->getTeamLeader();

        // To show all users in teamleaders dropdown
        $teamLeaders = (new commonController)->getUsers();
        unset($teamLeaders['']);
        $teamLeadersSelected = explode(',',$team->team_lead_id);

        return view('master.teams.edit', compact('team', 'teamLeaders','teamLeadersSelected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $messages = [
            "team_lead_id.required" => "Please select Team leader.",
        ];
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('App\Models\Team')->ignore($team->id),
            ],
            'team_lead_id'  => 'required'
        ], $messages);
        try {
            if(isset($team->id) && !empty($team->id)){
                $userDetails =  User::whereHas('userRole',function($qry){
                    $qry->where('code','=','EMP');
                })->whereHas('officialUser',function($q) use($team) {
                    $q->where('team_id',$team->id)->select('reporting_ids');
                })->with('officialUser')->get();

                foreach( $userDetails as $user){
                    $user->officialUser->reporting_ids = implode(',',$request->get('team_lead_id')) ?? null;
                    $user->officialUser->save();
                }
            }
            $team->name = $request->get('name') ?? null;
            $team->team_lead_id = implode(',',$request->get('team_lead_id')) ?? null;
            $team->status = $request->filled('status-teams') ? 1 : 0;
            $team->save();
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Team'])], 200);
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
            $teams = Team::where('id', '=', $id)->first();
            if ($teams) {
                $teams->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Team'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
