<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ApprovelWFHRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $wfhDetails;
    public $cc_users;
    public $wfh_comment;

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
    public function __construct($wfhDetails, $wfh_comment, $cc_users)
    {
        $this->wfhDetails = $wfhDetails;
        $this->cc_users = $cc_users;
        $this->wfh_comment = $wfh_comment;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cc_users = $this->cc_users;
        $wfh_details = $this->wfhDetails;
        Mail::send('emails.wfh-emails.wfh_approve_request', ['wfhDetails' => $wfh_details,'comments' => $this->wfh_comment], function ($message) use ($wfh_details, $cc_users) {
            $message->from(env('MAIL_USERNAME') ? (config('constant.default_users_email.admin') ? config('constant.default_users_email.admin') : 'admin@inexture.in') : 'admin@inexture.in', env('MAIL_FROM_NAME') ? (config('constant.default_users_name.admin') ? config('constant.default_users_name.admin') : 'INEXTURE PORTAL') : 'INEXTURE PORTAL');
            $message->to($wfh_details->userWfh->email, $wfh_details->userWfh->full_name);
            if(!empty($cc_users)) {
                $message->cc($cc_users);
            }
            $message->subject('Work From Home Request Approved');
        });
    }
}
