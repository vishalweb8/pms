<div class="modal-header">
    <h5 class="modal-title" id="teamModal">Edit {!! Str::title($module_title ?? '') !!}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($team, ['route' => ['teams.update', $team->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('master.teams.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
