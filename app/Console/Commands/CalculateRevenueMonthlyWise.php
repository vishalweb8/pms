<?php

namespace App\Console\Commands;

use App\Models\Performer;
use App\Models\User;
use Illuminate\Console\Command;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\ProjectBillableResources;
use App\Models\ProjectTaskWorkLog;
use Illuminate\Support\Facades\Log;

class CalculateRevenueMonthlyWise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:revenue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate revenue of all employee monthly wise';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $startDate = today()->subMonth()->startOfMonth()->format('Y-m-d');
            $endDate = today()->subMonth()->endOfMonth()->format('Y-m-d');
            $workLogs = ProjectTaskWorkLog::select('user_id','project_tasks.project_id',\DB::raw('sum(log_time) as total_hours'))
                ->join('project_tasks', 'project_task_work_logs.task_id', '=', 'project_tasks.id')
                ->whereBetween('log_date',[$startDate,$endDate])
                ->groupBy('user_id','project_tasks.project_id')
                ->get();
            $projectIds = $workLogs->pluck('project_id')->unique()->toArray();
            $userIds = $workLogs->pluck('user_id')->unique()->toArray();
            $workLogs = $workLogs->groupBy('user_id');
            $resources = ProjectBillableResources::whereIn('project_id',$projectIds)->with('currency','paymentType')->get();

            $lastMonthYear = lastMonthYear();
            $users = User::whereDoesntHave('roles',function ($query) {
                $query->where('code', 'ADMIN');
            })->with('officialUser')->cursor();
            foreach($users as $user) {
                $ctcInr = $user->officialUser->current_ctc ?? 0;
                $ctc = Currency::convert()
                ->from('INR')
                ->to('USD')
                ->amount($ctcInr)
                ->get();
                $query = $data = [
                    'user_id' => $user->id,
                    'month_year' => $lastMonthYear
                ];
                $revenue = 0;
                if(in_array($user->id,$userIds)) {
                    $revenue = $this->calculateRevenue($workLogs[$user->id], $resources);
                }
                $data['revenue'] = $revenue;
                $data['expense'] = ($ctc && $ctc != 0) ? round($ctc/12,2) : 0;
                Performer::updateOrCreate($query, $data);
            }                         
            
        } catch (\Throwable $th) {
            Log::error("getting error while calculating revenue and expense of performer:- ".$th);
        }
    }
    
    /**
     * Calculate revenue based on total working hours
     *
     * @param  mixed $workLogs
     * @param  mixed $resources
     * @return double
     */
    public function calculateRevenue($workLogs, $resources)
    {
        $totalAmt = 0;
        try {
            foreach($workLogs as $workLog) {
                $resource = $resources->where('user_id',$workLog->user_id)->where('project_id',$workLog->project_id)->first();
                if($resource) {
                    $amount = calculateMonthlyBilling($resource->paymentType, $resource->amount);
                    $currencyCode = $resource->currency->code ?? null;
                    if(!empty($amount) && $currencyCode && $currencyCode != 'USD') {
                        $amount = Currency::convert()
                        ->from($currencyCode)
                        ->to('USD')
                        ->amount($amount)
                        ->get();
                    }
                    info('monthly amount (user id)---------- :- '.$workLog->user_id);
                    info($amount);
                    $totalAmt += ($amount * $workLog->total_hours)/176; // for calculate amount based on total working hours in a month
                }
            }
            return round($totalAmt,2);
        } catch (\Throwable $th) {
            Log::error("getting error while calculating revenue of performer:- ".$th);
            return $totalAmt;
        }
    }
}
