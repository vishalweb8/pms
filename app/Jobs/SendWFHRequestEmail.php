<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Auth;

class SendWFHRequestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $wfhDetails;
    public $send_to_users;


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
    public function __construct($wfhDetails, $send_to_users)
    {
        $this->wfhDetails = $wfhDetails;
        $this->send_to_users = $send_to_users;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $send_to_users = $this->send_to_users;
        $applied_person = $this->wfhDetails->userWfh;
        Mail::send('emails.wfh-emails.wfh_request', ['applied_person' => $applied_person, 'wfhDetails' => $this->wfhDetails], function ($message) use ($send_to_users,$applied_person) {
            $message->from(env('MAIL_USERNAME') ? (config('constant.default_users_email.admin') ? config('constant.default_users_email.admin') : 'admin@inexture.in') : 'admin@inexture.in', env('MAIL_FROM_NAME') ? (config('constant.default_users_name.admin') ? config('constant.default_users_name.admin') : 'INEXTURE PORTAL') : 'INEXTURE PORTAL');
            $message->to($send_to_users)
                ->subject('Work From Home Request From '.ucwords($applied_person->full_name));
        });
    }
}
