<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\commonController;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\LeaveAllocation;
use App\Models\Role;
use App\Models\TeamLeaderMember;
use App\Models\User;
use App\Models\Technology;
use App\Models\UserOfficialDetail;
use App\Models\UserBank;
use App\Models\UserDesignation;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserFamily;
use Carbon\Carbon;
use Exception;
use Storage;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PDF;

class UsersController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|user.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|user.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|user.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|user.destroy'])->only(['destroy']);
        view()->share('module_title', 'Employee');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDataTable $dataTable)
    {
        $teams = (new commonController)->getTeams();
        return $dataTable->render('users.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $createForm = 1;
        $employeeRoles = (new commonController)->getEmployeeRoles();
        $countries = (new commonController)->getCountries();
        $states[''] = ' Select State';
        $states_list = (new commonController)->fetchState(['country_id' => array_search('India', $countries)]);
        $states += $states_list;
        $cities[''] = ' Select City';
        $city_list = (new commonController)->fetchCity(['state_id' => array_search('Gujarat', $states)]);
        $cities += $city_list;
        $teams = (new commonController)->getTeams();
        $userDesignations = (new commonController)->getUserDesignation();
        $departments = (new commonController)->getDepartments();
        $reportings = (new commonController)->getUsers();
        $adminReporting = User::where('role_id',1)->pluck('id')->toArray();
        $hrReporting = User::where('email','hrinexture@gmail.com')->pluck('id')->toArray();
        $defaultReportings = array_merge($adminReporting, $hrReporting);
        $technologies = (new commonController)->getAllTechnology();
        $members = (new commonController)->getUsers(Auth::user()->id,true);
        $mentors = (new commonController)->getUsers(Auth::user()->id);
        unset($reportings['']);
        $userEducationDetails = [];
        $userExperienceDetails = [];
        $userFamilyDetails = [];
        $selectedMentors = [];
        $selectedMembers = [];

        return view('users.create',compact('createForm','employeeRoles','userEducationDetails','userExperienceDetails','userFamilyDetails', 'countries', 'states','cities', 'teams', 'userDesignations', 'departments', 'technologies', 'reportings','defaultReportings','mentors','selectedMentors','selectedMembers','members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePersonalDetail(Request $request)
    {
        $userId = ($request->id) ? $request->id :  $request->user_id;
        // $passwordValid = ($userId) ? 'nullable' : 'required';
        $messages = [
            "designation_id.required" => "Please select Designation.",
            "role_id.required" => "Please select Role.",
            "temp_address1.required" => "The temporary address line1 field is required.",
            "temp_contry.required" => "Please select temporary country.",
            "temp_state.required" => "Please select temporary state.",
            "temp_city.required" => "Please select temporary city.",
            "address1.required" => "The permanent address line1 field is required.",
            "contry.required" => "Please select permanent temporary country.",
            "state.required" => "Please select permanent state.",
            "city.required" => "Please select permanent city.",
            "file_url.max" => "The file url must not be greater than 10MB.",
            "user_name.regex" => "The format for username is not valid, use format like(abc.xyz12)."
        ];

        $this->validate($request, [
            "first_name" => "required|max:191",
            "last_name"  => "required|max:191",
        //     "gender"  => "required",
            "user_name" => "required|max:191|unique:users,user_name," . $userId,
        //     "designation_id" => "required",
        //     "role_id" => "required",
            "email" => 'required|email|unique:users,email,' . $userId,
        //     "personal_email" => 'required|email|unique:users,personal_email,' . $userId,
        //     "password" => $passwordValid.'|min:8|max:191|regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/',
        //     "birth_date" => "required|before:today",
        //     "wedding_anniversary" => "nullable|before:today",
        //     "blood_group" => "required",
        //     "marital_status" => "required",
            "file_url" => "nullable|mimes:jpeg,png,jpg,jfif|max:".config('constant.maxProfileImageSize'),
        //     "phone_number" => "required|numeric|gt:0|digits:10",
        //     "emergency_number" => "required|numeric|gt:0|digits:10",
        //     "temp_address1" => "required",
        //     "temp_contry" => "required",
        //     "temp_state" => "required",
        //     "temp_city" => "required",
        //     "address1" => "required",
        //     "contry" => "required",
        //     "state" => "required",
        //     "city" => "required",
        ], $messages);

        try {
            $isManagement = Auth::user()->isManagement();
            if ($userId) {
                $users = User::select();
                if ($isManagement) {
                    $users = User::withBlocked();
                }
                $users = $users->find($userId);
            } else {
                $users = new User();
            }
            $users->first_name = $request->get('first_name') ?? null;
            $users->last_name = $request->get('last_name') ?? null;
            $users->full_name = $request->get('first_name')." ".$request->get('last_name');
            $users->gender = $request->get('gender') ?? null;
            $users->user_name = $request->get('user_name') ?? null;
            if($isManagement) {
                $users->role_id = $request->get('role_id') ?? null;
                $users->email = $request->get('email') ?? null;
            }
            $users->personal_email = $request->get('personal_email') ?? null;

            // Original condition
            // if($request->get('password') != null){
            //     $users->password = bcrypt($request->get('password')) ?? null;
            // }

            // If password is null at the time of edit then Inexture@123
            if($request->get('password') != null){
                $users->password = bcrypt($request->get('password')) ?? 'null';
            }
            if (($users->password == null) && ($request->get('password') == null)) {
                $users->password = '$2y$10$43j2qjcEAzfwNfCxGn2pDudjV4yVn/TtiwgmjHjspuyJ07gsiBLvK';
            }

            $users->birth_date = $request->get('birth_date') ?? null;
            $users->wedding_anniversary = $request->get('wedding_anniversary') ?? null;
            $users->blood_group = $request->get('blood_group') ?? null;
            $users->marital_status = $request->get('marital_status') ?? null;
            $users->phone_number = $request->get('phone_number') ?? null;
            $users->emergency_number = $request->get('emergency_number') ?? null;
            $users->temp_address1 = $request->get('temp_address1') ?? null;
            $users->temp_address2 = $request->get('temp_address2') ?? null;
            $users->temp_zipcode = $request->get('temp_zipcode') ?? null;
            $users->temp_contry = $request->get('temp_contry') ?? null;
            $users->temp_state = $request->get('temp_state') ?? null;
            $users->temp_city = $request->get('temp_city') ?? null;
            $users->address1 = $request->get('address1') ?? null;
            $users->address2 = $request->get('address2') ?? null;
            $users->zipcode = $request->get('zipcode') ?? null;
            $users->contry = $request->get('contry') ?? null;
            $users->state = $request->get('state') ?? null;
            $users->city = $request->get('city') ?? null;

            if ($request->hasFile('file_url')) {
                if ($users->profile_image) {
                    Storage::delete('public/upload/user/images/' . $users->profile_image);
                }
                $file = $request->file('file_url');
                $extension = explode('.', $file->getClientOriginalName())[1];
                $fileName = $request->get('user_name') . '_' . time() . '.' . $extension;
                $request->file_url->move(public_path('storage/upload/user/images/'), $fileName);
                $users->profile_image = $fileName;
            }
            $users->save();
            if($isManagement) {
                $users->syncRoles([$request->get('role_id')]);
            }
            $getRoleCode = UserDesignation::find($request->get('designation_id'));

            $checkEmpCode = UserOfficialDetail::where('user_id',$users->id)->select('emp_code')->first();
            $checkUserOfficial = UserOfficialDetail::where('user_id', $users->id)->first();
            // if($checkEmpCode == null || $checkEmpCode->emp_code == ''){      // Uncomment this line when start auto generated emp_code feature
            if($checkUserOfficial == null){                                     // Remove/comment this line when start auto generated emp_code feature

                $lastEmpCode = Helper::getNextAutoCode(new UserOfficialDetail, 'emp_code');
                $currentEmpCode = 'INX' .$getRoleCode->designation_code . ($lastEmpCode);
                $employeeCode = $currentEmpCode;
                $userOfficial = new UserOfficialDetail();

            } else {
                $userOfficial = UserOfficialDetail::where('user_id', $users->id)->first();
                if($request->createForm == '1'){
                    $preFix = explode('INX', $checkEmpCode->emp_code);
                    $numberCode = preg_replace('/[^0-9]/', '', $preFix[1]);
                    $roleCode = preg_replace('/[^A-Z]+/', '', $preFix[1]);
                    if($roleCode != $getRoleCode->designation_code){
                        $employeeCode = 'INX' . $getRoleCode->designation_code . ($numberCode);
                    } else {
                        $employeeCode = ($checkEmpCode) ? $checkEmpCode->emp_code : $checkEmpCode;
                    }
                } else {
                    $employeeCode = ($checkEmpCode) ? $checkEmpCode->emp_code : $checkEmpCode;
                }
            }
            // $userOfficial['emp_code'] = $employeeCode;
            if($isManagement) {
                $userOfficial['designation_id'] = $request->get('designation_id');
            }
            $userOfficial['user_id'] = $users->id;
            $userOfficial->save();

            $data['users'] = $users;
            $data['empCode'] = $employeeCode;
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'User personal details']),'data' => $data ], 200);
        } catch (\Exception $e) {
            Log::error('getting error while saving personal detail:- '.$e);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function storeOfficialDetail(Request $request)
    {
        $messages = [
            "team_id.required" => "Please select Team.",
            // "team_leader_id.required" => "Please select Team Leader.",
            "department_id.required" => "Please select Department.",
            "technologies_ids.required" => "Please select Technologies.",
            "reporting_ids.required" => "Please select Reporting persons.",
        ];
        // $userId = ($request->user_id) ? $request->user_id :  $request->id;

        $this->validate($request, [
        //     "emp_code" => "required|unique:user_official_details,emp_code," . $userId.",user_id",
        //     "emp_code" => "required",
        //     "experience" => "required",
            // "joining_date" => "required|before:today",
        //     "confirmation_date" => "required",
        //     "team_id" => "required",
        //     // "team_leader_id" => "required",
        //     "offered_ctc" => "required|numeric",
        //     "current_ctc" => "required|numeric",
        //     "department_id" => "required",
        //     "skype_id" => "required|email",
        //     // "company_email_id" => "required|email",
        //     "company_gmail_id" => "required|email",
        //     "company_gitlab_id" => "nullable|email",
        //     "company_github_id" => "nullable|email",
        //     "technologies_ids" => "required",
        //     "reporting_ids" => "required",
        ], $messages);

        try {
            $input = $request->except('_token');
            $technologies = $input['technologies_ids'] ?? null;
            $input['technologies_ids'] = ($technologies) ? implode(',', $technologies) : null;

            $reportings = $input['reporting_ids'] ?? null;
            $input['reporting_ids'] = ($reportings) ? implode(',', $input['reporting_ids']) : null;

            if ($input['user_id']) {
                $officialDetail = UserOfficialDetail::where('user_id', '=', $input['user_id'])->first();
            } else {
                $officialDetail = new UserOfficialDetail;
            }
            if (Auth::user()->isManagement()) {
                $officialDetail->emp_code = $input['emp_code'] ? $input['emp_code'] : null;
                //$officialDetail->experience = $input['experience'] ? $input['experience'] : null;
                $officialDetail->joining_date = $input['joining_date'] ? $input['joining_date'] : null;
                $officialDetail->resigned_date = !empty($input['resigned_date']) ? $input['resigned_date'] : null;
                $officialDetail->task_entry_date = $input['task_entry_date'] ? $input['task_entry_date'] : null;
                $officialDetail->confirmation_date = $input['confirmation_date'] ? $input['confirmation_date'] : null;
                $officialDetail->team_id = $input['team_id'] ? $input['team_id'] : null;
                $officialDetail->offered_ctc = $input['offered_ctc'] ? $input['offered_ctc'] : null;
                $officialDetail->current_ctc = $input['current_ctc'] ? $input['current_ctc'] : null;
                $officialDetail->department_id = $input['department_id'] ? $input['department_id'] : null;
                $officialDetail->reporting_ids = $input['reporting_ids'] ? $input['reporting_ids'] : null;
            }
            $officialDetail->skype_id = $input['skype_id'] ? $input['skype_id'] : null;
            $officialDetail->company_email_id = $input['company_email_id'] ? $input['company_email_id'] : null;
            $officialDetail->company_gmail_id = $input['company_gmail_id'] ? $input['company_gmail_id'] : null;
            $officialDetail->company_gitlab_id = $input['company_gitlab_id'] ? $input['company_gitlab_id'] : null;
            $officialDetail->company_github_id = $input['company_github_id'] ? $input['company_github_id'] : null;
            $officialDetail->technologies_ids = $input['technologies_ids'] ? $input['technologies_ids'] : null;

            $is_join_date_changed = $officialDetail->isDirty('joining_date'); // Check if the joining date is

            $officialDetail->save();
            $user = User::find($input['user_id']);
            // check the leave allocation entry is there for the current year
            if ($input['user_id'] && $officialDetail->joining_date && $is_join_date_changed) {
                $join_date = Carbon::parse($officialDetail->joining_date);
                $afterJoinDate = Carbon::parse($join_date)->addMonths(3);

                $financial_dates = Helper::getFinancialYearDates();

                // Check the joining_date is in current_financial year
                if($afterJoinDate->greaterThan($financial_dates['start_date'])) {
                    if($afterJoinDate->year == $financial_dates['start_date']->year) {
                        $year = $financial_dates['start_date']->year;
                    }elseif($afterJoinDate->month <= 3){
                        /* Check the joining_date is in current_financial year */
                        $year = $financial_dates['start_date']->year;
                    }else {
                        /* Check the joining_date is in add financial year */
                        $year = $financial_dates['start_date']->addYear()->year;
                    }
                    $leaveAllocation = LeaveAllocation::where('user_id', '=', $input['user_id'])->firstOrNew([
                        'user_id' => $input['user_id'],
                        'allocated_year' => $year,
                    ]);
                    $leaves = Helper::getAllocatedLeaves($user, $input['joining_date'], false);
                    $leaveAllocation->total_leave = $leaves;
                    $leaveAllocation->allocated_leave = $leaves;
                    $leaveAllocation->used_leave = $leaveAllocation->used_leave ?? 0;
                    $leaveAllocation->pending_leave = $leaveAllocation->pending_leave ?? 0;
                    $leaveAllocation->remaining_leave = $leaveAllocation->remaining_leave ?? 0;
                    $leaveAllocation->exceed_leave = $leaveAllocation->exceed_leave ?? 0;
                    $leaveAllocation->save();
                    if($year != $financial_dates['start_date']->year) {
                        $addCurrentYearEntry = true;
                    }
                }else {
                    // If there is no entry for the current year then add the default entry
                    $addCurrentYearEntry = true;
                }
            }
            if(isset($addCurrentYearEntry) && !empty($addCurrentYearEntry)) {
                LeaveAllocation::firstOrCreate(
                    ['user_id' => $input['user_id'],
                    'allocated_year' => $financial_dates['start_date']->year],
                    [
                        'total_leave' => config('constant.leaves.allocated_leave_in_financial_year'),
                        'allocated_leave' => config('constant.leaves.allocated_leave_in_financial_year'),
                        'used_leave' => 0,'pending_leave' => 0,'remaining_leave' => 0,'exceed_leave' => 0
                    ]
                );
            }

            if(!empty($user) && Auth::user()->isManagement()) {
                $user->sod_eod_enabled = (!empty($input['sod_eod_enabled']) && $input['sod_eod_enabled'] == "on") ? '1' : '0';
                $user->save();
                // if($user->hasRole('Team Leader')) {
                //     $user->teamLeaderMembers()->sync($request->tl_members);
                // } else {
                //     $user->teamLeaders()->sync($request->team_leaders);
                // }
                if($request->tl_members){
                    $user->teamLeaders()->sync($request->tl_members);
                }
                $user->teamLeaderMembers()->sync($request->team_leaders);
            }
            // UserOfficialDetail::updateOrCreate(['user_id' => $input['user_id']], ['reporting_ids' => $input['reporting_ids']], ['team_id' => $teamId], $input);

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'User official details'])], 200);
        } catch (Exception $e) {
            Log::error("getting error while storing official detail:- ".$e);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function storeEducationDetail(Request $request)
    {
        try {
            $input = $request->except('_token');

            UserEducation::where('user_id', $input['user_id'])->forceDelete();
            foreach($input['education_group'] as $key => $val) {
                if(array_filter($val) && !empty($val['qualification'])){
                    $val['user_id'] = $input['user_id'];
                    $val['deleted_at'] = null;
                    $where['id'] = null;
                    if(isset($val['id'])){
                        $where['id'] =  $val['id'];
                    }
                    $education = UserEducation::withTrashed()->updateOrCreate($where,$val);
                }
            }
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'User Education details'])], 200);
        } catch (Exception $e) {
            Log::error('Getting error while storing education details:- '.$e);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }



    public function storeExperienceDetail(Request $request)
    {
        try {
            $input = $request->except('_token');
            UserExperience::where('user_id', $input['user_id'])->forceDelete();
            foreach($input['experience_group'] as $key => $val) {
                if(array_filter($val) && !empty($val['previous_company'])){
                    $val['user_id'] = $input['user_id'];
                    if(isset($val['id'])){
                        UserExperience::updateOrCreate(['id' => $val['id']],$val);
                    }else{
                        UserExperience::updateOrCreate($val);
                    }

                }
            }
            $this->countAndUpdateExpYears($input['user_id']);
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'User Experience details'])], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * for count and update experience years in official tables
     *
     * @param  mixed $userId
     * @return void
     */
    public function countAndUpdateExpYears($userId)
    {
        try {
            $experiences = UserExperience::selectRaw("SUM(DATEDIFF(released_date, joined_date)+1) as days")->where('user_id',$userId)->first();
            $years = ($experiences && $experiences->days) ? round($experiences->days/365,2) : 0;
            $exp_year = intval($years);
            $exp_month = ($years - $exp_year);

            $exp_month = ($exp_month*12); // convert point value to actual month
            $years = $exp_year.".". intval($exp_month);
            UserOfficialDetail::where('user_id',$userId)->update(['experience' => $years]);
        } catch (\Throwable $th) {
            Log::error("Getting error while counting experience years:- ".$th);
        }
    }

    public function storeBankDetail(Request $request)
    {
        try {
            $input = $request->except('_token');
            UserBank::updateOrCreate(['user_id' => $input['user_id']],$input);
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'User Bank details'])], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function storeFamilyDetail(Request $request)
    {

            $messages = [
            "family_group.*.contact_number.numeric" => "Please Enter valid contact number.",
            "family_group.*.contact_number.digits" => "Please Enter 10 digit contact number.",
        ];

        $this->validate($request, [
            "family_group.*.contact_number" => "nullable|numeric|digits:10",
        ], $messages);
        try {
            $input = $request->except('_token');
            UserFamily::where('user_id', $input['user_id'])->forceDelete();
            foreach($input['family_group'] as $key => $val) {
                if(array_filter($val) && !empty($val['name'])){
                    $val['user_id'] = $input['user_id'];
                    $val['user_id'] = $input['user_id'];
                    if(isset($val['id'])){
                        UserFamily::updateOrCreate(['id' => $val['id']],$val);
                    }else{
                        UserFamily::updateOrCreate($val);
                    }
                }
            }
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'User Family details'])], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteEducationDetail($id)
    {
        $user = UserEducation::where('id',$id)->forceDelete();
    }

    public function deleteExperienceDetail($id)
    {
        $experience = UserExperience::findOrFail($id);
        $userId = $experience->user_id;
        $experience->forceDelete();
        $this->countAndUpdateExpYears($userId);
    }

    public function deleteFamilyDetail($id)
    {
        $user = UserFamily::where('id',$id)->forceDelete();
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
    public function edit($id)
    {
        $user = User::find($id);
        if (in_array(Auth::user()->userRole->code, ['ADMIN', 'PM', 'HR'])) {
            $user = User::withBlocked()->find($id);
        }
        $userOfficialDetails = $user->officialUser;
        $userBankDetails = $user->userBank;
        $userEducationDetails = $user->userEducation;
        $userExperienceDetails = $user->userExperience;
        $userFamilyDetails = $user->userFamily;
        $employeeRoles = (new commonController)->getEmployeeRoles();
        $countries = (new commonController)->getCountries();
        $states[''] = $tempStates[''] = ' Select State';
        $cities[''] = $tempCities[''] = ' Select City';
        if($user->contry){
            $states = (new commonController)->fetchState(['country_id' => $user->contry, 'from_controller' => true]);
        }

        if ($user->state) {
            $cities = (new commonController)->fetchCity(['state_id' => $user->state, 'from_controller' => true]);
        }
        if($user->temp_contry){
            $tempStates = (new commonController)->fetchState(['country_id' => $user->temp_contry, 'from_controller' => true]);
        }

        if ($user->temp_state) {
            $tempCities = (new commonController)->fetchCity(['state_id' => $user->temp_state, 'from_controller' => true]);
        }

        $teams = (new commonController)->getTeams();
        $userDesignations = (new commonController)->getUserDesignation();
        $departments = (new commonController)->getDepartments();
        $reportings = (new commonController)->getUsers($id);
        $members = (new commonController)->getUsers(Auth::user()->id,true);
        $mentors = (new commonController)->getUsers(Auth::user()->id);
        unset($reportings['']);
        $technologies = (new commonController)->getAllTechnology();
        $selectedTechnology = (isset($userOfficialDetails)) ? explode(',', $userOfficialDetails->technologies_ids) : null;
        $selectedReportings = (isset($userOfficialDetails)) ? explode(',', $userOfficialDetails->reporting_ids) : null;
        $adminReporting = User::where('role_id',1)->pluck('id')->toArray();
        $hrReporting = User::where('email','hrinexture@gmail.com')->pluck('id')->toArray();
        $defaultReportings = array_merge($adminReporting,$hrReporting);
        // $selectedReportings = $selectedReportings ? array_merge($defaultReportings,$selectedReportings) : null;
        $selectedMembers = TeamLeaderMember::where('user_id',$id)->pluck('member_id')->toArray();
        $selectedMentors = TeamLeaderMember::where('member_id',$id)->pluck('user_id')->toArray();
        return view('users.edit',compact('user','userOfficialDetails','userEducationDetails','userExperienceDetails','userFamilyDetails','userBankDetails','employeeRoles', 'countries', 'states','cities','tempStates','tempCities', 'teams', 'userDesignations', 'departments', 'technologies', 'reportings', 'selectedTechnology', 'selectedReportings','selectedMentors','mentors','selectedMembers','members'));
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
        //
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
            $user = User::find($id);
            $user->delete();
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'User'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function download($id)
    {
        try {
            $user = User::where('id', $id)->with('officialUser', 'officialUser.userDesignation', 'officialUser.userDepartment')->first();
            $technologyId = isset($user->officialUser) ? (!empty($user->officialUser->technologies_ids) ? explode(',', $user->officialUser->technologies_ids) : []) : [];
            $user->technologies = (new commonController)->getTechnology($technologyId);
            $pdf = PDF::loadView('users.user-detail-pdf', $user);
            return $pdf->download($user['first_name']. time() . '.pdf');

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * This function renders user profile view
     */
    public function userProfile()
    {
        view()->share('module_title', 'my profile');
        $user = Auth::user();
        $user->members = User::WhereHas('teamLeaders',function($q){
            $q->where('user_id',Auth::user()->id);
        })->get()->pluck('full_name','id')->toArray();
        $user->mentors = User::WhereHas('teamLeaderMembers',function($q){
            $q->where('member_id',Auth::user()->id);
        })->get()->pluck('full_name')->toArray();
        if($user->officialUser){
            $user->reportings = (new commonController)->getReportings(explode(',',$user->officialUser->reporting_ids));
            $user->technologies = (new commonController)->getTechnology(explode(',',$user->officialUser->technologies_ids));
        }
        return view('users.profile',compact('user'));
    }

    public function editOfficeDetail(Request $request)
    {
        $user = Auth::user();
        $userOffice = $user->officialUser;
        $teams = (new commonController)->getTeams();
        $departments = (new commonController)->getDepartments();
        $reportings = (new commonController)->getUsers(auth()->id());
        $technologies = (new commonController)->getAllTechnology();
        $selectedTechnology = (isset($userOffice)) ? explode(',', $userOffice->technologies_ids) : null;
        $selectedReportings = (isset($userOffice)) ? explode(',', $userOffice->reporting_ids) : null;
        $display = "Official";
        return view('users.my_profile_edit',compact('user','userOffice','display','teams','departments','reportings','technologies','selectedTechnology','selectedReportings'));
    }

    public function editUserBank(Request $request)
    {
        $userBankDetails = Auth::user()->userBank;
        $userBank = true;
        $display = "Bank";
        return view('users.my_profile_edit',compact('userBankDetails','userBank','display'));
    }

    public function editUserFamily(Request $request)
    {
        $userId = Auth::id();
        $userFamilyDetails = Auth::user()->userFamily;
        $userFamily = true;
        $display = "Family";
        return view('users.my_profile_edit',compact('userId','userFamilyDetails','userFamily','display'));
    }

    public function editUserExperience(Request $request)
    {
        $userId = Auth::id();
        $userExperienceDetails = Auth::user()->userExperience;
        $userDesignations = (new commonController)->getUserDesignation();
        $userExpr = true;
        $display = "Experience";
        return view('users.my_profile_edit',compact('userId','userExperienceDetails','userDesignations','userExpr','display'));
    }

    public function editUserEducation(Request $request)
    {
        $userId = Auth::id();
        $userEducationDetails = Auth::user()->userEducation;
        $userEdu = true;
        $display = "Education";
        return view('users.my_profile_edit',compact('userId','userEducationDetails','userEdu','display'));
    }

    public function editUserPersonal(Request $request)
    {
        $user = Auth::user();
        $userOfficialDetails = $user->officialUser;
        $userPer = true;
        $display = "Personal";
        $employeeRoles = (new commonController)->getEmployeeRoles();
        $countries = (new commonController)->getCountries();
        $states[''] = $tempStates[''] = ' Select State';
        $cities[''] = $tempCities[''] = ' Select City';
        if($user->contry){
            $states = (new commonController)->fetchState(['country_id' => $user->contry, 'from_controller' => true]);
        }

        if ($user->state) {
            $cities = (new commonController)->fetchCity(['state_id' => $user->state, 'from_controller' => true]);
        }
        if($user->temp_contry){
            $tempStates = (new commonController)->fetchState(['country_id' => $user->temp_contry, 'from_controller' => true]);
        }

        if ($user->temp_state) {
            $tempCities = (new commonController)->fetchCity(['state_id' => $user->temp_state, 'from_controller' => true]);
        }
        $teamLeaders = (new commonController)->getTeamLeader();
        $teams = (new commonController)->getTeams();
        $userDesignations = (new commonController)->getUserDesignation();
        $departments = (new commonController)->getDepartments();
        $reportings = (new commonController)->getUsers();
        $technologies = (new commonController)->getAllTechnology();
        $userEducationDetails = [];
        $userExperienceDetails = [];
        $userFamilyDetails = [];
        return view('users.my_profile_edit',compact('user','userPer','display','employeeRoles','userEducationDetails','userExperienceDetails','userFamilyDetails', 'countries', 'states','tempStates','tempCities','cities','teamLeaders', 'teams', 'userDesignations', 'departments', 'technologies', 'reportings','userOfficialDetails'));
    }

    public function profileUploadImage(Request $request)
    {
        $messages = [
            "userImage.required" => "Please upload profile image.",
            "userImage.extension" => "The profile image must be a file of type: png, jpg, jpeg.",
            "userImage.max" => "The profile image must not be greater than 10MB."
        ];
        $validator = Validator::make($request->all(),[
            'userImage' => 'required|mimes:png,jpg,jpeg|max:'.config('constant.maxProfileImageSize'),
        ],$messages);

        if($validator->fails()){
            return response()->json(['errors' => $validator->getMessageBag()->get('userImage')],422);
        }

        $users = User::where('id', '=', Auth::user()->id)->first();
        if ($request->hasFile('userImage')) {
            if ($users->profile_image) {
                Storage::delete('public/upload/user/images/profileimage/' . $users->profile_image);
            }
            $file = $request->file('userImage');
            $extension = explode('.', $file->getClientOriginalName())[1];
            $fileName = time() . '.' . $extension;
            $request->userImage->move(public_path('storage/upload/user/images/'), $fileName);
            $users->profile_image = $fileName;
        }
        $users->save();
        return response()->json(['success' => trans('messages.MSG_SUCCESS_UPLOAD',['name' => 'Profile Image'])], 200);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'old_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!\Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
                }],
            'new_password' => 'required|different:old_password|min:8|max:191|regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/',
            'confirm_password' => 'required|same:new_password',
        ]);
        try{

            $user->password = bcrypt($request->get('new_password'));
            $user->save();
            return response()->json(['success' => trans('messages.MSG_SUCCESS_CHANGED',['name' => 'Password'])], 200);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function statusUpdate($id)
    {
        try {

            $user = User::withBlocked()->find($id);
            $user->status = !$user->status;
            $user->save();
            $user_reporting_details = UserOfficialDetail::whereRaw("find_in_set('".$id."',user_official_details.reporting_ids)")->get(['id','reporting_ids']);
            if(!empty($user_reporting_details) && (!$user->status)){
                foreach($user_reporting_details as $key=>$value){
                     $reporting_ids = explode(",",$value->reporting_ids);
                     $block_id = array($id);
                     $result = array_diff($reporting_ids,$block_id);
                     $result = implode(",",$result);
                     UserOfficialDetail::where('id',$value->id)->update(['reporting_ids' => $result]);
                }
            }
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'User Status'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function removeImage(Request $request)
    {
        if($request->ajax()){
            $profilePicture = '';
            if($request->id){
                $userName = User::where('id',$request->id)->first();
                $profilePicture = $userName ? $userName->profile_picture : '';
                if ($profilePicture) {
                    Storage::delete('public/upload/user/images/'.$profilePicture);
                    \DB::table('users')->where('id',$request->id)->update([
                        'profile_image' => ""
                    ]);
                }else if($request->file_url){
                    $file = $request->file('file_url');
                    $extension = explode('.', $file->getClientOriginalName())[1];
                    $fileName = $request->get('user_name') . '_' . time() . '.' . $extension;
                    $request->file_url->move(public_path('storage/upload/user/images/'), $fileName);
                    \DB::table('users')->where('id',$request->id)->update([
                        'profile_image' => ""
                    ]);
                }
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Image']),'data' => $profilePicture], 200);
        }
    }

    /**
     * get team leader members and all members based on team
     *
     * @param  mixed $request
     * @return void
     */
    public function getTeamLeaderMembers(Request $request)
    {
        try {
            $selectedMembers = [];

            $members = User::select('id','first_name','last_name','full_name')
            ->where('id','!=',$request->user_id)
            // ->whereHas('roles', function ($query) {
            //     $query->where('code','!=' ,'TL');
            // })
            ->whereHas('officialUser', function ($query) use($request) {
                $query->where('team_id', $request->team_id);
            })->get();

            if(!empty($request->user_id)) {
                $selectedMentors = TeamLeaderMember::where('member_id',$request->user_id)->pluck('user_id');
            }
            if(!empty($request->user_id)) {
                $selectedMembers = TeamLeaderMember::where('user_id',$request->user_id)->pluck('member_id');
            }

            $data = [
                'members' => $members,
                'selected_members' => $selectedMembers,
                'selected_mentors' => $selectedMentors
            ];
            return response()->json(['status' => true,'data' => $data ], 200);
        } catch (\Throwable $th) {
            Log::error("Getting error while fetching team leader members:- ".$th);
            return response()->json(['status' => false,'message' => $th->getMessage()], 400);
        }
    }

    /**
     * for get known skills of employee
     *
     * @param  mixed $id
     * @return void
     */
    public function getKnownTechnology($id)
    {
        try {
            $user = User::select('id','first_name','last_name','full_name')->withBlocked()->find($id);
            $technologies = ($user && $user->officialUser) ? explode(',',$user->officialUser->technologies_ids) : [];
            $user->technologies = (new commonController)->getTechnology($technologies);
            return response()->json(['status' => true,'data' => $user], 200);
        } catch (\Throwable $th) {
            Log::error("Getting error while fetching known technology:- ".$th);
            return response()->json(['status' => false,'message' => $th->getMessage()], 400);
        }
    }

    /**
     * for get basic details of employee
     *
     * @param  mixed $request
     * @return void
     */
    public function getEmployeeInfo(Request $request)
    {
        try {
            $user = User::select('id','first_name','last_name','full_name','phone_number','email')->withBlocked()->findOrFail($request->id);
            $technologies = ($user && $user->officialUser) ? explode(',',$user->officialUser->technologies_ids) : [];
            $user->technologies = (new commonController)->getTechnology($technologies);
            $experience = userExperience($user->officialUser);
            info($experience);
            $user->expr = $experience;
            return response()->json(['status' => true,'data' => $user], 200);
        } catch (\Throwable $th) {
            Log::error("Getting error while fetching employee info:- ".$th);
            return response()->json(['status' => false,'message' => $th->getMessage()], 400);
        }
    }

}
