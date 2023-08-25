<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Department', ['class' => 'col-form-label']) !!}
        {!! Form::text('department', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "type", 'placeholder'=> "Enter Department"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="status-department">Status</label>
            <input class="form-check-input"  type="checkbox" name="status-department" id="status-department"
                        {{ (isset($department) && $department->status) ? 'checked' : (isset($department) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
