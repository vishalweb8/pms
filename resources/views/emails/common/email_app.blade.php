@include('emails.common.email_head-portion')
<body yahoo="fix" style="background-color:#ecedf1;  no-repeat center top; margin: 0; padding: 0; font-family: 'Open Sans', Arial, Helvetica, sans-serif;line-height:1;color:#073e72;-webkit-text-size-adjust: 100%;" id="bodyTable">
    <table class="section" border="0" cellpadding="0" cellspacing="0" align="center" width="600">
        <tbody>
            <!----Header---->
            @include('emails.common.email_header')
            <!----Body---->
            @yield('content')
            <!----Footer---->
            @include('emails.common.email_footer')
        </tbody>
    </table>
</body>
</html>
