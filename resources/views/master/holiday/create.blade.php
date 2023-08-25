<div class="modal-header">
    <h5 class="modal-title" id="holidayModal">Add Holiday</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::open(['route' => 'holiday.store', 'id' => 'addForm', 'class' => 'add-form']) !!}
@include('master.holiday.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
