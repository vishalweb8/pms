<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeaveImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $old_users = collect(config('old_portal_statistical.users'));
        $new_users = User::get();
        // Request From
        $request_from = $new_users->where('email', $old_users[$row['lm_user_id']])->first();

        // echo "<pre>"; print_r([$row, 'request_from' => $request_from]); echo "</pre>";

        // Request to
        // $request_to_emails = $old_users->filter(function ($value, $key) use($row) {
        //     return in_array($key, explode(',', $row['lm_request_to']));
        // });
        $request_to_emails = ['admin@inexture.in'];
        $request_to = $new_users->whereIn('email', $request_to_emails)->pluck('id');

        // leave type lm_leave_type=1-half day, 2-full day
        $leave_type = ($row['lm_leave_type'] == '1') ? 'half' : 'full';

        // half day status lm_half_time=1- first half, 2- second half
        $half_day_status = null;
        if($row['lm_leave_type'] == 1){
            if($row['lm_half_time'] == 1) {
                $half_day_status = 'firsthalf';
            }elseif($row['lm_half_time'] == 2) {
                $half_day_status = 'secondhalf';
            }
        }

        // Status lm_status= 0-Pending,1-Approve,2-Reject,3-cancel
        $status = 'pending';
        if($row['lm_status'] == 1) {
            $status = 'approved';
        }elseif($row['lm_status'] == 2) {
            $status = 'rejected';
        }elseif($row['lm_status'] == 3) {
            $status = 'cancelled';
        }

        // Leave approved/reject by
        $action_by = null;
        if(!empty($row['lm_approved_by'])) {
            $action_user = $new_users->where('email', $old_users[$row['lm_approved_by']])->first();
            if(!empty($action_user)) {
                $action_by = $action_user->id;
            }
        }
        $start_date = Carbon::parse($row['lm_start_date']);
        $end_date = Carbon::parse($row['lm_end_date']);
        if($start_date->greaterThanOrEqualTo(Carbon::parse("2021-04-01 00:00:00")) && $start_date->lessThan(Carbon::parse("2022-01-01 00:00:00"))) {
            $holiday_list = collect([
                '14-01-2021',
                '15-01-2021',
                '26-01-2021',
                '29-03-2021',
                '30-08-2021',
                '15-10-2021',
                '04-11-2021',
                '05-11-2021',
            ]);
            $input['request_from'] = (!empty($request_from)) ? $request_from->id : '';
            $input['request_to'] = (!empty($request_to)) ? implode(',',$request_to->toArray()): '';
            $input['type'] = $leave_type;
            $input['halfday_status'] = $half_day_status;
            $input['start_date'] = $row['lm_start_date'];
            $input['end_date'] = $row['lm_end_date'];
            $input['return_date'] = $row['lm_return_date'];
            $input['duration'] = Helper::calculateLeaveDuration($start_date, $end_date,$holiday_list);
            $input['reason'] = $row['lm_reason'];
            $input['isadhoc_leave'] = $row['lm_isadhoc_leave'];
            $input['adhoc_status'] = null;
            $input['available_on_phone'] = $row['lm_availability_on_phone'];
            $input['available_on_city'] = $row['lm_availability_on_city'];
            $input['emergency_contact'] = $row['lm_emergency_contact'];
            $input['status'] = "approved";
            $input['approved_by'] = ($status == 'approved') ? $action_by : null;
            $input['rejected_by'] = ($status == 'rejected') ? $action_by : null;
            $input['created_at'] = $row['created_at'];
            $input['updated_at'] = $row['created_at'];

            if(!empty($input['request_from'])) {
                $exist_entry = Leave::updateOrCreate(
                                        [
                                            'request_from' => $input['request_from'],
                                            'start_date' => $input['start_date'],
                                            'end_date' => $input['end_date']
                                        ], $input
                                    );
                if(empty($exist_entry)) {
                    return Leave::create($input);
                }
            }
        }

    }
}
