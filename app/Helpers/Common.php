<?php

Use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * get array of financial years
 *
 * @param  mixed $startDate
 * @return array
 */
function getFinancialYears($startDate = "2021-04-01 00:00:00" ) {
    $startYearObject = Carbon::parse($startDate);
    $loopYear = $startYearObject->format('Y');
    $endYear   = now()->year;
    $currentMonth   = date('m');
    if($currentMonth > 3) {
        $endYear++;
    }
    $financeYears = [];

    do {

        $currentEndYear = $startYearObject->addYear()->format('Y');
        $year = "{$loopYear}-{$currentEndYear}";
        $loopYear = $currentEndYear;
        $financeYears[$year] = $year;
    } while ($currentEndYear != $endYear);

    return $financeYears;
}


/**
 * get current financial start year
 *
 * @return int
 */
function currentFinStartYear() {
    $currentMonth   = date('m');
    $year = date('Y');

    return ($currentMonth > 3) ? $year : --$year;
}

/**
 * get current financial end year
 *
 * @return int
 */
function currentFinEndYear() {
    $currentMonth   = date('m');
    $year = date('Y');

    return ($currentMonth > 3) ? ++$year : $year;
}

/**
 * for get remaining leaves
 *
 * @param  mixed $allocatedLeaves
 * @param  mixed $leaves
 * @return double
 */
function getRemainingLeaves($allocatedLeaves, $leaves) {
    $usedLeaves = $leaves->sum('duration');
    $remainingLeaves = getTotalLeaves($allocatedLeaves) - $usedLeaves;
    return number_format($remainingLeaves,1);
}

function getTotalLeaves($userLeaves){
    $total_leave = 0;
    if(!empty($userLeaves)) {
        $total_leave = ($userLeaves->allocated_leave) ? $userLeaves->allocated_leave : 0;
        $total_leave += ($userLeaves->compensation_leaves) ? $userLeaves->compensation_leaves : 0;
    }
    return $total_leave;
}

/**
 * for convert thousands corrency format like 1000 to 1k.
 *
 * @param  mixed $num
 * @return string
 */
function thousandsCurrencyFormat($num)
{
    if ($num > 1000) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('k', 'm', 'b', 't');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];

        return $x_display;
    }

    return (string) $num;
}

/**
 * for calculate monthly billing expected amount based on payment type
 *
 * @param  mixed $paymentType
 * @param  mixed $amount
 * @return int
 */
function calculateMonthlyBilling($paymentType, $amount)
{
    if($paymentType && $amount) {
        switch ($paymentType->type) {
            case "Day":
                $monthlyAmt = $amount * 20;
                break;
            case "Hourly":
                $monthlyAmt = $amount * 160;
                break;
            case "Weekly":
                $monthlyAmt = $amount * 4;
                break;
            case "Yearly":
                $monthlyAmt = $amount/12;
                break;
            default:
                $monthlyAmt = $amount; // monthly and fixed for same
        }
        return $monthlyAmt;
    }

    return 0;
}

/**
 * for get last month year
 *
 * @return void
 */
function lastMonthYear()
{
    return date('m-Y', strtotime("-1 month"));
}

/* for calculate duration of time in/out
*
* @param  mixed $outTimes
* @param  mixed $inTimes
* @return array
*/
function getDurations($outTimes, $inTimes) {
    $inTimes = explode(',',$inTimes);
    $outTimes = ($outTimes) ? explode(',',$outTimes) : [];
    $durations = [];
    foreach ($inTimes as $key => $inTime) {
        $start  = new Carbon($inTime);
        if (!isset($outTimes[$key])) {
                $end = Carbon::now();
        }else{
            $end  = new Carbon($outTimes[$key]);
        }
        // if(isset($outTimes[$key])) {
            $durations[] = $start->diff($end)->format('%H:%I:%S');
        // }
    }
    return $durations;
}

/**
 * for get icon with data url to get known technology of employee
 *
 * @param  mixed $userId
 * @return string
 */
function knownTechIcon($userId) {
    $info = " <i class='mdi mdi-alert-circle known-tech cursor-pointer' data-url='".route("user.getKnownTechnology",$userId)."'></i>";
    return $info;
}

/**
 * for get user name with icon and team name
 *
 * @param  mixed $user
 * @param  mixed $teamName
 * @return void
 */
function userNameWithIcon($user, $teamName = '') {
    $userName = '<div class="all-emp-name-list name-with-info">';
        if (!empty($user['profile_image']) && Storage::exists("public/upload/user/images/". $user['profile_image'])){
            $userName .= ' <img src="'. asset("/storage/upload/user/images/" . $user['profile_image']) .'" class="rounded-circle avatar-xs" alt="" /> ';
        } else {
            $userName .= ' <div class="avatar-xs">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-12">
                            ' . (!empty($user['profile_picture']) ? $user['profile_picture'] : "") . '
                        </span>
                    </div>';
        }
        $userName .= '<div class="name-info ms-2">
            <div class="d-flex align-items-start" >
            <h5 class="font-size-14 m-0">
                ' . (!empty($user['full_name']) ? $user['full_name'].knownTechIcon($user->id) : "") . '
            </h5>
            </div>

            <span class="font-size-12 text-muted">
                ' . $teamName;
            if(!empty($user->experience)) {
                $userName .= " - ". $user->experience ." Yrs";
            }
    $userName .='</span>
        </div>
    </div>';
    return $userName;
}

/**
 * for get total experience of user
 *
 * @param  mixed $officialUser
 * @return string
 */
function userExperience($officialUser) {
    $user_experiance = (double) (!empty($officialUser) && !empty($officialUser->experience)) ? $officialUser->experience : 0;
    // separate the year and month from value
    $exp_year = intval($user_experiance);
    $exp_month = ($user_experiance - $exp_year);
    $exp_month = (int) !empty($exp_month) ? substr($exp_month, 2):0;
    // Check the user's joining date:
    $join_date = !empty($officialUser->joining_date) ? \Carbon\Carbon::createFromFormat('d-m-Y', $officialUser->joining_date) : '';
    if (!empty($join_date)) {
        $month = $join_date->diffInMonths(now());
        $month += $exp_month;
        $year = intval($month/12);
        $exp_year += $year;
        $exp_month = round($month%12, 2);

    }

    return __("{$exp_year} year(s) and {$exp_month} month(s)");
}

/**
 * for get all months of calender
 *
 * @return void
 */
function getMonths() {
    $months = [];
    for ($month = 1; $month <= 12; $month++) {
        $months[$month] = date('F', mktime(0,0,0,$month));
    }
    return $months;
}
