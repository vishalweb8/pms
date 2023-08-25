@extends('emails.common.email_app')
@section('content')
<!--WFH--request--rejected-->
<tr>
    <td style="text-align: center; padding: 30px 0px;">
        <img style="width: 50%;" alt="" src="{{ $message->embed(public_path('/images/wfh-reject.png')) }}">
    </td>
</tr>
<tr>
    <td>
        <h2 style="text-align: center; color: #374A5E;">Work From Home Rejected</h2>
    </td>
</tr>
<tr>
    <td>
        <h4 style="padding: 0px 20px; color: #374A5E;">Hello {{ $wfhDetails->userWfh->full_name ? ucwords($wfhDetails->userWfh->full_name) : '-' }},</h4>
        <p style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;">
                            Your work from home request rejected by {{ Auth::user()->full_name ? ucwords(Auth::user()->full_name) : '-' }}. </p>
        <p style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;">
            We have received your request for {{ $wfhDetails->reason ? $wfhDetails->reason : '-'  }} from {{ $wfhDetails->start_date ? (\Carbon\Carbon::parse($wfhDetails->start_date)->isoFormat('DD MMM YYYY')) : '-' }}, to {{ $wfhDetails->end_date ? (\Carbon\Carbon::parse($wfhDetails->end_date)->isoFormat('D MMM YYYY')) : '-' }}, {!! $comments ? $comments : '-' !!}.
        </p>
    </td>
</tr>
<tr>
    <td>
        <div style="padding: 20px; line-height: 1.3; color: #707070;">
            <p>Regards,<br>Inexture Solutions LLP</p>
        </div>
    </td>
</tr>
@endsection
