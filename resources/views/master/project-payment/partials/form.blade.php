<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Project Payment Type', ['class' => 'col-form-label']) !!}
        {!! Form::text('type', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "type", 'placeholder'=> "Enter Project Payment Type"]) !!}
    </div>
    <span></span>
    <div class="mb-3">
        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
            <label class="form-check-label" for="payment-status">Status</label>
            <input class="form-check-input" type="checkbox" name="payment-status" id="payment-status" {{ (isset($project_payment) && $project_payment->status) ? 'checked' : (isset($project_payment) ? '' : 'checked' ) }}>
        </div>
    </div>
</div>
