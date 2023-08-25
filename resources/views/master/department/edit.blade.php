<div class="modal-header">
    <h5 class="modal-title" id="AllocationTitle">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($department, ['route' => ['department.update', $department->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.department.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
