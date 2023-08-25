<?php

namespace App\Http\Controllers\WFH;

use App\DataTables\AllEmpWFHDatatable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\commonController;
use App\DataTables\WorkFromHomeDataTable;
use App\DataTables\TeamWfhDataTable;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WorkFromHome;
use App\Models\WfhComment;
use Exception;
use Validator;
use Auth;
use Mail;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\UserOfficialDetail;
use App\Jobs\SendWFHRequestEmail;
use App\Jobs\ApprovelWFHRequest;
use App\Jobs\RejectWFHRequest;
use App\Jobs\CancelWFHRequest;
use App\DataTables\AllWFHStatisticsDataTable;
use App\Models\TeamLeaderMember;
use Spatie\Permission\Exceptions\UnauthorizedException;

class WorkFromHomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|wfh.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|wfh.create'])->only(['create', 'saveWfhData']);
        $this->middleware(['role_or_permission:Super Admin|wfh.edit'])->only(['edit', 'saveWfhData']);
        $this->middleware(['role_or_permission:Super Admin|wfh.destroy'])->only(['cancelLeave']);
        $this->middleware(['role_or_permission:Super Admin|wfh.view'])->only(['viewWfh']);
        $this->middleware(['role_or_permission:Super Admin|wfhteam.list'])->only(['addWfhTeamView']);
        $this->middleware(['role_or_permission:Super Admin|wfhallemp.list|wfhteam.list'])->only(['viewTeamWfh']);
        $this->middleware(['role_or_permission:Super Admin|wfhallemp.list'])->only(['AllEmployeeWorkFromHome']);
        view()->share('module_title', 'Work From Home Request');
    }

    public function dashboard(WorkFromHomeDataTable $dataTable)
    {

        view()->share('module_title', 'my WFH requests');
        $wfhList = 1;
        return $dataTable->render('wfh.common.index',compact('wfhList'));
    }

    public function addWfhView(Request $request, $id = null)
    {
        try {
            // $requestUsers = (new commonController)->getUsers(Auth::user()->id);
            // // unset($requestUsers[""]);
            // $requestUsers = [];
            // $employee = UserOfficialDetail::where('user_id', Auth::user()->id)->first();
            // $reportingIds = $employee->reporting_ids ? explode(',', $employee->reporting_ids) : null;
            // if ($reportingIds != null) {
            //     foreach ($reportingIds as $reportingId) {
            //         $user = User::withBlocked()->where('id', $reportingId)->first();
            //         $requestUsers[$reportingId] = ($user->first_name ? $user->first_name : '')." ".($user->last_name ? $user->last_name : '');
            //     }
            // }
            // $adminReporter = User::withBlocked()->where('email', config('constant.default_users_email.admin'))->first();
            // $hrReporter = User::withBlocked()->where('email', config('constant.default_users_email.hr'))->first();

            // if ($adminReporter != null) {
            //     $requestUsers[$adminReporter->id] = isset($adminReporter) ? ($adminReporter->first_name.' '.$adminReporter->last_name) : config('constant.default_users_name.admin');
            // }
            // if ($hrReporter) {
            //     $requestUsers[$hrReporter->id] = isset($hrReporter) ? ($hrReporter->first_name.' '.$hrReporter->last_name) : config('constant.default_users_name.hr');
            // }
            $allUsers = User::all()->pluck('full_name','id')->toArray();

            if ($id != null) {
                $workFromHome = WorkFromHome::find($id);
                if(!Helper::hasAccess($workFromHome, 'user_id', [] )) {
                    $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
                    return view('errors.403')->with(['exception'=>$exception]);
                }
                $workFromHome->request_to = explode(',', $workFromHome->request_to);

                $requestUsers = Helper::fetchReportingIds($workFromHome->user_id);
                return view('wfh.common.edit', compact('allUsers'))->with(['workFromHome' => $workFromHome, 'requestUsers' => $requestUsers,'id' => $id]);
            } else {
                $requestUsers = Helper::fetchReportingIds(Auth::user()->id);
                return view('wfh.common.create', compact('allUsers'))->with(['requestUsers' => $requestUsers]);
            }
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function addWfhTeamView(Request $request, $id = null)
    {
        $teamWfh = 1;
        try {
            // $requestUsers = (new commonController)->getUsers(Auth::user()->id);
            $requestUsersTo =   (new commonController)->getUsers();
            $requestUsersFrom = (new commonController)->getUsers(Auth::user()->id);
            unset($requestUsersTo[""]);
            $allUsers = User::all()->pluck('full_name','id')->toArray();

            // unset($requestUsersFrom[""]);
            if ($id != null) {
                $workFromHome = WorkFromHome::find($id);
                if(!Helper::hasAccess($workFromHome, 'user_id', [] )) {
                    $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
                    return view('errors.403')->with(['exception'=>$exception]);
                }
                $workFromHome->request_to = explode(',', $workFromHome->request_to);

                return view('wfh.common.edit', compact('allUsers'))->with(['workFromHome' => $workFromHome, 'requestUsers' => $requestUsersTo, 'requestUsersFrom' => $requestUsersFrom , 'teamWfh' => $teamWfh,'id' => $id]);
            } else {
                return view('wfh.common.create', compact('allUsers'))->with(['requestUsers' => $requestUsersTo, 'requestUsersFrom' => $requestUsersFrom , 'teamWfh' => $teamWfh]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function saveWfhData(Request $request)
    {

        $userId = ($request->user_id) ? $request->user_id : Auth::user()->id;

        try {
            $validator = Validator::make($request->all(), [
                'request_to' => 'required_without:request_to1',
                'wfh_type' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'reason' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $startDateFinancialYear = Helper::getFinancialStartYearFromDate($request->start_date);//29-03-2022//2022
            $endDateFinancialYear = Helper::getFinancialStartYearFromDate($request->end_date);//05-04-2022//2023

            if($startDateFinancialYear != $endDateFinancialYear) {
                return redirect()->back()->with(['error' => trans('messages.ERR_FORBIDDEN_DATE',['name' => 'Work From Home'])]);
            }

            // check if wfh request already added in this date
            $wfhRequest = WorkFromHome::where('user_id', $userId)->select('start_date', 'end_date');

            if($request->id != null)
            {

                $wfhRequest = $wfhRequest->where('id','!=',$request->id);
            }

            $wfhRequest = $wfhRequest->whereIn('status',['approved','pending'])->get();

            $allWfhRequest = (new commonController)->checkPastRequest($wfhRequest);
            foreach ($allWfhRequest as $pastLeave) {
                if ($pastLeave == $request->start_date || $pastLeave == $request->end_date) {
                    return redirect()->back()->with(['error' => trans('messages.ERR_DUPLICATE_DATE',['name' => 'Work From Home'])]);
                }
            }

            if ($request->id) {
                $wfh = WorkFromHome::find($request->id);
            } else {
                $wfh = new WorkFromHome;
            }

            if(!empty($request->request_to1)){
                $requestTo = implode(',', $request->request_to1);
            }else{
                $requestTo = implode(',', $request->request_to);
            }
            if($request->id == NULL || empty($request->id)){
                $wfh->user_id = isset($request->user_id) ? $request->user_id : Auth::user()->id;
            }

            $wfh->request_to = $requestTo;
            $wfh->wfh_type = $request->wfh_type;
            $wfh->halfday_status = ($request->wfh_type != 'full') ? $request->halfday_status : null;
            $wfh->start_date = $request->start_date;
            $wfh->end_date = $request->end_date;
            $wfh->return_date = $request->return_date;
            $wfh->duration = $request->duration;
            $wfh->is_adhoc = ($request->is_adhoc == 'on') ? 1 : 0;
            $wfh->adhoc_status = ($wfh->is_adhoc == 1) ? $request->adhoc_status : null;
            $wfh->reason = $request->reason;
            $wfh->status = "pending";
            $wfh->created_by = Auth::user()->id;
            $wfh->save();

            $userDetails = User::whereIn('id', $request->request_to1 ? $request->request_to1 : $request->request_to)->pluck('email', 'id');
            $send_to_users = config('constant.default_users_email');
            $wfhDetails = WorkFromHome::with(['userWfh'])->where('id','=',$wfh->id)->first();
           if(!empty($userDetails)) {
                $send_to_users = array_values(array_unique(array_merge($userDetails->toArray(), $send_to_users)));
            }
           if(!empty($send_to_users)) {
                // $action_url = route('leave-team-view',$leave->id);
                 SendWFHRequestEmail::dispatch($wfhDetails, $send_to_users);

            }
            // foreach ($userDetails as $user) {
            //     Mail::send('emails.wfh-emails.wfh_request', ['user' => $user, 'wfhDetails' => $wfhDetails], function ($message) use ($user) {
            //         $message->from(env('MAIL_USERNAME') ? (config('constant.MAIL_USERNAME') ? config('constant.MAIL_USERNAME') : 'admin@inexture.in') : 'admin@inexture.in', env('MAIL_FROM_NAME') ? (config('constant.MAIL_FROM_NAME') ? config('constant.MAIL_FROM_NAME') : 'INEXTURE PORTAL') : 'INEXTURE PORTAL');
            //         $message->to($user->email, $user->full_name)
            //             ->subject('Work From Home Request From '.ucwords(Auth::user()->full_name));
            //     });
            // }
            if($request->id){
                $message = trans('messages.MSG_UPDATE',['name' => 'Work From Home']);
            }else {
                $message = trans('messages.MSG_SUCCESS',['name' => 'Work From Home']);
            }
            if($request->filled('_url')) {
                return redirect($request->input('_url'))->with('message', $message);
            }
            if(!isset($request->user_id)) {
                return redirect()->route('wfh-dashboard')->with('message', $message);
            } else {
                if($request->allempflagWFH == 1){
                    return redirect()->route('wfh-all-emp-index')->with('message', $message);
                }else{
                    return redirect()->route('wfh-team')->with('message', $message);
                }
            }

        } catch (Exception $e) {
           return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function viewWfh($id)
    {
        $requestUsers = (new commonController)->getUsers(Auth::user()->id);
        unset($requestUsers[""]);
        try {
            $wfhView = WorkFromHome::find($id);
            if(!Helper::hasAccess($wfhView, 'user_id', [] , $wfhView->request_to )) {
                $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
                return view('errors.403')->with(['exception'=>$exception]);
            }
            $wfhComments = WfhComment::where('wfh_id', $id)->with('reviewUser')->orderBy('id', 'Desc')->get();
            $request_to = explode(',', $wfhView->request_to);

            if (($key = array_search(Auth::user()->id, $request_to)) !== false) {
                 unset($request_to[$key]);
            }

            $requestArr = [];
            foreach ($request_to as $key => $value) {
                if(isset($requestUsers[$value])){
                    $requestArr[$value] = $requestUsers[$value];
                }
            }
            return view('wfh.common.view')->with(['wfhView' => $wfhView, 'requestUsers' => $requestArr, 'wfhComments' => $wfhComments]);
        } catch (EXception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function viewTeamWfh($id)
    {
        $requestUsers = (new commonController)->getUsers();
        unset($requestUsers[""]);
        try {
            $wfhView = WorkFromHome::find($id);
            if(!Helper::hasAccess($wfhView, 'user_id', [] , $wfhView->request_to )) {
                $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
                return view('errors.403')->with(['exception'=>$exception]);
            }
            $wfhComments = WfhComment::where('wfh_id', $id)->with('reviewUser')->orderBy('id', 'Desc')->get();
            $request_to = explode(',', $wfhView->request_to);
            $requestArr = [];
            foreach ($request_to as $key => $value) {
                if(isset($requestUsers[$value])){
                    $requestArr[$value] = $requestUsers[$value];
                }
            }
            $wfhUser = User::withBlocked()->where('id', $wfhView->user_id)->with('userRole')->first();
            return view('wfh.common.team_view')->with(['wfhUser' => $wfhUser, 'wfhView' => $wfhView, 'requestUsers' => $requestArr, 'wfhComments' => $wfhComments]);
        } catch (EXception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function teamWfh(TeamWfhDataTable $dataTable)
    {
        view()->share('module_title', 'WFH requests');
        $wfhList = 0;
        try {
            return $dataTable->render('wfh.common.team_index',compact('wfhList'));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function wfhRequest(Request $request)
    {
        try {
            if ($request->ajax()) {
                $wfhView = WorkFromHome::find($request->commentId);
                $wfhData = new WfhComment;
                $wfhData->wfh_id = $request->commentId;
                $wfhData->request_from = $wfhView->userWfh->id;
                $wfhData->review_by = Auth::user()->id;
                if ($request->dataAction == 'approve_wfh') {
                    $status = "approved";
                }elseif($request->dataAction == 'cancel_wfh') {
                    $status = "cancelled";
                } elseif($request->dataAction == 'add_comment') {
                    $status = "pending";
                } else{
                    $status = "rejected";
                }
                $wfhData->status = $status;
                if (Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {

                    if ($status == 'approved') {
                        $wfhDataStored['approved_by'] = Auth::user()->id;
                    } else if ($status == 'rejected') {
                        $wfhDataStored['rejected_by'] = Auth::user()->id;
                    }else{
                        $wfhDataStored['approved_by'] = NULL;
                    }

                    $wfhDataStored['status'] = $status;
                    $wfhView->update($wfhDataStored);
                    $WFHStatus = $status;
                }else{
                    $WFHStatus = 'pending';
                }
                $wfhData->comments = $request->comments;
                $wfhData->save();

                $wfhDetails = WorkFromHome::where('id',$request->commentId)->first();
                $request_to = explode(',',$wfhView->request_to);
                $userDetails = User::withBlocked()->whereIn('id', $request_to)->pluck('email', 'id');
                $cc_users = config('constant.default_users_email');
                if(!empty($userDetails)) {
                    $cc_users = array_values(array_unique(array_merge($userDetails->toArray(), $cc_users)));
                }

                if (Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {
                    if ($status == "approved") {
                        ApprovelWFHRequest::dispatch($wfhView, $wfhData, $cc_users);
                    } elseif($status == "cancelled") {
                        CancelWFHRequest::dispatch($wfhView, $wfhData, $cc_users);
                    } else {
                        RejectWFHRequest::dispatch($wfhView, $wfhData, $cc_users);
                    }
                }

                $wfhComments = WfhComment::where('wfh_id', $wfhData->wfh_id)->with('reviewUser')->orderBy('id', 'Desc')->get();
                $returnHTML = view("wfh.partials.comments", compact(['wfhComments', 'wfhView']))->render();
                $data['html'] = $returnHTML;
                $data['status'] = $WFHStatus;
                return response()->json(['message' => trans('messages.MSG_ON_MODAL',['name' => 'Work From Home']) . $status, 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function cancelWfh($id)
    {
        try {
            $wfh = WorkFromHome::where('id', '=', $id)->first();
            if ($wfh) {
                $wfh->update(['status' => "cancelled"]);
            }
            return response()->json(['message' => trans('messages.MSG_REQUEST_CANCELLED',['name' => 'Work From Home'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function AllEmployeeWorkFromHome(AllEmpWFHDatatable $dataTable)
    {
        view()->share('module_title', 'All Employee WFH requests');
        $wfhList = 1;
        $allUsers = User::pluck('first_name');
        $teams = (new commonController)->getTeams();
        $pendingWFH = WorkFromHome::where('status','=','pending')->count();
        $approvedWFH = WorkFromHome::where('status','=','approved')->count();
        $cancelledWFH = WorkFromHome::where('status','=','cancelled')->count();
        $rejectedWFH = WorkFromHome::where('status','=','rejected')->count();
        $wfhCountArray = array(
            ['name' => 'Pending WFH','count' => $pendingWFH ? $pendingWFH : 0],
            ['name' => 'Approved WFH','count' => $approvedWFH ? $approvedWFH : 0],
            ['name' => 'Cancelled WFH','count' => $cancelledWFH ? $cancelledWFH : 0],
            ['name' => 'Rejected WFH','count' => $rejectedWFH ? $rejectedWFH : 0],
        );
        return $dataTable->render('wfh.common.all_employee',compact('wfhList', 'allUsers', 'teams','wfhCountArray'));
    }

    public function wfhFetchRequestFrom(Request $request)
    {
        try {
            if ($request->ajax()) {
                $users = Helper::fetchReportingIds($request->request_from);
                return response()->json($users);
            }
        } catch (Exception $e) {

        }
    }

    public function allEmployeeWFHStatistics(AllWFHStatisticsDataTable $dataTable){
        if(!Helper::hasAnyPermission('wfhstatisticsallemp.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        view()->share('module_title', 'WFH Statistics');
        $allUsers = User::pluck('first_name','id');
        $financeYearData = Helper::getFinancialYearData(2021);
        try {
           return $dataTable->render('wfh.common.wfh_statistics',compact('financeYearData'));
        } catch (Exception $e) {
            Log::error("erorr", [$e]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function showComments(Request $request, $wfhId)
    {
        $wfhEntry = WorkFromHome::with('comments')->findOrFail($wfhId);
        return view('wfh.common.view_comments')->with(['wfh' => $wfhEntry]);
    }
}
