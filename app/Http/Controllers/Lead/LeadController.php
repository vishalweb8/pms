<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Industry;
use App\Models\LeadSource;
use App\Models\User;
use App\Models\Technology;
use App\Models\LeadTechnology;
use App\Models\LeadComment;
use Exception;
use Carbon\Carbon;
use Validator;
use DB;
use Auth;
use App\Http\Controllers\commonController;
use App\Helpers\Helper;
use App\DataTables\LeadDataTable;
use App\DataTables\LeadStatisticsDataTable;
use Spatie\Permission\Exceptions\UnauthorizedException;

class LeadController extends Controller
{
    //
    public function __construct() {
        parent::__construct();
        $this->Lead = new Lead;
        $this->LeadComment = new LeadComment;
        view()->share('module_title', 'Lead');
        $this->dateFilter = [
            'today' => 'Today',
            'week' => 'This Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'this_year' => 'This Year',
            'last_year' => 'Last Year',
        ];

        // For not show the last year option if current year is 2022
        if(now()->year == "2022") {
            unset($this->dateFilter['last_year']);
        }
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|lead.list'])->only('index', 'all');
        $this->middleware(['role_or_permission:Super Admin|lead.create'])->only(['add', 'store']);
        $this->middleware(['role_or_permission:Super Admin|lead.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|lead.view'])->only(['view']);
        $this->middleware(['role_or_permission:Super Admin|lead.destroy'])->only(['destroy']);

    }

    public function index(LeadDataTable $dataTable) {
        view()->share('module_title', 'my leads');
        $leaves = [];
        $user_id = Auth::user()->id;

        $leadOverview = LeadStatus::withCount(['lead',
                                        'lead as lead_count' => function ($query) use($user_id) {
                                            $query->where('lead_owner_id', $user_id);
                                        }
                                    ])->get();


        $leadStatusFilter = $leadOverview->pluck('name', 'id')->toArray();
        $leadStatusFilter = array(0 => 'Select Status') + $leadStatusFilter;


        $leadTotalObj = new LeadStatus();
        $leadTotalObj->name = 'All';
        $leadTotal = 0;
        foreach($leadOverview as $leadItem) {
            $leadTotal += $leadItem->lead_count;
        }
        $leadTotalObj->lead_count = $leadTotal;
        $leadOverview->prepend($leadTotalObj);

        $dateFilter = $this->dateFilter;
        $dateFilter = array('all' => 'Select Duration') + $this->dateFilter;


        return $dataTable->render('leads.common.index',compact('leaves','user_id','leadOverview', 'dateFilter', 'leadStatusFilter'));
    }

