<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Project Status', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "name", 'placeholder'=> "Enter Project Status Type"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="project-status">Status</label>
            <input class="form-check-input" type="checkbox" name="project-status" id="project-status" {{ (isset($projectStatus) && $projectStatus->status) ? 'checked' : (isset($projectStatus) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
