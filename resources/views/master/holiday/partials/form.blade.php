<div class="modal-body">
    <div class="form-group mb-3">
        {!! Form::label('floatingSelectGrid', 'Holiday', ['class' => 'col-form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off','id'=> "holiday", 'placeholder'=> "Enter Holiday Name"]) !!}
    </div>
    <div class="form-group mb-3">
        <label class="col-form-label" for="datepicker1">Select Date</label>
        <div class="position-relative" id="datepicker1">
            <input type="text" placeholder="Select Date" name="date" class="form-control datepicker"
            value="@if(isset($holiday)) {{ $holiday->date }} @endif"
            data-date-format="dd-mm-yyyy"
            data-date-container="#datepicker1"
            data-provide="datepicker"
            data-date-days-of-week-disabled="0,6"
            data-date-start-date="new Date()"
            autocomplete="off">
        </div>
    </div>
</div>
