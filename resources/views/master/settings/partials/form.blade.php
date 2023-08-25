<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Label', ['class' => 'col-form-label']) !!}
        {!! Form::text('field_label', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "setting_name",
        'placeholder'=> "Enter Label Name"]) !!}
    </div>
    <span></span>
    <div class="form-group mb-3">

         {!! Form::label('floatingSelectGrid', 'Value', ['class' => 'col-form-label']) !!}
        {!! Form::text('value', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "setting_value",
        'placeholder'=> "Enter Value"]) !!}
    </div>
</div>
