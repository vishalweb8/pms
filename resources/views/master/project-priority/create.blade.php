<div class="modal-header">
    <h5 class="modal-title" id="priorityTitle">Add {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::open(['route' => 'project-priority.store', 'id' => 'addForm', 'class' => 'add-form']) !!}
@include('master.project-priority.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
