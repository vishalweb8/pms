<?php

return [
    'maxFileSize' => '10240',
    'maxProfileImageSize' => '10240',
    /** qualification array */
    'qualification' => array(
        '1' => 'Secondary School Certificate',
        '2' => 'Higher Secondary Certificate',
        '3' => 'Bachelor in Computer Application',
        '4' => 'Master in Computer Application',
        '5' => 'Bachelor of Business Administration',
        '6' => 'Master of Business Administration',
        '7' => 'Bachelor of Commerce',
        '8' => 'Master of Commerce',
        '9' => 'Bachelor of Arts',
        '10' => 'Master of Arts',
        '11' => 'Bachelor of Engineering / Bachelor of Technology',
        '12' => 'Master of Engineering / Master of Technology',
        '13' => 'Bachelor of Science(IT)',
        '14' => 'Master of Science(IT)',
        '15' => 'Diploma (computer science)',
    ),

    /** relations array */
    'relations' => array(
        '1' => 'Grand Father',
        '2' => 'Grand Mother',
        '3' => 'Father',
        '4' => 'Mother',
        '5' => 'Aunt',
        '6' => 'Uncle',
        '7' => 'Husband',
        '8' => 'Wife',
        '9' => 'Brother',
        '10' => 'Sister',
        '11' => 'Daughter',
        '12' => 'Son'
    ),

    /** blood groups array */
    'blood_groups' => array(
        'A+' => 'A+',
        'A-' => 'A-',
        'B+' => 'B+',
        'B-' => 'B-',
        'O+' => 'O+',
        'O-' => 'O-',
        'AB+' => 'AB+',
        'AB-' => 'AB-'
    ),

    /** marital status array */
    'marital_status' => array(
        'married' => 'Married',
        'unmarried' => 'Unmarried',
        'divorced' => 'Divorced',
        'widowed' => 'Widowed',
    ),

     /** project type array */
     'project_type' => array(
        'billable' => 'Billable',
        'non-billable' => 'Non Billable',
        'partially-billable' => 'Partially Billable',
        'free' => "Free"
     ),

    /** emoloyee status array */
    'emp_status' => array(
        'occupied' => 'Occupied',
        'partially-occupied' => 'Partially Occupied',
        'free' => 'Free',
        'on-leave' => 'On Leave'
    ),

    'res_proj_status' => array(
        '1' => 'Billable',
        '2' => 'Non Billable',
        '3' => 'On Hold',
    ),

     /* Leave/workfromhome module year array */
     'financial_year' => array(
        '0' => '2021-2022',
        '1' => '2020-2021',
        '2' => '2019-2020',
        '3' => '2018-2019',
        '4' => '2017-2018',
        '5' => '2016-2017',
     ),

    'bank_account' => [
        '' => 'Please select',
        'company' => 'Company',
        'personal' => 'Personal',
    ],

    'payment_mode' => [
        '' => 'Please select',
        'wire_transfer' => 'Wire Transfer',
        'transferwise' => 'TransferWise',
        'paypal' => 'PayPal',
        'payoneer' => 'Payoneer',
    ],

    'payment_platform' => [
        '' => 'Please select',
        'lead_source' => 'Lead source',
    ],

    'start_year' => '2022',

    'leaves' => array(
        'total_leave' => '17',
        'allocated_leave_in_financial_year' => '17',
    ),

    'default_users_name' => array(
        'admin' => 'INX Administrative',
        'hr' => 'INX HRM',
    ),

    'default_users_email' => array(
        'admin' => 'admin@inexture.in',
        'hr' => 'hr@inexture.com',
    ),

    /** leave status array */
    'leave_status' => array(
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
    ),

    'task_hours' => array(
        '0' => '0',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        '11' => '11',
        '12' => '12',
        '13' => '13',
        '14' => '14',
        '15' => '15',
        '16' => '16',
    ),

    'task_minutes' => array(
        '0.00' => '00',
        '0.02' => '01',
        '0.03' => '02',
        '0.05' => '03',
        '0.07' => '04',
        '0.08' => '05',
        '0.10' => '06',
        '0.12' => '07',
        '0.13' => '08',
        '0.15' => '09',
        '0.17' => '10',
        '0.18' => '11',
        '0.20' => '12',
        '0.22' => '13',
        '0.23' => '14',
        '0.25' => '15',
        '0.27' => '16',
        '0.28' => '17',
        '0.30' => '18',
        '0.32' => '19',
        '0.33' => '20',
        '0.35' => '21',
        '0.37' => '22',
        '0.38' => '23',
        '0.40' => '24',
        '0.42' => '25',
        '0.43' => '26',
        '0.45' => '27',
        '0.47' => '28',
        '0.48' => '29',
        '0.50' => '30',
        '0.52' => '31',
        '0.53' => '32',
        '0.55' => '33',
        '0.57' => '34',
        '0.58' => '35',
        '0.60' => '36',
        '0.62' => '37',
        '0.63' => '38',
        '0.65' => '39',
        '0.67' => '40',
        '0.68' => '41',
        '0.7' => '42',
        '0.72' => '43',
        '0.73' => '44',
        '0.75' => '45',
        '0.77' => '46',
        '0.78' => '47',
        '0.80' => '48',
        '0.82' => '49',
        '0.83' => '50',
        '0.85' => '51',
        '0.87' => '52',
        '0.88' => '53',
        '0.90' => '54',
        '0.92' => '55',
        '0.93' => '56',
        '0.95' => '57',
        '0.97' => '58',
        '0.98' => '59',
        // '1' => '60',
    ),

    'total_leave' => '17',
    'MAIL_USERNAME' => 'admin@inexture.in',
    'MAIL_FROM_NAME' => 'INEXTURE PORTAL',

    'currentYearDate' => '01-01-2022'
];
