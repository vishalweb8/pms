@extends('emails.common.email_app')
@section('content')
<tr>
    <td>
        <table class="section" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="background:#ffffff;">
            <tbody>
            <!--leave--request--content-->
                <tr>
                    <td style="text-align: center; padding: 30px 0px;">
                        <img style="width: 50%;" alt="image" src="{{ $message->embed(public_path('/images/wfh.png')) }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 style="text-align: center; color: #374A5E;">Work From Home Request</h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h4 style="padding: 0px 20px; color: #374A5E;">Hello all,</h4>
                        <p style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;">
                             I ( {{ $applied_person->full_name ? ucwords($applied_person->full_name) : '-' }} - {{ $applied_person->email ? $applied_person->email : '-' }} ) would like to request {{ ($wfhDetails->duration) ? $wfhDetails->duration . (($wfhDetails->duration == 1) ? ' work from home day' : ' work from home days') : '-' }} starting from {{ $wfhDetails->start_date ? $wfhDetails->start_date : '-' }} due to {{ $wfhDetails->reason ? $wfhDetails->reason : '' }}.
                        </p>
                        <table style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px; width: 100%;">
                            <tbody>
                                <tr style="background-color: #f1f1f1;">
                                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">From Date</td>
                                    <td style="padding-left: 5px;">{{ $wfhDetails->start_date ? (\Carbon\Carbon::parse($wfhDetails->start_date)->isoFormat('dddd, D MMM, YYYY')) : '-' }}</td>
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
                                    <td style="width: 120px; font-weight: 600; vertical-align: text-top; padding-left: 5px;"> Work From Home Type</td>
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
                            </tbody>
                        </table>
                          <p style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;">
                           You can Approve or Reject a Work from home request by clicking on the below link.</p>
                           <a style="padding: 0px 20px; font-size: 14px; line-height: 25px; padding-bottom: 10px;" href="{{ route('wfh-team-view',$wfhDetails->id) }}">Click here for approve or reject request</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection
