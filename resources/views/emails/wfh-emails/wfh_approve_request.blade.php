@extends('emails.common.email_app')
@section('content')
<!--WFH--approve--content-->
<tr>
    <td style="text-align: center; padding: 30px 0px;">
        <img style="width: 50%;" alt="" src="{{ $message->embed(public_path('/images/wfh-approve.png')) }}">
    </td>
</tr>
<tr>
    <td>
        <h2 style="text-align: center; color: #374A5E;">Work From Home Request Approved </h2>
    </td>
</tr>
<tr>
    <td>
        <h4 style="padding: 0px 20px; color: #374A5E;">Hello {{ $wfhDetails->userWfh->full_name ? ucwords($wfhDetails->userWfh->full_name) : '-'  }},</h4>
        <p style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;">
            Your Work From Home request has been Approved.
        </p>
        <table style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px; width: 100%;">
            <tbody>
                <tr style="background-color: #f1f1f1;">
                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> From Date</td>
                    <td style="padding-left: 5px;">{{ $wfhDetails->start_date ? (\Carbon\Carbon::parse($wfhDetails->start_date)->isoFormat('dddd, D MMM, YYYY')) : '-'  }}</td>
                </tr>
                <tr style="background-color: #f1f1f1;">
                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> To Date</td>
                    <td style="padding-left: 5px;">{{ $wfhDetails->end_date ? (\Carbon\Carbon::parse($wfhDetails->end_date)->isoFormat('dddd, D MMM, YYYY')) : '-' }}</td>
                </tr>
                <tr style="background-color: #f1f1f1;">
                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> Duration</td>
                    <td style="padding-left: 5px;">{{ ($wfhDetails->duration) ? $wfhDetails->duration . (($wfhDetails->duration == 1) ? ' Day' : ' Days') : '-' }}</td>
                </tr>
                <tr style="background-color: #f1f1f1;">
                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> Approved by</td>
                    <td style="padding-left: 5px;">{{ Auth::user()->full_name ? ucwords(Auth::user()->full_name) : '-' }} </td>
                </tr>
                <tr style="background-color: #f1f1f1;">
                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> Approved Comments</td>
                    <td style="padding-left: 5px;">{!! $comments ? $comments : '-' !!} </td>
                </tr>
                <tr style="background-color: #f1f1f1;">
                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">WorkFromHome Type</td>
                    <td style="padding-left: 5px;">
                        @if($wfhDetails->wfh_type == 'full')
                            <span>Full Day</span>
                        @else
                            <span>Half Day </span>
                            @if($wfhDetails->halfday_status == 'firsthalf')
                                <span>(First Half)</span>
                            @else
                                <span>(Second Half)</span>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr style="background-color: #f1f1f1;">
                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">Reason</td>
                    <td style="padding-left: 5px;"> {{ $wfhDetails->reason ? $wfhDetails->reason : '-' }} </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection
