<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Technology', ['class' => 'col-form-label']) !!}
        {!! Form::text('technology', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "technology", 'placeholder'=> "Enter Technology Name"]) !!}
    </div>
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Description', ['class' => 'col-form-label']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control', 'autocomplete' => 'nope','placeholder' => "Enter Description", 'rows' => '7']) !!}
    </div>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="status-technology">Status</label>
            <input class="form-check-input" type="checkbox" name="status-technology" id="status-technology" {{ (isset($technology) && $technology->status) ? 'checked' : (isset($technology) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
