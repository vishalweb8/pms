@extends('emails.common.email_app')
@section('content')

   <table class="section" border="0" cellpadding="0" cellspacing="0" align="center" width="600">
      <tbody>
         <!----Body---->
         <tr>
            <td>
               <table class="section" border="0" cellpadding="0" cellspacing="0" align="center" width="100%"
                  style="background:#ffffff;">
                  <tbody>
                     <tr>
                        <td style="text-align: center; padding: 30px 0px;"><img style="width: 35%;" alt=""
                              src="Forgot-Password.svg">
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <h2 style="text-align: center; color: #374A5E;">Forgot your password?</h2>
                        </td>
                     </tr>
                     <tr>
                        <td>

                           <h4 style="padding: 0px 20px; color: #374A5E;">Hello {{$user->first_name}} {{$user->last_name}},</h4>

                           <p
                              style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;">
                              We received your request to reset your password. If you didn't submit a request, you can
                              safely ignore this email.
                           </p>

                           <p
                              style="padding: 0px 20px; font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px;">
                              To reset password click on reset password button below.
                           </p>

                           <div style="text-align: center;">
                              <a href="{{$link}}" style="color: #ffffff;
                              background-color: #556ee6;
                              border-radius: 4px;
                              padding: 15px;
                              position: relative;
                              display: block;
                              width: 150px;
                              margin: 0 auto;
                              text-decoration: none;
                              font-weight: 600;
                              font-size: 16px;
                              text-transform: capitalize;">
                                 Reset Password
                              </a>
                           </div>
                        </td>
                        <tr>
                           <td>
                              <div style="padding: 20px; line-height: 1.3; color: #707070;">
                                 <p>Regards,<br>Inexture Solutions LLP
                                 </p>
                              </div>
                           </td>
                        </tr>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
@endsection
