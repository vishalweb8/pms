<div class="modal-header">
    <h5 class="modal-title" id="settingModal">Edit Settings</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($setting, ['route' => ['setting.update', $setting->id], 'id' => 'editForm', 'class' => 'edit-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
<input type="hidden" name="_method" value="put">
<input type="hidden" name="settingId" value="{{$setting->id}}">
@include('master.settings.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
