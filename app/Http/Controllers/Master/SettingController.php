<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        view()->share('module_title', 'setting');
    }

    public function index() {
        $settings = Setting::all();
        return view('master.settings.index',compact('settings'));
    }

    public function create()
    {
        return view('master.settings.create');
    }

    public function store(Request $request)
    {
        $input = $request->only('field_label','value');
        $this->validate($request , [
            'field_label' => 'required|unique:settings',
            'value' => 'required|numeric',
        ]);

        try{
            $input['field_name'] = strtolower(preg_replace('/\s+/', '_', $input['field_label']));
            Setting::updateOrCreate($input);

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Setting']),'status'=>'success'], 200);
        } catch (Exception $e){
            return redirect()->route('setting.index')->with('error', $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        $setting = Setting::find($request->id);
        return view('master.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $input = $request->only('field_label','value');
        $this->validate($request , [
            'field_label' => 'required|unique:settings,field_label,'.$request->settingId,
            'value' => 'required|numeric',
        ]);

        try{
            Setting::updateOrCreate(['id'=> $request->settingId],$input);
            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Setting']),'status'=>'success'], 200);
        } catch (Exception $e){
            return redirect()->route('setting.index')->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $setting = Setting::find($id)->delete();
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Setting'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
