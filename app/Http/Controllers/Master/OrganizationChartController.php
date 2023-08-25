<?php

namespace App\Http\Controllers\Master;

use App\DataTables\OrganizationChartDataTable;
use App\Helpers\Helper;
use App\Http\Controllers\commonController;
use App\Http\Controllers\Controller;
use App\Models\OrganizationChart;
use App\Models\OrganizationChartReport;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganizationChartController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|organization-chart.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|organization-chart.show'])->only('show');
        $this->middleware(['role_or_permission:Super Admin|organization-chart.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|organization-chart.edit'])->only(['edit', 'update']);
        $this->middleware(['role_or_permission:Super Admin|organization-chart.destroy'])->only(['destroy']);
        view()->share('module_title', 'Organization Chart');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrganizationChartDataTable $dataTable)
    {
        return $dataTable->render('master.organization-charts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inputs = $this->getInputs();
        $chartUsers = OrganizationChart::pluck('id','user_id')->toArray();
        $inputs['users'] = array_diff_key($inputs['users'],$chartUsers);
        return view('master.organization-charts.create',$inputs);
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
            $organizationChart = OrganizationChart::updateOrCreate(['user_id'=>$request->user_id],$data);
            $organizationChart->reportingTo()->sync($request->reporting_to);

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Orgnaization Chart'])], 200);
        } catch (\Throwable $th) {
            Log::error('getting error while creating org chart:- '.$th);
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrganizationChart  $organizationChart
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if($request->ajax()) {
            $childs = [];
            $data['name'] = 'Inexture';
            $data['title'] = 'Company';
            $members = OrganizationChart::select('organization_charts.*')->where('status',1)->has('user')->with('user');
            if(empty($request->team)) {
                $members = $members->where('is_top_level',1)->get();
            } else { // for filter by teams
                $team = Team::find($request->team);
                $data['name'] = $team->name ?? 'Inexture';
                $data['title'] = '-';
                if(!empty($team->team_lead_id)) {
                    $userIds = $team->team_lead_id;
                    $userIds = explode(',',$userIds);
                } else {
                    $userIds = $team->userOfficialDetail->pluck('id')->toArray();
                }
                $members = $members->whereIn('user_id',$userIds)->get(); 
            }

            foreach($members as $member) {
                $childsData =  [];
                $childsData['name'] = $member->user->full_name;
                $childsData['title'] = $member->user->officialUser->userDesignation->name ?? '-';
                $childsData['className'] = Helper::getClassName($member->user);
                $children = $this->getChildMember($member->user_id);
                if(!empty($children)) {
                    $childsData['children'] = $children;
                }
                $childs[]= $childsData;
            }
            
            $data['children'] = $childs;
            return response()->json($data);
        }
        $teams = (new commonController)->getTeams();
        $teams[''] = 'All Teams';
        return view('master.organization-charts.show',compact('teams'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrganizationChart  $organizationChart
     * @return \Illuminate\Http\Response
     */
    public function edit(OrganizationChart $organizationChart)
    {
        $inputs = $this->getInputs();
        $inputs['organizationChart'] = $organizationChart;
        $inputs['reportingTo'] = $organizationChart->reportingTo->pluck('id');

        return view('master.organization-charts.edit',$inputs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrganizationChart  $organizationChart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrganizationChart $organizationChart)
    {
        try {
            $data = $request->all();
            $data['status'] = $request->input('status',0);
            $organizationChart->update($data);
            $organizationChart->reportingTo()->sync($request->reporting_to);
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Orgnaization Chart'])], 200);
        } catch (\Throwable $th) {
            Log::error('getting error while updating org chart:- '.$th);
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrganizationChart  $organizationChart
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrganizationChart $organizationChart)
    {
        try {
            $organizationChart->delete();
            return response()->json(['success' => true, 'message' => trans('messages.MSG_DELETE',['name' => 'Orgnaization Chart'])]);
        } catch (\Throwable $th) {
            Log::error('getting error while deleting org chart:- '.$th);
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
        $inputs['reportings'] = $inputs['users'];
        unset($inputs['reportings']['']);
        return $inputs;
    }

    /**
     * get child member of member
     *
     * @param  mixed $userId
     * @return void
     */
    public function getChildMember($userId)
    {
        try {
            $childs = [];
            $members = OrganizationChartReport::where('user_id',$userId)->with('organization.user')->get();

            foreach($members as $member) {
                $childsData =  [];
                if(!empty($member->organization->user)) {
                    $childsData['name'] = $member->organization->user->full_name;
                    $childsData['title'] = $member->organization->user->officialUser->userDesignation->name ?? '-';
                    $childsData['className'] = Helper::getClassName($member->organization->user);
                    $children = $this->getChildMember($member->organization->user_id);
                    if(!empty($children)) {
                        $childsData['children'] = $children;
                    }
                    $childs[]= $childsData;
                }
            }
            return $childs;
        } catch (\Throwable $th) {
            Log::error('Getting error while fetching child reporting member:- '.$th);
            return null;
        }
    }
}
