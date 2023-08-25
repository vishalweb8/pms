<?php

namespace App\Http\Controllers\SuperAdmin;

use App\DataTables\PaymentHistoryDataTable;
use App\Http\Controllers\commonController;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentHistoryRequest;
use App\Models\Currency;
use App\Models\PaymentHistory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use AmrShawky\LaravelCurrency\Facade\Currency as CurrencyConverter;
use App\DataTables\CompanyRevenueDataTable;
use App\Jobs\CalculateExpectedAmt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PaymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaymentHistoryDataTable $dataTable)
    {
        return $dataTable->render('super-projects.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $paymentHistory = PaymentHistory::where('project_id',$request->project_id)->first();
        $fields = $this->getFields($request->project_id);
        $fields['paymentHistory'] = $paymentHistory;
        return view('super-projects.payment-history.create',$fields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentHistoryRequest $request)
    {
        try {
            $data = $request->except(['file_url']);
            if($request->hasFile('file_url')) {
                $data['invoice_url'] = $request->file_url->store('invoice','public_uploads');
            }
            $data = $this->setAmountInDoller($data);
            $paymentHistory = PaymentHistory::create($data);
            CalculateExpectedAmt::dispatch($paymentHistory->project_id, $paymentHistory->id);
            $msg = trans('messages.MSG_SUCCESS',['name' => 'Payment History']);
            return redirect()->route('super-admin-project-activity',$request->project_id)->with('message', $msg);
        } catch (\Throwable $th) {
            Log::error("Getting error while creating payment history:- ".$th);
            return redirect()->route('super-admin-project-activity',$request->project_id)->withErrors($th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentHistory  $paymentHistory
     * @return \Illuminate\Http\Response
     */
    public function edit($projectId, PaymentHistory $paymentHistory)
    {
        $fields = $this->getFields($projectId);
        $fields['paymentHistory'] = $paymentHistory;
        return view('super-projects.payment-history.edit',$fields);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentHistory  $paymentHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $projectId, PaymentHistory $paymentHistory)
    {
       try {
            $data = $request->except(['file_url']);

            if($request->hasFile('file_url')) {
                $data['invoice_url'] = $request->file_url->store('invoice','public_uploads');
                \Storage::disk('public_uploads')->delete($paymentHistory->invoice_url);
            }
            $data = $this->setAmountInDoller($data);
            $paymentHistory->update($data);

            $msg = trans('messages.MSG_UPDATE',['name' => 'Payment History']);
            return redirect()->route('super-admin-project-activity',$request->project_id)->with('message', $msg);
        } catch (\Throwable $th) {
            Log::error("Getting error while updating payment history:- ".$th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentHistory  $paymentHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, PaymentHistory $paymentHistory)
    {
        try {
            $paymentHistory->delete();
            Session::flash('success', trans('messages.MSG_DELETE',['name' => 'Payment History']));
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            Log::error("Getting error while deleting payment history:- ".$th);
            return response()->json(['status' => 'error','message' => $th->getMessage()], 400);
        }
    }
    
    /**
     * for get fields for options
     *
     * @return void
     */
    public function getFields($projectId)
    {
        $clients = (new commonController)->getClients();
        $currencies = (new commonController)->getCurrencyName();
        $project = Project::findOrFail($projectId);
        $fields = [
            'clients' => $clients,
            'currencies' => $currencies,
            'project' => $project,
        ];

        return $fields;
    }

        
    /**
     * for convert amount in doller(other key) from payment currency
     *
     * @param  mixed $data
     * @return array
     */
    public function setAmountInDoller($data)
    {
        try {
            $amount = $data['amount'];
            $currencyId = $data['currency_id'];
            if(!empty($amount) && $currencyId) {
                $currency = Currency::find($currencyId);
                $amountInDoller = CurrencyConverter::convert()
                ->from($currency->code)
                ->to('USD')
                ->amount($amount)
                ->get();      
                $data['amount_in_doller'] = $amountInDoller;
            }
        } catch (\Throwable $th) {
            Log::error("Getting error while converting amount in doller:- ".$th);
        }
        return $data;        
    }
    
    /**
     * created listing of company revenue
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function companyRevenue(CompanyRevenueDataTable $dataTable)
    {
        view()->share('module_title', 'Company Revenue');
        return $dataTable->render('super-projects.payment-history.company-revenue');
    }
    
    /**
     * for show expected revenue of next three months
     *
     * @param  mixed $request
     * @return void
     */
    public function expectedRevenue(Request $request)
    {
        view()->share('module_title', 'Expected Revenue ($)');
        $projects = Project::has('resources')
            ->with('resources.currency','resources.paymentType')
            ->wherehas('projectStatus', function($q) {
                $q->where('name','not like', 'Closed');
            })
            //->whereDate('start_date','<=',today())->whereDate('end_date','>=',today())
            ->orderBy('id','desc')->get();
        info("projects count:- ".$projects->count());
        foreach($projects as $project) {
            $totalAmt = 0;
            foreach($project->resources as $resource) {
                $amount = calculateMonthlyBilling($resource->paymentType, $resource->amount);
                $currencyCode = $resource->currency->code ?? null;
                if(!empty($amount) && $currencyCode && $currencyCode != 'USD') {
                    
                    $seconds = 36000; // 10 hours
                    $cacheName = $currencyCode.'_'.$amount;
                    $amount = Cache::remember($cacheName, $seconds, function () use ($currencyCode,$amount) {
                        return CurrencyConverter::convert()
                            ->from($currencyCode)
                            ->to('USD')
                            ->amount($amount)
                            ->get();
                    });
                }
                $totalAmt += $amount;
            }
            $project->expected_amount = $totalAmt;
            info("Calculated expected amount (".$totalAmt. ") for project id:- ".$project->id);
        }

        return view('super-projects.payment-history.expected-revenue',compact('projects'));
    }
    
    /**
     * for show actual revenue of last three months
     *
     * @param  mixed $request
     * @return void
     */
    public function actualRevenue(Request $request)
    {
        view()->share('module_title', 'Actual Revenue ($)');
        $last2month = now()->startOfMonth()->subMonth(2);
        $projects = PaymentHistory::select('payment_histories.*',DB::raw('SUM(amount_in_doller) as actual_revenue'),DB::raw("(DATE_FORMAT(created_at, '%m-%Y')) as month_year"))
        ->whereDate('invoice_date','>=',$last2month)
        ->has('project')->with('project')->groupBy('project_id','month_year')->orderBy('project_id','desc')->get()->groupBy('project_id');
        
        //dd($projects);
        return view('super-projects.payment-history.actual-revenue',compact('projects'));
    }
    
    /**
     * for show bar chart for actual and expected revenue
     *
     * @return void
     */
    public function actualVsExpected(Request $request)
    {
        view()->share('module_title', 'Actual v/s Expected Revenue');
        $projects = $actualRevenue = $expectedRevenue = $projectsFilters = [];
        try {
            if(!empty($request->custom_filter)) {
                if($request->custom_filter == 'date_range' && !empty($request->start_date)) {
                    $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
                    $endDate = Carbon::parse($request->end_date)->format('Y-m-d');
                } else {
                    if($request->custom_filter == 'current_year') {
                        $finYear = currentFinStartYear();
                        $startDate = Carbon::createFromFormat('Y-m-d', $finYear.'-04-01')->format('Y-m-d');
                    } else {
                        $m = ($request->custom_filter == 'last_6_month') ? 6 : 3;
                        $startDate = today()->subMonths($m)->format('Y-m-d');
                    }
                    $endDate = today()->format('Y-m-d');
                }
            } else {
                $year = $request->input('year',date('Y'));
                $month = $request->input('month',date('m'));
            }
            $history = PaymentHistory::select(
                    'payment_histories.*',
                    DB::raw('ROUND(SUM(amount_in_doller),2) as actual_revenue'),
                    DB::raw('ROUND(expected_amount,2) as expected_amount')
                )
                ->when($request->project, function ($q) use($request) {
                    return $q->where('project_id',$request->project);
                });
                if(!empty($request->custom_filter)) {
                    $history->whereBetween('invoice_date',[$startDate, $endDate]);
                } else {
                    $history->whereYear('invoice_date',$year)
                    ->whereMonth('invoice_date',$month);
                }
            $history = $history->has('project')
                ->with('project')
                ->groupBy('project_id')
                ->get();
            $projects = $history->pluck('project.name')->toArray();
            $actualRevenue = $history->pluck('actual_revenue')->toArray();
            $expectedRevenue = $history->pluck('expected_amount')->toArray();
            if($request->ajax()) {
                $data = [
                    'projects' => $projects,
                    'actualRevenue' => $actualRevenue,
                    'expectedRevenue' => $expectedRevenue,
                ];
                return response()->json(['status' => !!$projects,'data' => $data], 200);
            }

            $projectsFilters = Project::select('id','name','project_code')->get();
        } catch (\Throwable $th) {
            Log::error("Getting error while actualVsExpected:- ".$th);
        }
        return view('super-projects.payment-history.actual-vs-expected',compact('projects','actualRevenue','expectedRevenue','projectsFilters'));
    }
}
