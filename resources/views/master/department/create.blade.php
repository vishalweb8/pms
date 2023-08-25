<div class="modal-header">
    <h5 class="modal-title" id="DepartmentTitle">Add {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::open(['route' => 'department.store', 'id' => 'addForm', 'class' => 'add-form']) !!}
@include('master.department.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
