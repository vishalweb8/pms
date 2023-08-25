@foreach ($result as $item)
<?php
    $defaulters=$all_users;
    $existing_sod_data = $sod_data->where("current_date", $item['current_date'])->first();
    if (!empty($existing_sod_data)) {
        $defaulters = $all_users->whereNotIn('id', explode(',', $existing_sod_data->user_id_list);
    }
    if(!empty($userOnLeave){
        $defaulters = $defaulters->whereNotIn('id', $userOnLeave);
    }
?>

@if(!empty($defaulters) && $defaulters->count()>0)
<?php
    $date_team_wise_defaulters = [];
    foreach ($defaulters as $key => $user) {
        $current_date = \Carbon\Carbon::createFromFormat('d-m-Y', $item['current_date'])->format('Y-m-d');
        $team_name =  (!empty($user->officialUser) && !empty($user->officialUser->userTeam->name)) ? $user->officialUser->userTeam->name : "Others";
        $today_leave_entry = (!empty($user->leaves)) ? $user->leaves()->where('start_date', '<=' ,$current_date)->where('end_date', '>=' ,$current_date)->first() : null;
        $user_office_details = $user->officialUser;
        $title = '';
        if($today_leave_entry && $today_leave_entry->status == 'approved') {
            continue;
        }
        $date_team_wise_defaulters[$current_date][$team_name][$key] = $user;
    }
?>
<tr style="background-color: #f1f1f1;">
    @foreach ($date_team_wise_defaulters as $date => $team)
        <table style="padding: 0px 20px; font-size: 14px; line-height: 25px; padding-bottom: 10px; width: 100%;">
            <thead>
                <th colspan="2">{{ $date }}</th>
            </thead>
            <tbody>
                @foreach ($team as $team_name => $defaulters)
                    <tr style="background-color: #f1f1f1;">
                        <td width="30%" style="vertical-align: top;">{{ $team_name }} ({{ count($defaulters) }})</td>
                        <td width="70%" style="vertical-align: top;">
                            <div style="display: inline-block;">
                                @foreach ($defaulters as $user)
                                    <span style="margin-right: 5px; margin-bottom: 10px; padding: 5px; background-color: lightcyan; border-radius: 10px; white-space: nowrap;" >
                                        {{ $user->full_name ?? '' }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</tr>
@endif
@endforeach
