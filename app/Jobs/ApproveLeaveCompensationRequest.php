<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ApproveLeaveCompensationRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $leaveDetails;
    public $cc_users;
    public $leave_comment;

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
    public function __construct($leaveDetails, $leave_comment, $cc_users)
    {
        $this->leaveDetails = $leaveDetails;
        $this->cc_users = $cc_users;
        $this->leave_comment = $leave_comment;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cc_users = $this->cc_users;
        $leave_details = $this->leaveDetails;
        Mail::send('emails.leave-compensation-emails.leave_compensation_approve_request', ['leaveDetails' => $leave_details,'leave_comment' => $this->leave_comment], function ($message) use ($leave_details, $cc_users) {
            $message->from(env('MAIL_USERNAME') ? (config('constant.default_users_email.admin') ? config('constant.default_users_email.admin') : 'admin@inexture.in') : 'admin@inexture.in', env('MAIL_FROM_NAME') ? (config('constant.default_users_name.admin') ? config('constant.default_users_name.admin') : 'INEXTURE PORTAL') : 'INEXTURE PORTAL');
            $message->to($leave_details->userLeave->email, $leave_details->userLeave->full_name);
            if(!empty($cc_users)) {
                $message->cc($cc_users);
            }
            $message->subject('Leave Compensation Request Approved');
        });
    }
}
