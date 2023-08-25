<div class="input-group">
    {!! Form::text('select_date', (isset($today) ? $today : null), ['class' => 'form-control ', 'placeholder' =>
    'Select Date',
    'id' =>
    'selectedDate', 'change' => "getData()", 'data-provide' => 'datepicker','data-date-autoclose'=> "true" ,'data-date-format'=> "dd-mm-yyyy"])
    !!}
</div>
