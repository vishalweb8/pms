@foreach($workRepo as $userWork)

    <tr>
        <td  class="avtar-td">
            <?php
            $team_name = '';
            if(isset($userWork->officialUser) && !empty($userWork->officialUser) && isset($userWork->officialUser->userTeam) && !empty($userWork->officialUser->userTeam) && !empty($userWork->officialUser->userTeam->name) ) {
                $team_name = $userWork->officialUser->userTeam->name;
            }

            ?>
            {!! userNameWithIcon($userWork,$team_name) !!}
        </td>
        <td>{{ !empty($userWork['monthly_hour']) ? $userWork['monthly_hour'].'h' : '' }}</td>
        <?php
            $temp = collect([]);
            $userWork['workLogData']->map(function ($item) use (&$temp) {
                $day_total = collect($item)->sum('log_time');
                if(!empty($day_total)) {
                    $temp[] = $day_total;
                }
            });
            $monthly_avg = (!empty($temp)) ? round($temp->avg(), 2) : 0;
        ?>
        <td>{{ !empty($monthly_avg) ? $monthly_avg .'h' : '' }}</td>
        @foreach($userWork['workLogData'] as $logDate => $dayWorkLog)
            <td>
                <?php
                $dailyCount = 0;
                $TaskDetail = '';?>

                @foreach($dayWorkLog as $LogData)

                    <?php
                    if(isset($LogData->logProjectTask)) {

                        $dailyCount += $LogData->log_time;
                        $TaskDetail .= '<div class="task-detail"><span class="task-project"><b>'. Str::limit($LogData->logProjectTask->project->name,30).':</b><span class="task-name"> '.Str::limit($LogData->logProjectTask->name, 30). '</span></span><b class="task-hour">'.$LogData->log_time.'h</b>'.'</div>';

                    }
                    ?>
                @endforeach
                @if($dailyCount != 0)
                    @php $dailyCount = number_format($dailyCount, 2); @endphp
                    <a href="javascript:void(0)" role="button" id={{'key'.$logDate}} class="popovers pr-2 getTimeEntryDetail" data-date={{Carbon\Carbon::parse($logDate)->format('Y-m-d')}} data-id={{$userWork['id']}} title="{{$logDate}} <span>{{$dailyCount}}h</span>" >{{$dailyCount}}h</a>
                    @if(array_key_exists($logDate, $userWork['leaveData']))
                        <span class="worklog-register bg-light-green {{$userWork['leaveData'][$logDate]['type']}}" title="{{ucfirst($userWork['leaveData'][$logDate]['type'])}} day leave">L</span>
                    @endif
                    @if(array_key_exists($logDate, $userWork['wfhData']))
                        <span class="worklog-register bg-light-yellow {{$userWork['wfhData'][$logDate]['type']}}" title="{{ucfirst($userWork['wfhData'][$logDate]['type'])}} day WFH"><i class="bx bx-home-circle"></i></span>
                    @endif
                @elseif(in_array($logDate, $publicHoliday))
                    <span class="worklog-register holiday bg-primary" title="Holiday">H</span>
                @elseif(in_array($logDate, $weekendList))
                    <span class="worklog-register weekend" title="Weekend">W</span>
                @elseif(array_key_exists($logDate, $userWork['leaveData']))
                    <span class="worklog-register bg-light-green {{$userWork['leaveData'][$logDate]['type']}}" title="{{ucfirst($userWork['leaveData'][$logDate]['type'])}} day leave">L</span>
                @endif
            </td>
        @endforeach
    </tr>
@endforeach
