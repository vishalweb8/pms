<?php

namespace App\Jobs;

use AmrShawky\LaravelCurrency\Facade\Currency as CurrencyConverter;
use App\Models\PaymentHistory;
use App\Models\ProjectBillableResources;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CalculateExpectedAmt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $projectId;
    public $paymentHistoryId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($projectId, $paymentHistoryId)
    {
        $this->projectId = $projectId;
        $this->paymentHistoryId = $paymentHistoryId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $totalAmt = 0;
            $paymentHistory = PaymentHistory::where('project_id',$this->projectId)->where('id','<>',$this->paymentHistoryId)->first();
            if($paymentHistory && !empty($paymentHistory->expected_amount)) {
                $totalAmt = $paymentHistory->expected_amount;
            } else {
                $resources = ProjectBillableResources::where('project_id',$this->projectId)->with('currency','paymentType')->get();                
                foreach($resources as $resource) {
                    $amount = calculateMonthlyBilling($resource->paymentType, $resource->amount);
                    $currencyCode = $resource->currency->code ?? null;
                    if(!empty($amount) && $currencyCode && $currencyCode != 'USD') {
                        $amount = CurrencyConverter::convert()
                        ->from($currencyCode)
                        ->to('USD')
                        ->amount($amount)
                        ->get();
                    }
                    $totalAmt += $amount;
                }
                info("Calculated expected amount (".$totalAmt. ") for project id:- ".$this->projectId);
            }
            PaymentHistory::whereId($this->paymentHistoryId)->update(['expected_amount' => $totalAmt]);
        } catch (\Throwable $th) {
            Log::error("Getting error while calculating expected amount in doller:- ".$th);
        }
    }
}
