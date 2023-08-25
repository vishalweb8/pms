<?php

namespace App\Http\Controllers\Interview;

use App\DataTables\InterviewDetailDatatable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InterviewDetail;
use App\Models\User;
use Exception;
use Illuminate\Validation\Rule;

class InterviewdetailController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|interview-detail.create'])->only(['create']);
        $this->middleware(['role_or_permission:Super Admin|interview-detail.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|interview-detail.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|interview-detail.destroy'])->only(['destroy']);
        view()->share('module_title', 'interview-detail');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InterviewDetailDataTable $datatable)
    {
        return $datatable->render('interview.interview_detail.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId = User::get()->pluck('first_name', 'id');
        return view('interview.interview_detail.create',compact('userId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'name' => 'required',
            'email'  => 'required|email',
            'phone' => 'required|digits:10',
            'resume' => 'required|mimes:doc,docx,pdf|max:10240',
        ],[
            'resume.max' => "You can upload maximum 10MB file/document"
        ]);
        try {
            $interviewDetail = new InterviewDetail();
            $interviewDetail->name = $request->get('name') ?? null;
            $interviewDetail->email = $request->get('email') ?? null;
            $interviewDetail->phone = $request->get('phone') ?? null;
            $interviewDetail->total_experience = $request->get('total_experience') ?? null;
            $interviewDetail->current_ctc = $request->get('current_ctc') ?? null;
            $interviewDetail->expected_ctc = $request->get('expected_ctc') ?? null;
            $interviewDetail->notice_period = $request->get('notice_period') ?? null;
            $interviewDetail->current_organization = $request->get('current_organization') ?? null;
            $interviewDetail->location = $request->get('location') ?? null;
            $interviewDetail->reference_id = $request->get('reference_id') ?? null;
            if($request->file()){
                $fileName = time().'_'.$request->resume->getClientOriginalName();
                $filePath = $request->file('resume')->storeAs('uploads/interviewDetails/', $fileName, 'public');
                $interviewDetail->resume = '/storage/' . $filePath;
            }
            $interviewDetail->source_by = $request->get('source_by') ?? null;
            $interviewDetail->remark = $request->get('remark') ?? null;
            $interviewDetail->status = ($request->filled('status') ? 1 : ($request->filled('status') ? 2 : 3));
            $interviewDetail->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'InterviewDetail'])], 200);
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
    public function edit(Request $request, InterviewDetail $interviewDetail)
    {
        $userId = User::get()->pluck('first_name', 'id');
        return view('interview.interview_detail.edit', compact('interviewDetail','userId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Interviewdetail $interviewDetail)
    {
        $this->validate($request , [
            'name' => 'required',
            'email'  => 'required|email',
            'phone' => 'required|digits:10',
            'resume' => 'mimes:doc,docx,pdf',
        ]);
        try {
            $interviewDetail->name = $request->get('name') ?? null;
            $interviewDetail->email = $request->get('email') ?? null;
            $interviewDetail->phone = $request->get('phone') ?? null;
            $interviewDetail->total_experience = $request->get('total_experience') ?? null;
            $interviewDetail->current_ctc = $request->get('current_ctc') ?? null;
            $interviewDetail->expected_ctc = $request->get('expected_ctc') ?? null;
            $interviewDetail->notice_period = $request->get('notice_period') ?? null;
            $interviewDetail->current_organization = $request->get('current_organization') ?? null;
            $interviewDetail->location = $request->get('location') ?? null;
            $interviewDetail->reference_id = $request->get('reference_id') ?? null;
            if(count($request->file()) > 0){
                $fileName = time().'_'.$request->resume->getClientOriginalName();
                $filePath = $request->file('resume')->storeAs('uploads/interviewDetails/', $fileName, 'public');
                $interviewDetail->resume = '/storage/' . $filePath;
            }else{
                $resume = InterviewDetail::whereId($request->interviewDetailId)->first();
                $interviewDetail->resume = $resume->resume;
            }
            $interviewDetail->source_by = $request->get('source_by') ?? null;
            $interviewDetail->remark = $request->get('remark') ?? null;
            $interviewDetail->status = ($request->filled('status') ? 1 : ($request->filled('status') ? 2 : 3));
            $interviewDetail->save();
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'InterviewDetail'])], 200);
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
            $interviewDetail = InterviewDetail::where('id', '=', $id)->first();
            if ($interviewDetail) {
                $interviewDetail->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'InterviewDetail'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
