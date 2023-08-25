<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Lead Source', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "name", 'placeholder'=> "Enter Lead Source Type"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="lead-source-status">Status</label>
            <input class="form-check-input" type="checkbox" name="lead-source-status" id="lead-source-status" {{ (isset($leadSource) && $leadSource->status) ? 'checked' : (isset($leadSource) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
