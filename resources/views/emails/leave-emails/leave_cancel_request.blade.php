@extends('emails.common.email_app')
@section('content')
<tr>
    <td>
        <table class="section" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="background:#ffffff;">
            <tbody>
                <!--leave--request--rejected-->
                <tr>
                    <td style="text-align: center; padding: 30px 0px;"><img style="width: 45%;" alt="" src="{{ $message->embed(public_path('/images/leave-reject.png')) }}"></td>
                </tr>
                <tr>
                    <td>
                        <h2 style="text-align: center; color: #374A5E;">Leave Cancelled</h2>
                    </td>
                </tr>
                <tr>
                    <td> <h4 style="padding: 0px 20px; color: #374A5E;">Hello {{ $leaveDetails->userLeave->full_name ? ucwords($leaveDetails->userLeave->full_name) : '-' }},</h4>
                           <p style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;">
                            Your leave request for {{ $leaveDetails->start_date ? (\Carbon\Carbon::parse($leaveDetails->start_date)->isoFormat('DD MMM YYYY')) : '-' }} to {{ $leaveDetails->end_date ? (\Carbon\Carbon::parse($leaveDetails->end_date)->isoFormat('DD MMM YYYY')) : '-' }} is cancelled by {{ $leave_comment->reviewUser->full_name ? ucwords($leave_comment->reviewUser->full_name) : '-' }}. </p>
                            <p style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;">
                            <b>Reason:</b> {!! $leave_comment->comments ?? '-' !!}.</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="padding: 20px; line-height: 1.3; color: #707070;">
                            <p>Regards,<br>Inexture Solutions LLP</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection
