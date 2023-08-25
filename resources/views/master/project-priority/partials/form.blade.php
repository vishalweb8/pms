<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Project Priority', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "name", 'placeholder'=> "Enter Project Priority"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="priority-status">Status</label>
            <input class="form-check-input" type="checkbox" name="priority-status" id="priority-status" {{ (isset($projectPriority) && $projectPriority->status) ? 'checked' : (isset($projectPriority) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
