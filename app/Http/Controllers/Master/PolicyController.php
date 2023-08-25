<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Policy;
use Exception;
use App\DataTables\PolicyDataTable;
use Yajra\DataTables\Facades\DataTables;


class PolicyController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|policy.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|policy.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|policy.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|policy.destroy'])->only(['destroy']);
        view()->share('module_title', 'policy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PolicyDataTable $dataTable)
    {
        return $dataTable->render('master.policy.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.policy.create');
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
            'title' => 'required|unique:policies',
            'file_url' => 'required|mimes:doc,docx,pdf|max:10240',
        ], [
            'file_url.max' => 'The file url must not be greater than 10MB.'
        ]);

        try{
            $policy = new Policy();
            $policy->title = $request->get('title') ?? null;
            $policy->status = $request->get('status') ? 1 : 0;

            if ($request->hasFile('file_url')) {
                if ($policy->file_url) {
                    \Storage::delete('public/upload/policy/' . $policy->file_url);
                }
                $file = $request->file('file_url');
                $fileName = $file->getClientOriginalName();
                $request->file_url->move(public_path('storage/upload/policy/'), $fileName);
                $policy->file_url = $fileName;
            }
            $policy->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Policy'])], 200);
        } catch (Exception $e){
            return redirect()->route('policy.index')->with('error', $e->getMessage());
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
    public function edit(Request $request, Policy $policy)
    {
        return view('master.policy.edit', compact('policy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Policy $policy)
    {
        $this->validate($request, [
            'title' => 'required|unique:policies,title,'.$request->policyId,
            'file_url' => 'mimes:doc,docx,pdf|max:10240',
        ], [
            'file_url.max' => 'The file url must not be greater than 10MB.'
        ]);

        try{
            $policy->title = $request->get('title') ?? null;
            $policy->status = $request->filled('status') ? 1 : 0;

            if ($request->hasFile('file_url')) {
                if ($policy->file_url) {
                    \Storage::delete('public/upload/policy/' . $policy->file_url);
                }
                $file = $request->file('file_url');
                $fileName = $file->getClientOriginalName();
                $request->file_url->move(public_path('storage/upload/policy/'), $fileName);
                $policy->file_url = $fileName;
            }

            // if(count($request->file()) > 0){
            //     $fileName = time().'_'.$request->file_url->getClientOriginalName();
            //     $filePath = $request->file('file_url')->storeAs('uploads/policies/', $fileName, 'public');
            //     $policy->file_url = '/storage/' . $filePath;
            // }else{
            //     $fileUrl = Policy::whereId($request->policyId)->first();
            //     $policy->file_url = $fileUrl->file_url;
            // }
            $policy->save();
            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Policy'])], 200);
        }
        catch(Exception $e){
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
            $policy = Policy::where('id', '=', $id)->first();
            if ($policy) {
                $policy->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Policy'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
