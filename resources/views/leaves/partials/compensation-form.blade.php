{!! Form::hidden('_url', url()->previous() )!!}
<div class="row">
    <div class="col-md-6">
        <div class="row">
            @isset($addTeamLeave)
            <div class="form-group mb-3">
                {!! Form::Label('floatingSelectGrid', 'Select Request from employee', ['class' => 'col-form-label']) !!}
                {!! Form::select('request_from', $requestFrom, null, ['class' => 'form-control select2','id' => 'requestFrom', (!empty($leave)) ? 'disabled' : '']) !!}
                @if(!empty($leave))
                    {!! Form::hidden('request_from', $leave->request_from) !!}
                @endif
            </div>
            @endisset
            <div class="col-md-12 form-group">
                {!! Form::hidden('id', null) !!}
                {!! Form::label('reqeustTo', 'Send Request To', ['class' => 'col-form-label']) !!}
                {!! Form::select('request_to_comp[]', $requestUsers, array_keys($requestUsers),
                ['class' =>
                'form-control select2', 'id' => 'reqeustTo', 'multiple' => 'multiple']) !!}
                {{-- @if(!empty($requestUsers))
                    {!! Form::hidden('request_to', $requestUsers) !!}
                @endif --}}
                @error('request_to')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="billing-name" class="col-form-label d-block">Day Type</label>
                <div class="form-check form-check-inline font-size-16">
                    {!! Form::radio('type', 'full', null, ['class' => 'form-check-input','id' => 'leaveType1','checked'
                    =>'checked']) !!}
                    {!! Form::label('leaveType1', 'Full Day', ['class' => 'form-check-label font-size-13']) !!}
                </div>
                <div class="form-check form-check-inline font-size-16">
                    {!! Form::radio('type', 'half', null, ['class' => 'form-check-input','id' => 'leaveType2']) !!}
                    {!! Form::label('leaveType2', 'Half Day', ['class' => 'form-check-label font-size-13']) !!}
                </div>
                @error('type')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div
                class="col-md-6 form-group leave-status {{ (isset($leave) && $leave->type == 'half') ? '' : 'd-none'}}">
                <label for="billing-name" class="col-form-label d-block">Half Time</label>

                <div class="form-check form-check-inline font-size-16">
                    {!! Form::radio('halfday_status', 'firsthalf', null, ['class' => 'form-check-input', 'id' =>
                    'firsthalf', 'checked' =>'checked']) !!}
                    {!! Form::label('firsthalf', 'First Half', ['class' => 'form-check-label font-size-13']) !!}
                </div>
                <div class="form-check form-check-inline font-size-16">
                    {!! Form::radio('halfday_status', 'secondhalf', null, ['class' => 'form-check-input', 'id' =>
                    'secondhalf']) !!}
                    {!! Form::label('secondhalf', 'Second Half', ['class' => 'form-check-label font-size-13']) !!}
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('reason', 'Reason', ['class' => 'col-form-label']) !!}
        {!! Form::textarea('reason', null, ['class' => 'form-control', 'placeholder' => 'Enter your reason', 'id' =>
        'reason', 'rows' => '4']) !!}
        @error('reason')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

</div>



<div class="row">
    <div class="col-md-3 form-group">
        <label class="col-form-label">Start Date</label>
        <div class="input-group">
            {!! Form::text('start_date', null, ['class' => 'form-control ', 'placeholder' => 'Select Start Date', 'id'
            =>
            'startDate', 'data-date-format' => "dd-mm-yyyy", 'data-provide' => 'datepicker','data-date-autoclose'=>
            "true",'autocomplete' => 'off','data-default' => isset($leave) ? Carbon\Carbon::parse($leave->start_date) :
            '']) !!}
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
        @error('start_date')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3 form-group">
        <label class="col-form-label">End Date</label>
        <div class="input-group">
            {!! Form::text('end_date', null, ['class' => 'form-control ', 'placeholder' => 'Select End Date', 'id' =>
            'endDate', 'data-date-format' => "dd-mm-yyyy", 'data-provide' => 'datepicker','data-date-autoclose'=>
            "true",'autocomplete' => 'off','data-default' => isset($leave) ? Carbon\Carbon::parse($leave->end_date) :
            '']) !!}
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
        @error('end_date')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row">
    <div class="col-md-3 form-group">
        <label class="col-form-label">Total Leave(s)</label>
        <div class="alert alert-secondary leave-day-alert">
            <span class="badge badge-secondary"><span class="fw-600"> {!! Form::text('duration', null, ['class' =>
                    'form-control','readonly', 'id' => 'compDuration']) !!} </span></span> Day(s)
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-center">
        <a href="{{ isset($addTeamLeave) ?  (url()->previous() == route('leave-compensation-all-employee') ? route('leave-compensation-all-employee') : route('leave-compensation-all-employee')) : route('leave-compensation-dashboard') }}"
            class="btn btn-secondary my-3 me-3 text-white">Back</a>
        <button type="submit" class="btn btn-primary my-3">Save</button>
    </div>
</div>
@push('scripts')

<script src="{{ asset('js/modules/leaves.js') }}"></script>
<script type="text/javascript">
    var getAllHolidayDate = "{{ route('holiday-get-all-dates') }}";
    setTimeout(() => {
        jQuery('.select2').select2();
    }, 500);
    $("#startDate").datepicker({atesDisabled: null});
    $("#endDate").datepicker({atesDisabled: null});
</script>

@endpush
