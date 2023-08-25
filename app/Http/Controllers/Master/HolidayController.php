<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Exception;
use App\DataTables\HolidayDataTable;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class HolidayController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|holiday.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|holiday.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|holiday.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|holiday.destroy'])->only(['destroy']);
        view()->share('module_title', 'holiday');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HolidayDataTable $dataTable)
    {
        return $dataTable->render('master.holiday.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.holiday.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['date'] = Carbon::parse($request->date)->format("Y-m-d");
        $this->validate($request , [
            'name' => ["required", Rule::unique("holidays")->where(function ($query) use ($request) {
                return $query->whereYear('date', Carbon::parse($request->date)->format("Y"));
            })->whereNull('deleted_at'),"regex:/^[a-zA-Z ]+$/"
            ],
            'date' => ["required", Rule::unique("holidays")->where(function ($query) use ($request){
                return $query->whereDate('date', $request->date);
            })->whereNull('deleted_at')]
        ]);

        try {
            $holiday = new Holiday();
            $holiday->name = $request->get('name') ?? null;
            $holiday->date = $request->get('date') ?? null;
            $holiday->save();

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Holiday'])], 200);
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->route('holiday.index')->with('error', $e->getMessage());
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
    public function edit(Request $request, Holiday $holiday)
    {
        return view('master.holiday.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request['date'] = Carbon::parse($request->date)->format("Y-m-d");
        $this->validate($request, [
            'name' => [
                "required", Rule::unique("holidays")->where(function ($query) use ($request) {
                    return $query->whereYear('date', Carbon::parse($request->date)->format("Y"));
                })->whereNull('deleted_at')->ignore($holiday->id),"regex:/^[a-zA-Z ]+$/"
            ],
            'date' => ["required", Rule::unique("holidays")->where(function ($query) use ($request){
                return $query->whereDate('date', $request->date);
            })->whereNull('deleted_at')->ignore($holiday->id)
            ]
        ]);
        try {
            $holiday->name = $request->get('name') ?? null;
            $holiday->date = $request->get('date') ?? null;
            $holiday->save();

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Holiday'])], 200);
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
        $holiday = Holiday::find($id);
        if($holiday){
            $holiday->delete();
            return response()->json(['success' => true, 'message' => trans('messages.MSG_DELETE',['name' => 'Holiday'])]);
        }else {
            return response()->json(['success' => true, 'message' => 'Holiday not found']);
        }
    }

    public function getAllHolidayList(Request $request)
    {
        try {
            if ($request->ajax()) {
                // dd("here");
                $allHoiliday = Holiday::pluck('date');
                // dd($allHoiliday);
                return response()->json(['allHoiliday' => $allHoiliday]);
            }
        } catch (Exception $e) {

        }
    }
}
