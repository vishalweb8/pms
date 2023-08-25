<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Industry Name', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "name", 'placeholder'=> "Enter Industry Name"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="industry-status">Status</label>
            <input class="form-check-input" type="checkbox" name="industry-status" id="industry-status" {{ (isset($industry) && $industry->status) ? 'checked' : (isset($industry) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
