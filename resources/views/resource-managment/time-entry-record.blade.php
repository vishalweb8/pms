@foreach($users as $user)
    <?php
        $team_name = $user->officialUser->userTeam->name ?? '';
        $empCode = $user->officialUser->emp_code ?? '';
        $timeEntries = $user->officialUser->timeEntry ?? [];
        if($user->is_blocked && count($timeEntries) == 0) {
            continue;
        }
        $totalHours = (!empty($timeEntries)) ? Helper::calulateTotalDuration($timeEntries->pluck('total_duration')) : "00:00:00"  ;     

        $entryTime = (!empty($timeEntries)) ? $timeEntries->pluck('total_duration','log_date')->toArray() : [];
        
        $dayCount = count($timeEntries);
        $avgHours = "00:00:00";
        //this month total hours convert to second
        $totalThisMonthSecond = Helper::timeToSeconds($totalHours);
        if($totalThisMonthSecond > 0){
            // divide by total this month to daily hours
            $divideTotalMonthSec = ($totalThisMonthSecond / $dayCount);
            // convert seconds to time
            $avgHours = round($divideTotalMonthSec);
            $avgHours = date("H:i:s", mktime(0, 0,$avgHours));
        }
    ?>
    <tr>
        <td  class="avtar-td">
            {!! userNameWithIcon($user,$team_name) !!}
        </td>
        <td>{{ $totalHours }}</td>        
        <td>{{ $avgHours }}</td>
        @foreach($range as $key => $logDate)
            <td>
                <?php
                $dailyCount = $entryTime[$logDate] ?? 0;
                $TaskDetail = '';
                ?>

                
                @if($dailyCount != 0)
                    <a href="javascript:void(0)" data-url="{{route('view-today-time-entry',[$empCode,$logDate])}}"  role="button" class="popovers pr-2 add-btn"  title="{{$logDate}} <span>{{$dailyCount}}h</span>" >{{$dailyCount}}</a>
                    @if(array_key_exists($logDate, $user['leaveData']))
                        <span class="worklog-register bg-light-green {{$user['leaveData'][$logDate]['type']}}" title="{{ucfirst($user['leaveData'][$logDate]['type'])}} day leave">L</span>
                    @endif
                    @if(array_key_exists($logDate, $user['wfhData']))
                        <span class="worklog-register bg-light-yellow {{$user['wfhData'][$logDate]['type']}}" title="{{ucfirst($user['wfhData'][$logDate]['type'])}} day WFH"><i class="bx bx-home-circle"></i></span>
                    @endif
                @elseif(in_array($logDate, $publicHoliday))
                    <span class="worklog-register holiday bg-primary" title="Holiday">H</span>
                @elseif(in_array($logDate, $weekendList))
                    <span class="worklog-register weekend" title="Weekend">W</span>
                @elseif(array_key_exists($logDate, $user['leaveData']))
                    <span class="worklog-register bg-light-green {{$user['leaveData'][$logDate]['type']}}" title="{{ucfirst($user['leaveData'][$logDate]['type'])}} day leave">L</span>
                @elseif(array_key_exists($logDate, $user['wfhData']))
                    <span class="worklog-register bg-light-yellow {{$user['wfhData'][$logDate]['type']}}" title="{{ucfirst($user['wfhData'][$logDate]['type'])}} day WFH"><i class="bx bx-home-circle"></i></span>
                    
                @endif
            </td>
        @endforeach
    </tr>
@endforeach
