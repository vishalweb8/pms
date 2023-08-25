<?php

namespace App\Http\Controllers\Master;

use App\DataTables\ClientDataTable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\commonController;
use App\Models\Client;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|client.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|client.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|client.edit'])->only(['edit', 'update']);
        $this->middleware(['role_or_permission:Super Admin|client.destroy'])->only(['destroy']);
        view()->share('module_title', 'clients');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClientDataTable $dataTable)
    {
        return $dataTable->render('master.client.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = $cities = [];
        $countries = (new commonController)->getCountries();
        return view('master.client.create',compact('states','cities','countries'));
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
            'first_name' => "required",
            'last_name' => "required",
            // 'first_name' => "required | regex:/^[a-zA-Z]+$/u",
            // 'last_name' => "required | regex:/^[a-zA-Z]+$/u",
            'email' => "required |unique:clients,email",
            // 'phone_number' => "nullable|gt:0",

        ]);
        try {

            $input = $request->except('_token');
            Client::create($input);

            return response()->json(['message' => trans('messages.MSG_SUCCESS',['name' => 'Client'])], 200);
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
    public function edit(Request $request, $id)
    {
        $client = Client::where('id',$id)->with('getProjectByClient')->first();
        $countries = $states = $cities = [];
        $countries = (new commonController)->getCountries();
        if($client->country_id) {
            $states = (new commonController)->fetchState(['country_id' => $client->country_id, 'from_controller' => true]);
            if ($client->state_id) {
                $cities = (new commonController)->fetchCity(['state_id' => $client->state_id, 'from_controller' => true]);
            }
        }
        return view('master.client.edit', compact('client','cities','states','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $this->validate($request, [
            'first_name' => "required ",
            'last_name' => "required ",
            // 'phone_number' => "nullable|numeric|gt:0",
            'email' => [
                'required',
                Rule::unique('clients')->ignore($client->id),
            ],

        ]);
        try {

            $input = $request->except('_token');
            Client::where('id',$client->id)->update($input);

            return response()->json(['message' => trans('messages.MSG_UPDATE',['name' => 'Client'])], 200);
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
            $client = Client::where('id', '=', $id)->first();
            if ($client) {
                $client->delete();
            }
            return response()->json(['message' => trans('messages.MSG_DELETE',['name' => 'Client'])], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
