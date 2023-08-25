<div class="modal-header">
    <h5 class="modal-title" id="priorityTitle">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($projectPriority, ['route' => ['project-priority.update', $projectPriority->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.project-priority.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
