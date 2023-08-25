<div class="modal-header">
    <h5 class="modal-title" id="policyModal">Add Setting</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::open(['route' => 'setting.store', 'id' => 'addForm', 'class' => 'add-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
@include('master.settings.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
