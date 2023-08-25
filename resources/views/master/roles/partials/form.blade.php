<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Role', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "rolename", 'placeholder'=> "Enter Role Name"]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Code', ['class' => 'col-form-label']) !!}
        {!! Form::text('code', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "code", 'placeholder'=> "Enter Role Code"]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Type', ['class' => 'col-form-label']) !!}
        {!! Form::text('type', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "type", 'placeholder'=> "Enter Role Type"]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Guard Name', ['class' => 'col-form-label']) !!}
        {!! Form::text('guard_name', "web", ['class' => 'form-control', 'autocomplete' => 'off','id'=> "guard", 'placeholder'=> "Enter Role Name", "disabled"]) !!}
    </div>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="status-role">Status</label>
            <input class="form-check-input" type="checkbox" name="status-role" id="status-role" {{ (isset($role) && $role->status) ? 'checked' : (isset($role) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