    public function add() {
        view()->share('module_title', 'Add Lead');
        $leadStatus = LeadStatus::pluck('name', 'id')->toArray();
        $industry = Industry::pluck('name', 'id')->toArray();
        $leadSource = LeadSource::pluck('name', 'id')->toArray();
        $technology = Technology::pluck('technology', 'id')->toArray();
        $countries = (new commonController)->getCountries();
        $states[''] = ' Select State';
        $cities[''] = ' Select City';
        $isSuperAdmin = 0;
        if( Auth::user()->hasRole('Super Admin') ) {
            $isSuperAdmin = 1;
        }
        $leadOwners = User::select(
            DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->has('officialUser.userTeam')->wherehas('officialUser.userTeam', function($query) {
                $query->where('name', 'like', 'Sales%');
        })->pluck('name', 'id')->toArray();
        return view('leads.common.create', compact('leadStatus', 'industry', 'leadSource', 'technology','countries', 'states', 'cities', 'isSuperAdmin', 'leadOwners'));
    }

    public function edit($leadId) {
        view()->share('module_title', 'Edit Lead');
        $isSuperAdmin = 0;
        if( Auth::user()->hasRole('Super Admin') ) {
            $isSuperAdmin = 1;
        }


        $leadRepo = Lead::with('technology')->where('id', $leadId)->first();
        if(!Helper::hasAccess($leadRepo, 'lead_owner_id')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }

        $leadStatus = LeadStatus::pluck('name', 'id')->toArray();
        $industry = Industry::pluck('name', 'id')->toArray();
        $leadSource = LeadSource::pluck('name', 'id')->toArray();

        $countries = (new commonController)->getCountries();
        $states[''] = ' Select State';
        $cities[''] = ' Select City';

        if($leadRepo->country_id){
            $states = (new commonController)->fetchState(['country_id' => $leadRepo->country_id, 'from_controller' => true]);
        } else {
            $states[''] = ' Select State';
        }

        if ($leadRepo->state_id) {
            $cities = (new commonController)->fetchCity(['state_id' => $leadRepo->state_id, 'from_controller' => true]);
        } else {
            $cities[''] = ' Select City';
        }

        $technology = Technology::pluck('technology', 'id')->toArray();

        $leadOwners = User::select(
            DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->has('officialUser.userTeam')->wherehas('officialUser.userTeam', function($query) {
                    $query->where('name', 'like', 'Sales%');
        })->pluck('name', 'id')->toArray();

        return view('leads.common.edit', compact('leadStatus', 'industry', 'leadSource', 'technology',  'leadRepo', 'countries', 'states', 'cities', 'leadOwners', 'isSuperAdmin'));
    }

    public function save(Request $request) {

        $requestData = $request->all();
        $validation_rule = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number'=> 'digits_between:8,14',
        ];

        if($request->filled('email')) {
            $validation_rule['email'] = "email";
        }
        $validator = Validator::make($request->all(), $validation_rule);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        if(isset($requestData['lead_source_id']) && !is_numeric($requestData['lead_source_id'])) {
            $leadSource = new LeadSource();
            $leadSource->name = $requestData['lead_source_id'];
            $leadSource->status = 1;
            $leadSource->save();
            $requestData['lead_source_id'] = $leadSource->id;
        }

        if(isset($requestData['lead_status_id']) && !is_numeric($requestData['lead_status_id'])) {
            $leadStatus = new LeadStatus();
            $leadStatus->name = $requestData['lead_status_id'];
            $leadStatus->status = 1;
            $leadStatus->save();
            $requestData['lead_status_id'] = $leadStatus->id;
        }


        if(isset($requestData['lead_industry_id']) && !is_numeric($requestData['lead_industry_id'])) {
            $industry = new Industry();
            $industry->name = $requestData['lead_industry_id'];
            $industry->status = 1;
            $industry->save();
            $requestData['lead_industry_id'] = $industry->id;
        }


        $leadRepo = $this->Lead->insertUpdate($requestData);

        $this->Lead->processTechnology($request->technology, $leadRepo);
        if(isset($requestData['id']) && $requestData['id'] != '' && $requestData['id'] > 0){
            $message = trans('messages.MSG_UPDATE',['name' => 'Lead source']);
        }else {
            $message = trans('messages.MSG_SUCCESS',['name' => 'Lead source']);
        }
        if(isset($leadRepo->id) && $leadRepo->id != '') {
            if( Auth::user()->hasRole('Super Admin') ) {
                return redirect()->route('lead.all')->with('message', $message);
            } else {
                return redirect()->route('lead.list')->with('message', $message);
            }
        }else {
            return redirect()->back();
        }

    }

    public function view($id) {
        $leadRepo = Lead::with(['technology', 'user', 'leadSource', 'leadStatus', 'industry', 'city', 'state', 'country', 'comments'])->find($id);

        $leaveComments = [];
        $request_to = [];
        $requestArr = [];

        $leaves = [];
        $user_id = Auth::user()->id;

        return view('leads.common.view')->with(['leaves' => $leaves,'leadRepo' => $leadRepo, 'requestUsers' => $requestArr, 'leaveComments' => $leaveComments,'user_id' => $user_id]);
    }

    public function all(LeadDataTable $dataTable) {
        view()->share('module_title', 'all leads');

        $allUsers = User::select(
            DB::raw("CONCAT(CONCAT(UCASE(LEFT(first_name, 1)), LCASE(SUBSTRING(first_name, 2))),' ',CONCAT(UCASE(LEFT(last_name, 1)), LCASE(SUBSTRING(last_name, 2)))) AS name"),'id')->has('officialUser.userTeam')->wherehas('officialUser.userTeam', function($query) {
                    $query->where('name', 'like','Sales%');
        })->pluck('name', 'id')->toArray();

        $leadOverview = LeadStatus::withCount('lead')->get();


        $leadTotalObj = new LeadStatus();
        $leadTotalObj->name = ' All';
        $leadTotal = 0;
        foreach($leadOverview as $leadItem) {
            $leadTotal += $leadItem->lead_count;
        }
        $leadTotalObj->lead_count = $leadTotal;

        $leadStatusFilter = $leadOverview->pluck('name', 'id')->toArray();
        $leadOverview->prepend($leadTotalObj);

        $dateFilter = array('all' => 'Select Duration') + $this->dateFilter;
        $leadStatusFilter = array(0 => 'Select Status') + $leadStatusFilter;
        $allUsers = array(0 => 'Select User') + $allUsers;

        return $dataTable->render('leads.common.all_index', compact('allUsers','leadOverview', 'dateFilter', 'leadStatusFilter'));

    }

    public function destroy($id)
    {
        try {
            $leadDetails = Lead::where('id', $id)->first();
            if ($leadDetails) {
                LeadTechnology::where('lead_id', $leadDetails->id)->delete();
                LeadComment::where('lead_id', $leadDetails->id)->delete();
                $leadDetails->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'lead'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function saveComment(Request $request) {

        $requestData = $request->all();
        $leadRepo = $this->LeadComment->insertUpdate($requestData);
        return redirect()->back();
    }

    public function statistics(LeadStatisticsDataTable $dataTable) {
        if(!Helper::hasAnyPermission('lead-statistics.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        view()->share('module_title', 'Lead Statistics');
        $financeYearData = Helper::getFinancialYearData(2021);

        try {
           return $dataTable->render('leads.common.lead_statistics',compact('financeYearData'));
        } catch (Exception $e) {
            // Log::error("erorr", [$e]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
