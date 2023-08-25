<div class="modal-header">
    <h5 class="modal-title" id="performerModal">Edit Performer</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::model($performer, ['route' => ['performer.update', $performer->id], 'id' => 'editForm', 'class' => 'edit-form']) !!}
@include('performer.partials.form')
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>
{!! Form::close() !!}
