<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Designation', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "designation", 'placeholder'=> "Enter Designation Name"]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Designation Code', ['class' => 'col-form-label']) !!}
        {!! Form::text('designation_code', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "designation", 'placeholder'=> "Enter Designation Code"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="status-designation">Status</label>
            <input class="form-check-input" type="checkbox" name="status-designation" id="status-designation" {{ (isset($designation) && $designation->status) ? 'checked' : (isset($designation) ? '' : 'checked') }}>
        </div>
    </div>
</div>
