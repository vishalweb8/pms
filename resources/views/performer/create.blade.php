<div class="modal-header">
    <h5 class="modal-title" id="performerModal">Add Performer</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{!! Form::open(['route' => 'performer.store', 'id' => 'addForm', 'class' => 'add-form']) !!}
@include('performer.partials.form')
@include('common.form-footer-buttons')
{!! Form::close() !!}
