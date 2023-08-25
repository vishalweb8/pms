@extends('emails.common.email_app')
@section('content')
<tr>
    <td>
        <table class="section" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="background:#ffffff;">
            <tbody>
            <!--leave--approve--content-->
                <tr>
                    <td>
                        <h2 style="text-align: center; color: #374A5E;">{{ $run_at->format('d-m-Y') }}: Defaulters List</h2>
                        <h4> This data was fetched at {{ $run_at->format('h:i A') }}.</h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="padding: 0px 20px; font-size: 14px; line-height: 25px; padding-bottom: 10px; width: 100%;">
                            <tbody>
                                {!! $response_html ?? '' !!}
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection
