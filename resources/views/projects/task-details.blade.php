@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('view-project-task', $task->project->project_code.' - '.$task->project->name, $task->project->id,$task->id.' - '.$task->name)  }}
@endsection
@section('content')

<div class="page-title-box d-flex align-items-center justify-content-between">
    <h4 class="m-0 page-title">Task Details</h4>
    <a href="{{ route('project-details', $task->project_id) }}" type="reset"
        class="btn btn-secondary mx-2 w-md">Back</a>
</div>

<div class="project-detail-wrapper">
    <div class="row">
        <div class="col-lg-8 col-xl-9">
            @if ($task->description)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="project-title">
                        <h5 class="card-title">{{ $task->id }} - {{ $task->name }}</h5>
                    </div>
                    <div class="p-detail-disc">
                        <p>{{ $task->description }}</p>
                    </div>
                </div>
            </div>
            @endif
            @if (count($workLogs) > 0)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-center hours-filter">
                        <h5 class="card-title mb-0">Worklogs</h5>
                        <div class=" d-sm-flex align-items-center">
                            <input class="form-control start-date" placeholder="Start Date"
                                data-provide="datepicker" data-date-autoclose="true" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" id="start_date">
                            <input class="form-control end-date" placeholder="End Date"
                                data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" type="text" autocomplete="off" id="end_date">
                            <select class="form-control select2 no-search" name="logUser" id="logUser">
                                <option value="">All Users</option>
                                @foreach ($logUsers as $key => $logUser)
                                <option value="{{ $key }}">{{ $logUser }}</option>
                                @endforeach
                            </select>
                            <span class="clear-filter cursor-pointer">Clear</span>
                            <label for="logUser">Total Hours:</label>
                            <input type="text" class="form-control filter-hours" name="totalHours" id="totalHours"
                                value="{{ $totalHours }}" readonly>
                        </div>
                    </div>
                    <ul class="files-list filter-list task-comments" id="workLogUI">
                        @include('projects.task-comments')
                    </ul>
                </div>
            </div>
            @endif
        </div>
        <div class="col-lg-4 col-xl-3">
            <div class="card mb-3 work-log-wrapper">
                <div class="card-body">
                    <h6 class="card-title m-b-15">Add Log Time</h6>
                    <form action="{{ route('save-work-log-of-task', [$task->project_id, $task->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" id="taskId" value="{{ $task->id }}">
                        <input type="hidden" name="project_id" id="projectId" value="{{ $task->project_id }}">
                        <input type="hidden" name="user_id" id="taskUserId" value="{{ $task->user_id }}">
                        <input type="hidden" name="log_id" id="logId" value="">
                        <div class="form-group mb-0 mt-3 date-f-w">
                            <label for="logDate" class="col-form-label">Date</label>
                            <input class="form-control " placeholder="Select Date" id="logDate"
                                data-provide="datepicker" data-date-autoclose="true" data-date-format="dd-mm-yyyy"
                                name="log_date" type="text" autocomplete="off" value="{{ old('log_date', now()->format('d-m-Y')) }}">
                            @if($errors->has('log_date'))
                            <span class="error">{{ $errors->first('log_date') }}</span>
                            @endif
                        </div>
                        <div class="form-group mt-3">
                            <label for="logTime" class="col-form-label">Log Time (h.mm)</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control select2" name="log_hours" id="logHours">
                                        @foreach (config('constant.task_hours') as $hours)
                                        @if (old('log_hours') == $hours)
                                        <option value="{{ $hours }}" selected>{{ $hours }}</option>
                                        @else
                                        <option value="{{ $hours }}">{{ $hours }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::select('log_minutes', config('constant.task_minutes') , null, ['class'=> 'form-control select2', 'id' => 'logMinutes' ]) !!}

                                    {{-- <select class="form-control select2 " name="log_minutes" id="logMinutes">
                                        @foreach (config('constant.task_minutes') as $minutes)
                                        @if (old('log_minutes') == $minutes)
                                        <option value="{{ $minutes }}" selected>{{ $minutes }}</option>
                                        @else
                                        <option value="{{ $minutes }}">{{ $minutes }}</option>
                                        @endif
                                        @endforeach
                                    </select> --}}
                                </div>
                            </div>
                            @if($errors->has('log_time'))
                            <span class=" error">{{ $errors->first('log_time') }}</span>
                            @endif
                        </div>
                        <div class="form-group mt-3">
                            <label for="workDescription" class="col-form-label">Work Description</label>
                            <textarea class="form-control" placeholder="Enter Work Description" name="description"
                                id="workDescription" rows="5">{{ old('description') }}
                            </textarea>
                            @if($errors->has('description'))
                            <span class="error">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                        <div class="form-group d-flex justify-content-center mt-4 mb-2">
                            <a href="javascript:void(0)" id="clearWorkLogDetails" type="reset"
                                class="btn btn-secondary mx-2 w-md">Clear</a>
                            <button type="submit" class="btn btn-primary w-md" id='taskLogBtn' {{$diasbled }}>Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script type="text/javascript">
    var editTaskWorkLogURL = "{{ route('get-work-log-of-task') }}";
    var deleteTaskWorkLogURL = "{{ route('delete-work-log-of-task') }}";
    var getLogOfUserURL = "{{ route('worklog-of-selected-user', "$task->id") }}";
</script>
<script type="text/javascript">
    CKEDITOR.config.contentsCss = '/css/style.css';
    CKEDITOR.replace('workDescription', {
        toolbar: [{
                name: 'basicstyles',
                groups: ['basicstyles', 'cleanup'],
                items: ['Bold', 'Italic', 'Underline', ]
            },
            {
                name: 'paragraph',
                groups: ['list'],
                items: ['BulletedList']
            },
            {
                name: 'links',
                items: ['Link', 'Unlink']
            },
        ],
        on: {
            pluginsLoaded: function() {
                var cmd = this.addCommand('tag', {
                    allowedContent: {
                        input: {
                            attributes: ['type', 'value'],
                        },
                        strong: {
                            attributes: ['project_id', 'id'],
                        }
                    }
                });

                // This is a custom command, so we need to register it.
                this.addFeature(cmd);
            },
            // instanceReady: function (evt) {
            //     console.log("loaded : ", evt);
            //     this.execCommand('bulletedlist');
            // },
            // focus: function (evt) {
            //     console.log("loaded : ", evt);
            //     this.execCommand('bulletedlist');
            // },
        },
    });
    CKEDITOR.on( 'dialogDefinition', function( ev ) {
    var dialogName = ev.data.name;
        var dialogDefinition = ev.data.definition;
        if ( dialogName == 'link' ) {
            var targetTab = dialogDefinition.getContents( 'target' );
            var targetField = targetTab.get( 'linkTargetType' );
            targetField[ 'default' ] = '_blank';
        }
    });

    document.getElementById("taskLogBtn").onclick = function() {
    setTimeout(() => {
        this.disabled = true;
    }, 100);
}


</script>
<script src="{{ asset('js/modules/projects.js') }}"></script>
@endpush

@endsection
