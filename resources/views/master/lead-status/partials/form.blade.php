<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Lead Status', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "name", 'placeholder'=> "Enter Lead Status Type"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="lead-status">Status</label>
            <input class="form-check-input" type="checkbox" name="lead-status" id="lead-status" {{ (isset($leadStatus) && $leadStatus->status) ? 'checked' : (isset($leadStatus) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
