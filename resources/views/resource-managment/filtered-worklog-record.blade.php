@foreach($users as $user)
    <tr>
        <td  class="avtar-td" style="display:table-cell;">
            <?php
                $team_name = $user->officialUser->userTeam->name ?? '';

                $worklogProjects = $user->workLogData->groupBy('project_name');
            ?>
            {!! userNameWithIcon($user,$team_name) !!}
        </td>
        <td colspan="2">
            @if($worklogProjects->isNotEmpty())
                <table class="filtered-table">
                    @forelse($worklogProjects as $project => $tasks)
                    <tr>
                        <td style="width: 200px">
                            {{$project }}
                        </td>
                        <td>
                            @foreach($tasks as $task)
                            <div>
                                {{$task->task_name." -> ".$task->task_total_time }} hr(s)
                            </div>
                            @endforeach
                        </td>
                        <td style="text-align: right">
                            {{$tasks->sum('task_total_time')}} hr(s)
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </table>
            @else
                <center> - </center>
            @endif
        </td>
        <td style="text-align: right">
            {{$user->workLogData->sum('task_total_time')}} hr(s)
        </td>

    </tr>
@endforeach
