<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Inexture Solution LLP</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <style type="text/css">
        body {
            font-size: 14px;
            line-height: 1;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            font-family: 'system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans","Liberation Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji" ';
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            background-color: #ecedf1;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .info-table {
            border-collapse: collapse;
        }

        .info-table tr td:first-child::after {
            content: ":";
            position: absolute;
            font-size: 20px;
            left: 145px;
        }

        .info-table tr td {
            border-bottom: 1px solid #d5d9ed;
            padding: 5px 0;
            font-family: Arial, Helvetica, sans-serif !important;
        }


        .info-table tr:last-child td {
            border-bottom: none
        }
    </style>
</head>

<body yahoo="fix" style="background-color:#fff; margin: 0; padding: 0; line-height:1;color:#073e72;-webkit-text-size-adjust: 100%;" id="bodyTable">
    <table cellspacing="0" align="center" width="100%">
        <tbody>
            <!----Body---->
            <tr>
                <td>
                    <table class="inner-table" align="center" width="100%" style="background:#ffffff; width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <table style="width: 100%; margin: 10px 0;">
                                        <tr>
                                            <td style="text-align: center; padding: 10px 0px; background-color:#556ee6; margin-bottom:20px;">
                                                <img src="https://pms-staging.inexture.com/images/logo.png" alt="Logo" style="width: 125;" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px 10px; background-color:#9facec33;">
                                    <h2 style="font-size: 16px; color: #556ee6;">Employee information</h2>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="width: 100%; margin: 10px 0;">
                                        <tbody>
                                            <tr>
                                                <td style="width: 70px; height: 70px;">
                                                    <div>

                                                        @if (!empty($profile_image) && file_exists(public_path('storage/upload/user/images/'.$profile_image))) <img style="width: 100%; height:70px; border-radius: 50%; border: 3px solid #556ee6;" src="{{asset('storage/upload/user/images')}}/{{$profile_image}}" />
                                                        @else
                                                        <div style="width: 70px; height: 70px; text-align:center; background-color: #9facec33; color: #556ee6; border-radius: 50%; font-size:25px; padding:10px; line-height:55px;">{{ $profile_picture }}</div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td style="padding-left: 20px;">
                                                    <h3 style="margin: 5px 0px; color:#000">{{ $full_name }}</h3>
                                                    <span> {{ ($official_user) ? (($official_user['user_designation']) ? $official_user['user_designation']['name'] : '') : ''}}</span>
                                                </td>
                                            </tr>
                                        </tbody>

                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="info-table" style="margin-top: 10px;  font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px; width: 100%;">
                                        <tbody>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px; font-family:Arial, Helvetica, sans-serif;">
                                                    Name</td>
                                                <td style="padding-left: 5px;">{{ ($full_name) ? $full_name : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Personal Email</td>
                                                <td style="padding-left: 5px;">{{ ($personal_email) ? $personal_email : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Contact No.</td>
                                                <td style="padding-left: 5px;">{{ ($phone_number) ? $phone_number : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Gender</td>
                                                <td style="padding-left: 5px;">{{ ($gender) ? ucfirst($gender) : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Birth Date</td>
                                                <td style="padding-left: 5px;">{{ ($birth_date) ? $birth_date : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Address</td>
                                                <td style="padding-left: 5px;">{{ ($full_address) ? $full_address : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Blood Group</td>
                                                <td style="padding-left: 5px;">{{ ($blood_group) ? $blood_group : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Joining Date</td>
                                                <td style="padding-left: 5px;">{{ ($official_user) ? $official_user['joining_date'] : '-'}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px 10px; background-color:#9facec33; margin-top: 15px;">
                                    <h2 style="font-size: 16px; color: #556ee6;">Company information</h2>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="info-table" style="margin-top: 10px;  font-size: 14px; color: #707070; line-height: 25px; padding-bottom: 10px; width: 100%;">
                                        <tbody>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px; font-family:Arial, Helvetica, sans-serif;">
                                                    Employee Code</td>
                                                <td style="padding-left: 5px;">{{ ($official_user) ? $official_user['emp_code'] : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Total Experience</td>
                                                <td style="padding-left: 5px;">{{ ($official_user) ? (($official_user['experience'] > 1 ) ? $official_user['experience'] .' Years' : $official_user['experience'] .' Year') : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Designation</td>
                                                <td style="padding-left: 5px;">{{ ($official_user) ? (($official_user['user_designation']) ? $official_user['user_designation']['name'] : '-') : '-'}}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Department</td>
                                                <td style="padding-left: 5px;">{{ ($official_user) ? (($official_user['user_department']) ? $official_user['user_department']['department'] : '-') : '-'}}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Technology</td>
                                                <td style="padding-left: 5px;">{{ ($technologies) ? implode(', ',$technologies) : '-'}}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Skype Id</td>
                                                <td style="padding-left: 5px;">{{ ($official_user) ? $official_user['skype_id'] : '-' }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Inexture Email Id</td>
                                                <td style="padding-left: 5px;">{{ $email }}</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                                <td style="width:150px; font-weight: 600; vertical-align: text-top; padding-left: 5px;">
                                                    Inexture Gmail Id</td>
                                                <td style="padding-left: 5px;">{{ ($official_user) ? $official_user['company_gmail_id'] : '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
