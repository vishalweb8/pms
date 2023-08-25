<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLeaveRequestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $leaveDetails;
    public $send_to_users;
    public $action_url;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($leaveDetails, $send_to_users, $action_url='')
    {
        $this->leaveDetails = $leaveDetails;
        $this->send_to_users = $send_to_users;
        $this->action_url = $action_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $send_to_users = $this->send_to_users;
        $applied_person = $this->leaveDetails->userLeave;
        Mail::send('emails.leave-emails.leave_request', ['leaveDetails' => $this->leaveDetails, 'applied_person' => $applied_person, 'action_url' => $this->action_url], function ($message) use ($send_to_users, $applied_person) {
            $message->from(env('MAIL_USERNAME') ? (config('constant.default_users_email.admin') ? config('constant.default_users_email.admin') : 'admin@inexture.in') : 'admin@inexture.in', env('MAIL_FROM_NAME') ? (config('constant.default_users_name.admin') ? config('constant.default_users_name.admin') : 'INEXTURE PORTAL') : 'INEXTURE PORTAL');
            $message->to($send_to_users)
            ->subject('Leave Request From ' .ucwords($applied_person->full_name));
        });
    }
}
