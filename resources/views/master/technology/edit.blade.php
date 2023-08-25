<div class="modal-header">
    <h5 class="modal-title" id="technologyModal">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($technology, ['route' => ['technology.update', $technology->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.technology.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
