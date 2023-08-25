@foreach ($result as $item)
<?php
    $defaulters=$all_users;
    $existing_sod_data = $sod_data->where("current_date", $item['current_date'])->first();
    if (!empty($existing_sod_data)) {
        $defaulters = $all_users->whereNotIn('id', explode(',', $existing_sod_data->user_id_list));
    }
    if(!empty($userOnLeave)){
        $defaulters = $defaulters->whereNotIn('id', $userOnLeave);
    }
?>

@if(!empty($defaulters) && $defaulters->count()>0)
<tr>
    <td class="p-1 bg-white">
        {{ $item['current_date'] ?? ''}}
    </td>
    <td class="avtar-td w-auto d-block">
        <div class="d-flex flex-wrap">
            @foreach ($defaulters as $user)
                <?php
                    $dyanamic_class = '';
                    $title = "Pending SOD/EOD";
                    $current_date = \Carbon\Carbon::createFromFormat('d-m-Y', $item['current_date'])->format('Y-m-d');
                    $today_leave_entry = (!empty($user->leaves)) ? $user->leaves()->where('start_date', '<=' ,$current_date)->where('end_date', '>=' ,$current_date)->first() : null;
                    $user_office_details = $user->officialUser;

                    if($today_leave_entry) {
                        if($today_leave_entry->status == 'approved') {
                            $title = "Approved Leave";
                            if($today_leave_entry->type == 'full') {
                                $dyanamic_class .= 'bg-light-green';
                            }else {
                                $dyanamic_class .= ($today_leave_entry->halfday_status == "firsthalf") ? 'bg-first-half-approved' : 'bg-second-half-approved';
                            }
                        }else {
                            $title = "Pending Leave";
                            if($today_leave_entry->type == 'full') {
                                $dyanamic_class .= 'bg-light-yellow';
                            }else {
                                $dyanamic_class .= ($today_leave_entry->halfday_status == "firsthalf") ? 'bg-first-half-pending' : 'bg-second-half-pending';
                            }
                        }
                        if($today_leave_entry->type == "half") {
                            $title .= ($today_leave_entry->halfday_status == "firsthalf") ? " (First Half)" : " (Second Half)";
                        }
                    }
                    // if($user->id == 8) {
                    //     dump($user); exit();
                    // }
                    if(!empty($user->officialUser->task_entry_date)) {
                        $task_entry_date = \Carbon\Carbon::parse($user->officialUser->task_entry_date);
                        $task_entry_date->hour = $task_entry_date->minute = $task_entry_date->second = 0;
                        $current_date = \Carbon\Carbon::parse($item['current_date']);
                        $current_date->hour = $current_date->minute = $current_date->second = 0;

                        if($current_date->lessThan($task_entry_date)){
                            $dyanamic_class .= ' d-none';
                        }
                    }
                ?>
                <div class="sod-eod all-emp-name-list {{ $dyanamic_class }}" title="{{ $title }}" >
                    <!-- <img src={projectItem.img} class="rounded-circle avatar-xs" alt="" /> -->
                    @if (!empty($user['profile_image']) && Storage::exists("public/upload/user/images/". $user['profile_image']))
                        <img src="{{ asset("/storage/upload/user/images/". $user['profile_image']) }}" class="rounded-circle avatar-xs" alt="" />
                    @else
                        <div class="avatar-xs">
                            <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-12">
                                {{ $user['profile_picture'] ?? '' }}
                            </span>
                        </div>
                    @endif
                    <div class="name-info ms-2">
                        <h5 class="font-size-14 m-0">
                            {{ $user->full_name ?? '' }} {!! knownTechIcon($user->id) !!}
                        </h5>
                        <span class="font-size-12 text-muted">
                            {{ $user->officialUser->userTeam->name ?? '' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </td>
</tr>
@endif
@endforeach
