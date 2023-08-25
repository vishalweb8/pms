<div class="modal-header">
    <h5 class="modal-title" id="roleModal">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($role, ['route' => ['roles.update', $role->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.roles.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
