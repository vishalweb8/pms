<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManageSodEodDate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $user_id;
    public $date;

    /**
     * Undocumented function
     *
     * @param [string] $type= [add|remove]
     * @param [string|integer] $user_id
     * @param [date] $date [format="d-m-Y" or "Y-m-d"]
     * @date 08-02-2022 
     * @return void
     */
    public function __construct($type, $user_id, $date)
    {
        $this->type = $type;
        $this->user_id = $user_id;
        $this->date = $date;
    }

}
