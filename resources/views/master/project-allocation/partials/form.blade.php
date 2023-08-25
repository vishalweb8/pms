<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Project Allocation Type', ['class' => 'col-form-label']) !!}
        {!! Form::text('type', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "type", 'placeholder'=> "Enter Project Allocation Type"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="status-allocation">Status</label>
            <input class="form-check-input" type="checkbox" name="status-allocation" id="status-allocation" {{ (isset($allocation) && $allocation->status) ? 'checked' : (isset($allocation) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
