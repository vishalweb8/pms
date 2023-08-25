<?php

namespace App\Listeners;

use App\Events\ManageSodEodDate;
use App\Models\PendingSodEod;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;


class ManageSodEodDateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ManageSodEodDate  $event
     * @return void
     */
    public function handle(ManageSodEodDate $event)
    {
        if(!empty($event->date) && !empty($event->type) && !empty($event->user_id)) {
            $date = Carbon::parse($event->date)->format('Y-m-d');
            $user_id = $event->user_id;
            if($event->type == Str::lower('add')) {
                PendingSodEod::create(['user_id' => $user_id, 'date' => $date]);
            }elseif($event->type == Str::lower(('remove'))) {
                PendingSodEod::where('user_id', $user_id)->where('date', $date)->delete();
            }
        }
    }
}
