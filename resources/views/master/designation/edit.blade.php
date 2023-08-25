<div class="modal-header">
    <h5 class="modal-title" id="designationModal">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($designation, ['route' => ['designation.update', $designation->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.designation.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
