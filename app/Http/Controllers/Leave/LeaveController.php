<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Http\Controllers\commonController;
use App\DataTables\LeaveDataTable;
use App\DataTables\TeamLeaveDataTable;
use App\DataTables\AllLeaveDataTable;
use App\DataTables\AllLeaveStatisticsDataTable;
use App\DataTables\LeaveCompensationDataTable;
use App\DataTables\AllLeaveCompensationDataTable;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\LeaveAllocation;
use App\Models\LeaveComment;
use App\Models\LeaveCompensation;
use App\Models\Team;
use App\Models\User;
use App\Models\UserOfficialDetail;
use Exception;
use Carbon\Carbon;
use Validator;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Helper;
use App\Jobs\ApproveLeaveRequest;
use App\Jobs\RejectLeaveRequest;
use App\Jobs\SendLeaveRequestEmail;
use App\Jobs\CancelLeaveRequest;
use App\Jobs\ApproveLeaveCompensationRequest;
use App\Jobs\RejectLeaveCompensationRequest;
use App\Jobs\CancelLeaveCompensationRequest;
use App\Models\PendingSodEod;
use App\Models\LeaveCompensationComment;
use Illuminate\Support\Facades\URL;
use App\Jobs\SendLeaveCompensationRequestEmail;

class LeaveController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->middleware(['role_or_permission:Super Admin|designation.list'])->only('index');
        // $this->middleware(['role_or_permission:Super Admin|designation.create'])->only(['create', 'store']);
        // $this->middleware(['role_or_permission:Super Admin|designation.edit'])->only(['edit', 'store']);
        // $this->middleware(['role_or_permission:Super Admin|designation.destroy'])->only(['destroy']);
        view()->share('module_title', 'Leave');
    }

    public function dashboard(LeaveDataTable $dataTable,Request $request)
    {
        view()->share('module_title', 'my leaves');
        return $dataTable->render('leaves.common.index');
    }

    public function addLeaveView(Request $request, $id = null)
    {
        try {
            $requestUsers = Helper::fetchReportingIds(Auth::id());
            $allUsers = User::all()->pluck('full_name','id')->toArray();

            if ($id != null) {
                $leave = Leave::find($id);
                $leave->request_to = explode(',', $leave->request_to);
                return view('leaves.common.edit', compact('allUsers'))->with(['leave' => $leave, 'requestUsers' => $requestUsers]);
            } else {
                return view('leaves.common.create', compact('allUsers'))->with('requestUsers', $requestUsers);
            }
        } catch (Exception $e) {

        }
    }

    public function addLeaveTeamView(Request $request, $id = null)
    {
        $addTeamLeave = 1;
        try {
            $requestTo = (new commonController)->getUsers();
            $requestFrom = (new commonController)->getUsers(Auth::user()->id);
            $allUsers = User::all()->pluck('full_name','id')->toArray();

            unset($requestTo[""]);

            if ($id != null) {
                $leave = Leave::find($id);
                $request['request_from'] = $leave->request_from;

                $requestTo = Helper::fetchReportingIds($request->request_from);
                unset($requestTo[$leave->request_from]);
                $leave->request_to = explode(',', $leave->request_to);
                return view('leaves.common.edit', compact('allUsers'))->with(['leave' => $leave, 'requestUsers' => $requestTo,'requestFrom' => $requestFrom, 'addTeamLeave' => $addTeamLeave]);
            } else {
                return view('leaves.common.create', compact('allUsers'))->with(['requestUsers' => $requestTo,'requestFrom' => $requestFrom, 'addTeamLeave' => $addTeamLeave]);
            }
        } catch (Exception $e) {
            dd($e);
        }
    }

    // public function addAllEmpLeave(Request $request, $id = null)
    // {
    //     $addTeamLeave = 1;
    //     try {
    //         $requestUsers = (new commonController)->getUsers(Auth::user()->id);
    //         unset($requestUsers[""]);
    //         if ($id != null) {
    //             $leave = Leave::find($id);
    //             unset($requestUsers[Auth::user()->id]);
    //             $leave->request_to = explode(',', $leave->request_to);
    //             return view('leaves.common.edit')->with(['leave' => $leave, 'requestUsers' => $requestUsers, 'addTeamLeave' => $addTeamLeave]);
    //         } else {
    //             return view('leaves.common.create')->with(['requestUsers' => $requestUsers, 'addTeamLeave' => $addTeamLeave]);
    //         }
    //     } catch (Exception $e) {
    //     }
    // }

    public function saveLeaveData(Request $request)
    {
        $userId = ($request->request_from) ? $request->request_from : Auth::user()->id;

        try {
            $validator = Validator::make($request->all(), [
                // 'request_to' => 'required',
                'type' => 'required',
                'start_date' => 'required|before_or_equal:end_date',
                'end_date' => 'required|after_or_equal:start_date',
                'reason' => 'required',
                // 'emergency_contact' => 'nullable|numeric|gt:0|digits:10',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            $startYear = Helper::getFinancialStartYearFromDate($request->start_date);
            $endYear = Helper::getFinancialStartYearFromDate($request->end_date);
            // for check leave request in forbidden date range
            if($startYear != $endYear) {
                return redirect()->back()->with(['error' => trans('messages.ERR_FORBIDDEN_DATE')]);
            }

            // check if leave already added in this date
            $leaves = Leave::where('request_from', $userId)->whereIn('status',['approved','pending'])->select('start_date', 'end_date');
            if($request->id != null)
            {
                $leaves = $leaves->where('id','!=',$request->id);
            }
            $leaves = $leaves->get();
            $allLeaves = (new commonController)->checkPastRequest($leaves);
            foreach ($allLeaves as $pastLeave) {
                if($pastLeave == $request->start_date || $pastLeave == $request->end_date) {
                    return redirect()->back()->with(['error' => trans('messages.ERR_DUPLICATE_DATE',['name' => 'leave'])]);
                }
            }
            // dd($request);
            if ($request->id) {
                $leave = Leave::find($request->id);
            } else {
                $leave = new Leave;
            }
            $requestTo = implode(',', $request->request_to);
            if($request->id == NULL || empty($request->id)){
                $leave->request_from = isset($request->request_from) ? $request->request_from : Auth::user()->id;
            }
            $leave->request_to = $requestTo;
            $leave->type = $request->type;
            $leave->halfday_status = ($request->type != 'full') ? $request->halfday_status : null;
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->end_date;
            $leave->return_date = $request->return_date;
            $leave->duration = $request->duration;
            $leave->reason = $request->reason;
            $leave->isadhoc_leave = ($request->isadhoc_leave == 'on') ? 1 : 0;
            $leave->adhoc_status = ($leave->isadhoc_leave == 1) ? $request->adhoc_status : null;
            $leave->available_on_phone = ($request->available_on_phone == 'on') ? 1 : 0 ;
            $leave->available_on_city = ($request->available_on_city == 'on') ? 1 : 0;
            $leave->emergency_contact = $request->emergency_contact;
            // Add created by only when new entry
            if(!$leave->id) {
                $leave->status = "pending";
                $leave->created_by = Auth::user()->id;
            }

            $leave->save();

            // Remove the pending_sod_eod_entries
            if($leave->status == "approved") {
                $this->ManagePendingSODEntries($leave);
            }

            $userDetails = User::whereIn('id', $request->request_to)->pluck('email', 'id');
            $send_to_users = config('constant.default_users_email');
            if(!empty($userDetails)) {
                $send_to_users = array_values(array_unique(array_merge($userDetails->toArray(), $send_to_users)));
            }
            if(!empty($send_to_users)) {
                $action_url = route('leave-team-view',$leave->id);
               SendLeaveRequestEmail::dispatch($leave, $send_to_users, $action_url);

            }

            if($request->id){
                $message = trans('messages.MSG_UPDATE',['name' => 'Leave']);
            }else {
                $message = trans('messages.MSG_SUCCESS',['name' => 'Leave']);
            }


            if($request->filled('_url')) {
                return redirect($request->input('_url'))->with('message', $message);
            }
            if (!isset($request->request_from)) {
                return redirect()->route('leave-dashboard')->with('message', $message);
            } else {
                if($request->allempflag == 1){
                    return redirect()->route('leave-all-employee')->with('message', $message);
                }else{
                    return redirect()->route('leave-team')->with('message', $message);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function viewLeave($id)
    {
        $requestUsers = (new commonController)->getUsers(Auth::user()->id);

        unset($requestUsers[""]);

        try {
            $leaveView = Leave::find($id);
            $leaveComments = LeaveComment::where('leave_id',$id)->with('reviewUser')->orderBy('id','Desc')->get();
            $request_to = explode(',', $leaveView->request_to);
            $requestArr = [];

            unset($request_to[Auth::user()->id]);

            foreach ($request_to as $key => $value) {
                if(isset($requestUsers[$value])){
                  $requestArr[$value] = $requestUsers[$value];
                }
            }

            $leaves = LeaveAllocation::where('allocated_year', Helper::getFinancialStartYearFromDate(today()))->where('user_id',Auth::user()->id)->latest()->first();
            $user_id = Auth::user()->id;

            return view('leaves.common.view')->with(['leaves' => $leaves,'leaveView' => $leaveView, 'requestUsers' => $requestArr, 'leaveComments' => $leaveComments,'user_id' => $user_id]);
        } catch (EXception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function viewTeamLeave($id)
    {
        try {
            $leaveView = Leave::find($id);
            $requestUsers = (new commonController)->getUsers();
            unset($requestUsers[""]);

            $leaveComments = LeaveComment::where('leave_id',$id)->with('reviewUser')->orderBy('id','Desc')->get();
            $request_to = explode(',', $leaveView->request_to);
            $requestArr = [];

            foreach ($request_to as $key => $value) {
                if(isset($requestUsers[$value])){
                $requestArr[$value] = $requestUsers[$value];
                }
            }

            $leaveUser = User::withBlocked()->where('id',$leaveView->request_from)->with('userRole')->first();
            $leaves = LeaveAllocation::where('allocated_year', Helper::getFinancialStartYearFromDate(today()))->where('user_id',$leaveView->request_from)->first();
            $user_id = $leaveView->request_from;

            return view('leaves.common.team_view')->with(['leaves' => $leaves,'leaveView' => $leaveView, 'requestUsers' => $requestArr, 'leaveComments' => $leaveComments,'leaveUser' => $leaveUser,'user_id'=> $user_id]);
        } catch (EXception $e) {
            Log::error("erorr",[$e]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function teamLeave(TeamLeaveDataTable $dataTable)
    {
        view()->share('module_title', 'Leave Requests');
        $teamLeave = 1;
        try {
            return $dataTable->render('leaves.common.team_index', compact('teamLeave'));
        } catch (Exception $e) {
            Log::error("erorr", [$e]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function manageLeave()
    {
        try {

            return view('leaves.common.manage');

        } catch (Exception $e) {

        }
    }

    public function leaveRequest(Request $request)
    {
        try {
            if ($request->ajax()) {
                $leaveView = Leave::find($request->commentId);
                $leaveData = new LeaveComment;
                $leaveData->leave_id = $request->commentId;
                $leaveData->request_from = $leaveView->userLeave->id;
                $leaveData->review_by = Auth::user()->id;
                if($request->dataAction == 'approve_leave'){
                    $status = "approved";
                } elseif($request->dataAction == 'cancel_leave') {
                    $status = "cancelled";
                } elseif($request->dataAction == 'add_comment') {
                    $status = "pending";
                } else{
                    $status = "rejected";
                }
                // dump($leaveView);
                $leaveData->status = $status;
                if (Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {
                    if($status == 'approved'){
                        $leaveDataStored['approved_by'] = Auth::user()->id;
                    } else if($status == 'rejected'){
                        $leaveDataStored['rejected_by'] = Auth::user()->id;
                    } else{
                        $leaveDataStored['approved_by'] = NULL;
                    }
                    $leaveDataStored['status'] = $status;
                    $leaveView->update($leaveDataStored);
                    $leaveStatus = $status;
                    if($leaveStatus == 'approved'){
                        $current_year = date("Y");
                        $past_year = date("Y",strtotime("-1 year"));
                        $next_year = date("Y",strtotime("+1 year"));

                        $from_date = $past_year."-04-01";
                        $to_date = $current_year."-03-31";

                        $current_date_timestamp = strtotime(date('Y-m-d'));
                        $last_date_timestamp = strtotime($to_date);

                        if($current_date_timestamp <= $last_date_timestamp){
                              $userLeaves = LeaveAllocation::where('allocated_year', Helper::getFinancialStartYearFromDate(today()))->where('user_id',$leaveView->userLeave->id)
                              ->whereBetween('updated_at',[$from_date,$to_date])->first();
                        }else{

                            $from_date = $current_year."-04-01";
                            $to_date = $next_year."-03-31";
                            $userLeaves = LeaveAllocation::where('allocated_year', Helper::getFinancialStartYearFromDate(today()))->where('user_id',$leaveView->userLeave->id)
                              ->whereBetween('updated_at',[$from_date,$to_date])->first();
                        }

                        if($userLeaves){
                            $userLeaves->used_leave = $userLeaves->used_leave  + doubleval($leaveView->duration);
                            $userLeaves->pending_leave = ($userLeaves->allocated_leave  - $userLeaves->used_leave > 0) ? $userLeaves->allocated_leave  - $userLeaves->used_leave  : 0.00 ;
                            $userLeaves->remaining_leave =($userLeaves->allocated_leave  - $userLeaves->used_leave > 0) ? $userLeaves->allocated_leave  - $userLeaves->used_leave  : 0.00 ;
                            $userLeaves->exceed_leave = ($userLeaves->used_leave  - $userLeaves->allocated_leave > 0) ?$userLeaves->used_leave  - $userLeaves->allocated_leave  : 0.00 ;
                            $userLeaves->save();
                        }
                    }
                }
                else{
                    $leaveStatus = 'pending';
                }
                $leaveData->comments = $request->comments;
                $leaveData->save();

                $request_to = explode(',',$leaveView->request_to);
                $userDetails = User::whereIn('id', $request_to)->pluck('email', 'id');
                $cc_users = config('constant.default_users_email');
                if(!empty($userDetails)) {
                    $cc_users = array_values(array_unique(array_merge($userDetails->toArray(), $cc_users)));
                }


                if (Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {
                    if ($status == "approved") {
                        ApproveLeaveRequest::dispatch($leaveView, $leaveData, $cc_users);
                    } elseif($status == "cancelled") {
                        CancelLeaveRequest::dispatch($leaveView, $leaveData, $cc_users);
                    } else {
                        RejectLeaveRequest::dispatch($leaveView, $leaveData, $cc_users);
                    }
                }
                $this->ManagePendingSODEntries($leaveView); // For manage the pending_sod_eod_entries

                $leaveComments = LeaveComment::where('leave_id', $leaveData->leave_id)->with('reviewUser')->orderBy('id', 'Desc')->get();
                $returnHTML = view("leaves.partials.comments", compact(['leaveComments', 'leaveView']))->render();
                $data['html'] = $returnHTML;
                $data['status'] = $leaveStatus;
                return response()->json(['message' => trans('messages.MSG_ON_MODAL',['name' => 'Leave']). ucfirst($status), 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function cancelLeave($id)
    {
        try {
            $leave = Leave::where('id', '=', $id)->first();
            if ($leave) {
                $leave->update(['status' => "cancelled",'approved_by' => NULL]);
                $leaveData = new LeaveComment;
                $leaveData->leave_id = $leave->id;
                $leaveData->request_from = $leave->userLeave->id;
                $leaveData->review_by = Auth::user()->id;
                $leaveData->status = 'cancelled';
                $leaveData->save();
            }
            return response()->json(['message' => trans('messages.MSG_REQUEST_CANCELLED',['name' => 'Leave'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function setTotalLeave(Request $request)
    {
        try {

            $setTotal = LeaveAllocation::all();

        } catch (Exception $e) {

        }
    }

    public function allEmployeeLeave(AllLeaveDataTable $dataTable)
    {
        view()->share('module_title', 'All Employee leaves');
        $teamLeave = 1;
        //$allUsers = User::pluck('full_name');
        $teams = (new commonController)->getTeams();
        try {
            return $dataTable->render('leaves.common.all_index', compact('teamLeave', 'teams'));
        } catch (Exception $e) {
            Log::error("erorr", [$e]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // public function AllEmployeeLeaveAdd(Request $request,$id = null)
    // {
    //     // $teamWfh = 2;
    //     try {
    //         $requestUsers = (new commonController)->getUsers(Auth::user()->id);
    //         unset($requestUsers[""]);
    //         if ($id != null) {
    //             $leave = Leave::find($id);
    //             unset($requestUsers[Auth::user()->id]);
    //             $leave->request_to = explode(',', $leave->request_to);
    //             return view('leaves.common.edit')->with(['leave' => $leave, 'requestUsers' => $requestUsers]);
    //         } else {
    //             return view('leaves.common.create')->with('requestUsers', $requestUsers);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 400);
    //     }
    // }

    public function fetchRequestFrom(Request $request)
    {
        try {
            if ($request->ajax()) {
                $users = Helper::fetchReportingIds($request->request_from);
                return response()->json($users);
            }
        } catch (Exception $e) {

        }
    }

    public function allEmployeeLeaveStatistics(AllLeaveStatisticsDataTable $dataTable){
        view()->share('module_title', 'Leaves Statistics');
        try {
           return $dataTable->render('leaves.common.leaves_statistics');
        } catch (Exception $e) {
            Log::error("erorr", [$e]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function ManagePendingSODEntries($leave)
    {
        if(!empty($leave)) {
            $start_date = Carbon::parse($leave->start_date);
            // If leave approved then remove that day entries from the pending_sod_eod_entries
            if(in_array($leave->status, ['approved']) && !$start_date->greaterThan(now())) {
                // Fetch the pending_sod_eod_entries and remove it.
                PendingSodEod::where('date', '>=', Carbon::parse($leave->start_date)->format('Y-m-d'))
                                ->where('date', '<=', Carbon::parse($leave->end_date)->format('Y-m-d'))->delete();
            }
        }
    }

    public function leaveCompensationDashboard(LeaveCompensationDataTable $dataTable)
    {
        view()->share('module_title', 'leaves compensation');
        $leaves = LeaveAllocation::with('user')->where('user_id',Auth::user()->id)->where('allocated_year',date("Y"))->latest()->first();
        // return view('leaves.common.dashboard')->with('leaves', $leaves);
        $user_id = Auth::user()->id;
        $pendingLeave = LeaveCompensation::where('request_from',$user_id)->where('status','=','pending')->count();
        $approvedLeave = LeaveCompensation::where('request_from',$user_id)->where('status','=','approved')->count();
        $cancelledLeave = LeaveCompensation::where('request_from',$user_id)->where('status','=','cancelled')->count();
        $rejectedLeave = LeaveCompensation::where('request_from',$user_id)->where('status','=','rejected')->count();
        $leaveCountArray = array(
            ['name' => 'Pending','count' => $pendingLeave ? $pendingLeave : 0],
            ['name' => 'Approved','count' => $approvedLeave ? $approvedLeave : 0],
            ['name' => 'Cancelled','count' => $cancelledLeave ? $cancelledLeave : 0],
            ['name' => 'Rejected','count' => $rejectedLeave ? $rejectedLeave : 0],
        );
        return $dataTable->render('leaves.common.compensation-index',compact('leaves','user_id','leaveCountArray'));
    }

    public function addLeaveCompensationView(Request $request, $id = null)
    {
        try {

            $requestUsers = Helper::fetchReportingIds(Auth::id());

            if ($id != null) {
                $leave = LeaveCompensation::find($id);
                $leave->request_to = explode(',', $leave->request_to);
                return view('leaves.common.edit-compensation')->with(['leave' => $leave, 'requestUsers' => $requestUsers]);
            } else {
                return view('leaves.common.create-compensation')->with('requestUsers', $requestUsers);
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    public function saveLeaveCompensationData(Request $request)
    {
        $requestUsers = [];

        $userId = ($request->request_from) ? $request->request_from : Auth::user()->id;
        $adminReporter = User::where('email', config('constant.default_users_email.admin'))->first();
        $hrReporter = User::where('email', config('constant.default_users_email.hr'))->first();
        if ($adminReporter != null) {
            $requestUsers[$adminReporter->id] = isset($adminReporter) ? ($adminReporter->first_name.' '.$adminReporter->last_name) : config('constant.default_users_name.admin');
        }
        if ($hrReporter) {
            $requestUsers[$hrReporter->id] = isset($hrReporter) ? ($hrReporter->first_name.' '.$hrReporter->last_name) : config('constant.default_users_name.hr');
        }
        try {
            $validator = Validator::make($request->all(), [
                // 'request_to' => 'required',
                'type' => 'required',
                'start_date' => 'required|before_or_equal:end_date',
                'end_date' => 'required|after_or_equal:start_date',
                'reason' => 'required',
                // 'emergency_contact' => 'nullable|numeric|gt:0|digits:10',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }
            // check if leave already added in this date
            $leaves = LeaveCompensation::where('request_from', $userId)->whereIn('status',['approved','pending'])->select('start_date', 'end_date');
            if($request->id != null)
            {
                $leaves = $leaves->where('id','!=',$request->id);
            }
            $leaves = $leaves->get();
            $allLeaves = (new commonController)->checkPastRequest($leaves);
            foreach ($allLeaves as $pastLeave) {
                if($pastLeave == $request->start_date || $pastLeave == $request->end_date) {
                    return redirect()->back()->with(['error' => trans('messages.ERR_DUPLICATE_DATE',['name' => 'leave compensation'])]);
                }
            }
            // dd($request);
            if ($request->id) {
                $leave = LeaveCompensation::find($request->id);
            } else {
                $leave = new LeaveCompensation;
            }
            $requestTo = implode(',', $requestUsers);
            if($request->id == NULL || empty($request->id)){
                $leave->request_from = isset($request->request_from) ? $request->request_from : Auth::user()->id;
            }
            $leave->request_to = $requestTo;
            $leave->type = $request->type;
            $leave->halfday_status = ($request->type != 'full') ? $request->halfday_status : null;
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->end_date;
            $leave->duration = $request->duration;
            $leave->reason = $request->reason;
            // Add created by only when new entry
            if(!$leave->id) {
                $leave->status = "pending";
                $leave->created_by = Auth::user()->id;
            }

            $leave->save();

            // Remove the pending_sod_eod_entries
            if($leave->status == "approved") {
                $this->ManagePendingSODEntries($leave);
            }

            $userDetails = User::whereIn('id', $requestUsers)->pluck('email', 'id');
            $send_to_users = config('constant.default_users_email');
            if(!empty($userDetails)) {
                $send_to_users = array_values(array_unique(array_merge($userDetails->toArray(), $send_to_users)));
            }

            if(!empty($send_to_users)) {
                $action_url = route('leave-compensation-team-view',$leave->id);
                SendLeaveCompensationRequestEmail::dispatch($leave, $send_to_users, $action_url);
            }

            if($request->filled('_url')) {
                return redirect($request->input('_url'));
            }
            if (!isset($request->request_from)) {
                return redirect()->route('leave-compensation-dashboard');
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function  viewLeaveCompensation($id)
    {

        try {
            $leaveView = LeaveCompensation::find($id);
            $leaveComments = LeaveCompensationComment::where('leave_compensation_id',$id)->with('reviewUser')->orderBy('id','Desc')->get();
            $request_to = explode(',', $leaveView->request_to);

            $leaves = LeaveAllocation::where('allocated_year', Helper::getFinancialStartYearFromDate(today()))->where('user_id',Auth::user()->id)->latest()->first();
            $user_id = Auth::user()->id;

            return view('leaves.common.view-compensation')->with(['leaves' => $leaves,'leaveView' => $leaveView, 'requestUsers' => $request_to, 'leaveComments' => $leaveComments,'user_id' => $user_id]);
        } catch (EXception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancelLeaveCompensation($id){
        try {
            $leave = LeaveCompensation::where('id', '=', $id)->first();
            if ($leave) {
                $leave->update(['status' => "cancelled",'approved_by' => NULL]);
                $leaveData = new LeaveCompensationComment;
                $leaveData->leave_compensation_id = $leave->id;
                $leaveData->request_from = $leave->userLeave->id;
                $leaveData->review_by = Auth::user()->id;
                $leaveData->status = 'cancelled';
                $leaveData->save();
            }
            return response()->json(['message' => trans('messages.MSG_REQUEST_CANCELLED',['name' => 'Leave Compensation'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function allEmployeeCompensationLeave(AllLeaveCompensationDataTable $dataTable)
    {
        view()->share('module_title', 'All compensations');
        $teamLeave = 1;
        $allUsers = User::pluck('full_name');
        $teams = (new commonController)->getTeams();
        try {
            $pendingLeave = LeaveCompensation::where('status','=','pending')->count();
            $approvedLeave = LeaveCompensation::where('status','=','approved')->count();
            $cancelledLeave = LeaveCompensation::where('status','=','cancelled')->count();
            $rejectedLeave = LeaveCompensation::where('status','=','rejected')->count();
            $allLeaveCountArray = array(
                ['name' => 'pending','count' => $pendingLeave ? $pendingLeave : 0],
                ['name' => 'approved','count' => $approvedLeave ? $approvedLeave : 0],
                ['name' => 'cancelled','count' => $cancelledLeave ? $cancelledLeave : 0],
                ['name' => 'rejected','count' => $rejectedLeave ? $rejectedLeave : 0],
            );
            return $dataTable->render('leaves.common.all_index_compensation', compact('teamLeave', 'allUsers', 'teams','allLeaveCountArray'));
        } catch (Exception $e) {
            Log::error("erorr", [$e]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function addAllLeaveCompensationView(Request $request, $id = null)
    {
        $addTeamLeave = 1;
        try {
            $requestFrom = (new commonController)->getUsers(Auth::user()->id);
            $requestUsers = [];
            if ($id != null) {
                $leave = LeaveCompensation::find($id);
                $request['request_from'] = $leave->request_from;
                $leave->request_to = explode(',', $leave->request_to);
                $requestUsers = Helper::fetchReportingIds($leave->request_from);
                return view('leaves.common.edit-compensation')->with(['leave' => $leave, 'requestUsers' => $requestUsers,'requestFrom' => $requestFrom, 'addTeamLeave' => $addTeamLeave]);
            } else {
                $requestUsers = (new commonController)->getUsers(Auth::user()->id);
                return view('leaves.common.create-compensation')->with(['requestUsers' => $requestUsers,'requestFrom' => $requestFrom, 'addTeamLeave' => $addTeamLeave]);
            }
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function leaveRequestCompensation(Request $request)
    {
        try {
            if ($request->ajax()) {
                $leavePrevData = LeaveCompensation::find($request->commentId);
                $leaveView = LeaveCompensation::find($request->commentId);
                $leaveData = new LeaveCompensationComment;
                $leaveData->leave_compensation_id = $request->commentId;
                $leaveData->request_from = $leaveView->userLeave->id;
                $leaveData->review_by = Auth::user()->id;
                if($request->dataAction == 'approve_leave_compensation'){
                    $status = "approved";
                } elseif($request->dataAction == 'cancel_leave_compensation') {
                    $status = "cancelled";
                } else{
                    $status = "rejected";
                }
                // dump($leaveView);
                $leaveData->status = $status;
                if (Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {
                    if($status == 'approved'){
                        $leaveDataStored['approved_by'] = Auth::user()->id;
                    } else if($status == 'rejected'){
                        $leaveDataStored['rejected_by'] = Auth::user()->id;
                    } else{
                        $leaveDataStored['approved_by'] = NULL;
                    }

                    $leaveDataStored['status'] = $status;
                    $leaveView->update($leaveDataStored);
                    $leaveStatus = $status;

                    $current_year = date("Y");
                    $past_year = date("Y",strtotime("-1 year"));
                    $next_year = date("Y",strtotime("+1 year"));

                    $from_date = $past_year."-04-01";
                    $to_date = $current_year."-03-31";

                    $current_date_timestamp = strtotime(date('Y-m-d'));
                    $last_date_timestamp = strtotime($to_date);
                    $financial_dates = Helper::getFinancialYearDates();

                    if($current_date_timestamp > $last_date_timestamp){
                        $from_date = $current_year."-04-01";
                        $to_date = $next_year."-03-31";
                    }
                    $userLeaves = LeaveAllocation::where('allocated_year', Helper::getFinancialStartYearFromDate(today()))->where('user_id',$leaveView->userLeave->id)
                      ->whereBetween('updated_at',[$from_date,$to_date])->first();

                    if($leaveStatus == 'approved'){
                        if($userLeaves){
                            $joiningDate = Carbon::parse($leaveView->userTeam->joining_date);
                            $afterJoinDate = Carbon::parse($joiningDate)->addMonths(3);
                            $userLeaves->total_leave = $userLeaves->total_leave  + $leaveView->duration;
                            $userLeaves->compensation_leaves = $userLeaves->compensation_leaves + $leaveView->duration;
                            $userLeaves->save();
                        }
                    } elseif($leaveStatus = 'cancelled' || $leaveStatus = 'rejected'){
                        if($userLeaves && $leavePrevData->status == "approved"){
                            $userLeaves->total_leave = $userLeaves->total_leave  - $leaveView->duration;
                            $userLeaves->compensation_leaves = $userLeaves->compensation_leaves - $leaveView->duration;
                            $userLeaves->save();
                        }
                    }
                }
                else{
                    $leaveStatus = 'pending';
                }
                $leaveData->comments = $request->comments;
                $leaveData->save();

                $request_to = explode(',',$leaveView->request_to);
                $userDetails = User::whereIn('id', $request_to)->pluck('email', 'id');
                $cc_users = config('constant.default_users_email');
                if(!empty($userDetails)) {
                    $cc_users = array_values(array_unique(array_merge($userDetails->toArray(), $cc_users)));
                }


                if (Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {
                    if ($status == "approved") {
                        ApproveLeaveCompensationRequest::dispatch($leaveView, $leaveData, $cc_users);
                    } elseif($status == "cancelled") {
                        CancelLeaveCompensationRequest::dispatch($leaveView, $leaveData, $cc_users);
                    } else {
                        RejectLeaveCompensationRequest::dispatch($leaveView, $leaveData, $cc_users);
                    }
                }

                $leaveComments = LeaveCompensationComment::where('leave_compensation_id', $leaveData->leave_compensation_id)->with('reviewUser')->orderBy('id', 'Desc')->get();
                $returnHTML = view("leaves.partials.comments", compact(['leaveComments', 'leaveView']))->render();
                $data['html'] = $returnHTML;
                $data['status'] = $leaveStatus;
                return response()->json(['message' => trans('messages.MSG_ON_MODAL',['name' => 'Leave Compensation']). ucfirst($status), 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function viewAllLeaveCompensation($id)
    {
        try {
            $leaveView = LeaveCompensation::find($id);
            $leaveComments = LeaveCompensationComment::where('leave_compensation_id',$id)->with('reviewUser')->orderBy('id','Desc')->get();
            $request_to = explode(',', $leaveView->request_to);

            $leaveUser = User::withBlocked()->where('id',$leaveView->request_from)->with('userRole')->first();
            $leaves = LeaveAllocation::where('allocated_year', Helper::getFinancialStartYearFromDate(today()))->where('user_id',$leaveView->request_from)->first();
            $user_id = $leaveView->request_from;

            return view('leaves.common.view-compensation-all')->with(['leaves' => $leaves,'leaveView' => $leaveView, 'requestUsers' => $request_to, 'leaveComments' => $leaveComments,'leaveUser' => $leaveUser,'user_id'=> $user_id]);
        } catch (EXception $e) {
            Log::error("erorr",[$e]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function syncLeaveStatics(Request $request)
    {
        if($request->filled('user_id')) {
            $fYear = currentFinStartYear();
            if(!$request->filled('fyear')) {
                $financialYear = explode('-',$request->fyear);
                $fYear = $financialYear[0] ?? currentFinStartYear();
            }

            $user = User::with(['officialUser', 'allocatedLeaves'])->find($request->user_id);
            // Get the leave allocation value for the user for the requested year
            $leave_allocation_entry = $user->allocatedLeaves;
            // Check the joining data for user
            $joining_date = $user->officialUser->joining_date;
            $allocated_leaves = 0;
            if(!empty($joining_date)) {
                $financial_dates = Helper::getFinancialYearDates();
                $joining_date = Carbon::parse($joining_date);
                if($joining_date->lessThan($financial_dates['start_date'])) {
                    $joining_date = $financial_dates['start_date'];
                }
                $allocated_leaves = ((float) config('constant.leaves.allocated_leave_in_financial_year'));
                if($joining_date->diffInMonths(now()) < 12) {
                    $allocated_leaves = Helper::getAllocatedLeaves($user, $user->officialUser['joining_date']);
                }
            }
            // Get the total approved leave for the requested year
            $user->load('approvedLeaves');
            $current_year_approved_leave = (!empty($user->approvedLeaves)) ? $user->approvedLeaves->sum('duration') : 0;


            // Get the leave compensations for the requested year
            $user->load('approvedLeaveCompensation');
            $current_year_approved_leave_compensation = (!empty($user->approvedLeaveCompensation)) ? $user->approvedLeaveCompensation->sum('duration') : 0;


            // calculate the exceed leave for the user
            $leave_allocation_entry->allocated_leave = $allocated_leaves;
            $leave_allocation_entry->used_leave = $current_year_approved_leave;
            $leave_allocation_entry->compensation_leaves = $current_year_approved_leave_compensation;
            $total_leaves = $allocated_leaves + $current_year_approved_leave_compensation; // Total leave = allocated + compensate (approved only)
            $leave_allocation_entry->total_leave = $total_leaves;
            $leave_allocation_entry->pending_leave = $total_leaves - $current_year_approved_leave;
            $leave_allocation_entry->remaining_leave = $total_leaves - $current_year_approved_leave;
            $exceed_leaves = $current_year_approved_leave - $total_leaves;
            $leave_allocation_entry->exceed_leave = ($exceed_leaves > 0) ? $exceed_leaves : 0;
            $leave_allocation_entry->save();
            return response()->json(['message' => "$user->full_name's leave statistics updated", 'action_title' => "Leave Statistic"], 200);
        }
        return response()->json(['error' => "User not found", 'action_title' => "Leave Statistic"], 422);
    }

    public function showLeaveComments(Request $request, $leaveId)
    {
        $leaveEntry = Leave::with('comments')->findOrFail($leaveId);
        return view('leaves.common.view_comments')->with(['leave' => $leaveEntry]);
    }
}
