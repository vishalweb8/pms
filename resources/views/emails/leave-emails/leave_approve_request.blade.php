@extends('emails.common.email_app')
@section('content')
<tr>
    <td>
        <table class="section" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="background:#ffffff;">
            <tbody>
            <!--leave--approve--content-->
                <tr>
                    <td style="text-align: center; padding: 30px 0px;"><img style="width: 45%;" alt="" src="{{ $message->embed(public_path('/images/leave-approve.png')) }}"></td>
                </tr>
                <tr>
                    <td>
                        <h2 style="text-align: center; color: #374A5E;">Leave Request Approved</h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h4 style="padding: 0px 20px; color: #374A5E;">Hello {{ $leaveDetails->userLeave->full_name ? ucwords($leaveDetails->userLeave->full_name) : '-' }},</h4>
                        <p style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;"> Your leave request has been Approved. </p>
                        <table style="padding: 0px 20px; font-size: 14px; color: #bb9e9e; line-height: 25px; padding-bottom: 10px; width: 100%;">
                            <tbody>
                                <tr style="background-color: #f1f1f1;">
                                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">From Date</td>
                                    <td style="padding-left: 5px;">{{ $leaveDetails->start_date ? (\Carbon\Carbon::parse($leaveDetails->start_date)->isoFormat('dddd, D MMM, YYYY')) : '-' }}</td>
                                </tr>
                                <tr style="background-color: #f1f1f1;">
                                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> To Date</td>
                                    <td style="padding-left: 5px;">{{ $leaveDetails->end_date ? (\Carbon\Carbon::parse($leaveDetails->end_date)->isoFormat('dddd, D MMM, YYYY')) : '-' }}</td>
                                </tr>
                                <tr style="background-color: #f1f1f1;">
                                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> Duration</td>
                                    <td style="padding-left: 5px;">{{ ($leaveDetails->duration) ? $leaveDetails->duration . (($leaveDetails->duration == 1) ? ' Day' : ' Days') : '-' }}</td>
                                </tr>
                                <tr style="background-color: #f1f1f1;">
                                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> Approved by</td>
                                    <td style="padding-left: 5px;">{{ $leave_comment->reviewUser->full_name ? ucwords($leave_comment->reviewUser->full_name) : '-' }} </td>
                                </tr>
                                <tr style="background-color: #f1f1f1;">
                                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> Approved Comments</td>
                                    <td style="padding-left: 5px;">{!! $leave_comment->comments ?? '-' !!} </td>
                                </tr>
                                <tr style="background-color: #f1f1f1;">
                                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">Leave Type</td>
                                    <td style="padding-left: 5px;">
                                        @if($leaveDetails->type == 'full')
                                            <span>Full Day</span>
                                        @else
                                            <span>Half Day </span>
                                            @if($leaveDetails->halfday_status == 'firsthalf')
                                                <span>(First Half)</span>
                                            @else
                                                <span>(Second Half)</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background-color: #f1f1f1;">
                                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">Reason</td>
                                    <td style="padding-left: 5px;">{{ $leaveDetails->reason ? $leaveDetails->reason : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection
