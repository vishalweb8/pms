@extends($theme)
@section('breadcrumbs')
@if (isset($task))
{{ Breadcrumbs::render('edit-project-task', $project_name, $project_id) }}
@else
{{ Breadcrumbs::render('add-project-task', $project_name, $project_id) }}
@endif
@endsection
@section('content')

<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="m-0 page-title">{{ isset($task) ? 'Edit' : 'Add' }} Task</h4>
    <a href="{{ route('project-details', $project_id) }}" type="reset" class="btn btn-secondary mx-2 w-md">Back</a>
</div>

<div class="row">
    <div class="col-md-12 add-tasks-detail">
        <div class="card h-100">
            <div class="card-body">
                <form method="POST" id="addTaskForm" action="{{ route('store-task-details', $project_id) }}">
                    @csrf
                    <input type="hidden" name="project_id" id="project_id" value="{{ $project_id }}">
                    <input type="hidden" name="task_id" id="task_id" value="{{ isset($task) ? $task->id : '' }}">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            {!! Form::label('taskName', 'Task Name', ['class' => 'col-form-label']) !!}
                            {!! Form::text('name', isset($task) ? $task->name : null, ['class' => 'form-control', 'id'=>
                            "taskName",
                            'placeholder'=> "Enter Task Name"]) !!}
                            @if($errors->has('name'))
                            <span class="error">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 form-group">
                            {!! Form::label('description', 'Task Description', ['class' => 'col-form-label']) !!}
                            {!! Form::textArea('description', isset($task) ? $task->description : null, ['class' =>
                            'form-control', 'id'=> "description",
                            'placeholder'=> "Enter Task Description" , 'rows' => 4])
                            !!}
                            @if($errors->has('description'))
                            <span class="error">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 form-group">
                            {!! Form::label('assignees', 'Assignee', ['class' => 'col-form-label']) !!}
                            {!! Form::select('assignees[]',$assignees,(isset($selectedAssignee)) ? $selectedAssignee : null,['multiple' =>
                            'multiple','class' => 'form-control select2', 'id' => 'assignees']) !!}
                            @if($errors->has('name'))
                            <span class="error">{{ $errors->first('assignees') }}</span>
                            @endif
                        </div>
                        <div class="col-md-3 form-group">
                            {!! Form::label('priority', 'Priority', ['class' => 'col-form-label']) !!}
                            {!! Form::select('priority',$priorities,isset($task) ? $task->priority_id : null, ['class'
                            => 'form-control select2 no-search',
                            'id' =>
                            'priority']) !!}
                            @if($errors->has('priority'))
                            <span class="error">{{ $errors->first('priority') }}</span>
                            @endif
                        </div>
                        <div class="col-md-3 form-group">
                            {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!}
                            {!! Form::select('status',$statuses,isset($task) ? $task->status : null,
                            ['class' => 'form-control select2 no-search',
                            'id' => 'status']) !!}
                        </div>
                        <div class="col-md-12 form-group d-flex justify-content-center mt-4 mb-2">
                            <a href="{{ route('project-details', $project_id) }}" type="reset"
                                class="btn btn-secondary mx-2 w-md">Cancel</a>
                            <button type="submit" class="btn btn-primary w-md addTaskBtn">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    // For disable save button after one click
    $(document).ready(function () {
        $("#addTaskForm").submit(function (e) {
            $(".addTaskBtn").attr("disabled", true);
            return true;
        });
    });
</script>
<script src="{{ asset('js/modules/projects.js') }}"></script>
@endpush

@endsection
