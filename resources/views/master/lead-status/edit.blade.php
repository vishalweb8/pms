<div class="modal-header">
    <h5 class="modal-title" id="StatusTitle">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($leadStatus, ['route' => ['lead-status.update', $leadStatus->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.lead-status.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
