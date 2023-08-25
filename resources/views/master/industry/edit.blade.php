<div class="modal-header">
    <h5 class="modal-title" id="StatusTitle">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($industry, ['route' => ['industry.update', $industry->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.industry.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
