<div class="modal-header">
    <h5 class="modal-title" id="holidayModal">Edit Holiday</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($holiday, ['route' => ['holiday.update', $holiday->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
<input type="hidden" name="holidayId" value="{{$holiday->id}}">
@include('master.holiday.partials.form')
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>
{!! Form::close() !!}
