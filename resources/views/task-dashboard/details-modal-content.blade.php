@if ($modalData->isNotEmpty())
@foreach ($modalData as $detail)
<div class="border-line">
    <h6 class="bg-light-primary p-2 text-primary fw-bold top-0">{{ $detail->project_code }} - {{ $detail->name
        }}<span>{{ $detail->worklog_time }} Hours</span>
    </h6>
    @foreach ($detail->tasks as $task)
    <p><strong>{{ $task->id }} - {{ $task->name }}</strong></p>
        @foreach ($task->worklogs as $worklog)
        {!! $worklog->description !!}
        @endforeach
    @endforeach
</div>
@endforeach
@else
<div class="empty-entry-outer d-flex flex-column align-items-center justify-content-center sod-popup-block">
    <img src="{{ asset('/images/no-entry.png') }}" class="w-25" alt="image">
    <h6 class="mt-2" id="AddTaskEntry">No Worklog Available</h6>
</div>
@endif
