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
                @if(isset($addTeamLeave))
                {!! Form::select('request_to[]', $allUsers, null, ['class' =>
                'form-control select2', 'id' => 'reqeustTo', 'multiple' => 'multiple']) !!}
                {{-- <select class="form-control select2" id="requestTo" name="request_to[]" multiple></select> --}}
                @else
                {!! Form::select('request_to[]', $allUsers, (isset($leave) ? null : array_keys($requestUsers)),
                ['class' =>
                'form-control select2', 'id' => 'reqeustTo', 'multiple' => 'multiple']) !!}
                @endif
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
    <div class="col-md-3 form-group mt-4">
        <div class="form-check form-switch form-switch-md" dir="ltr">
            <label class="col-form-label pe-2" for="isadhoc_leave">Is Adhoc Leave</label>
            <input class="form-check-input" type="checkbox" name="isadhoc_leave" id="adhocLeave" {{ (isset($leave) &&
                $leave->isadhoc_leave == 1) ? 'checked' : '' }}>
        </div>
    </div>

    <div class="col-md-3 form-group">
        <div class="adhoc-status {{ (isset($leave) && $leave->isadhoc_leave == 1) ? '' : 'd-none'}}">
            {!! Form::label('adhocStatus', 'Select adhoc status', ['class' => 'col-form-label']) !!}
            {!! Form::select('adhoc_status', ['teammember' => 'Team member', 'directly' => 'Directly', 'notinform' =>
            'Not
            Inform'], null, ['class' => 'form-control select2',
            'id' => 'adhocStatus']) !!}
        </div>
    </div>

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
                    'form-control','readonly', 'id' => 'duration']) !!} </span></span> Day(s)
        </div>
    </div>

    <div class="col-md-3 form-group">
        {!! Form::label('returnDate', 'Return Date', ['class' => 'col-form-label']) !!}
        {!! Form::text('return_date', null, ['class' => 'form-control', 'readonly', 'id' => 'returnDate']) !!}
    </div>

    <div class="col-md-3 form-group mt-4">
        <div class="form-check form-switch form-switch-md" dir="ltr">
            <label class="col-form-label pe-2" for="available_on_phone">Availability On Phone</label>
            <input class="form-check-input" type="checkbox" name="available_on_phone" id="phoneAvailabilty" {{
                (isset($leave) && $leave->available_on_phone == 1) ? 'checked' : '' }}>
        </div>
        @error('available_on_phone')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 form-group mt-4">
        <div class="form-check form-switch form-switch-md" dir="ltr">
            <label class="col-form-label pe-2" for="available_on_city">Availability In City</label>
            <input class="form-check-input" type="checkbox" name="available_on_city" id="cityAvailabilty" {{
                (isset($leave) && $leave->available_on_city == 1) ? 'checked' : '' }}>
        </div>
        @error('available_on_city')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>
</div>


<div class="row">
    <div class="col-md-3 form-group">
        {!! Form::label('emergencyContact', 'Emergency Contact Number', ['class' => 'col-form-label']) !!}
        {!! Form::text('emergency_contact', null, ['class' => 'form-control', 'id' => 'emergencyContact']) !!}
        @error('emergency_contact')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row">
    <div class="col-md-12 text-center">
        <a href="{{ isset($addTeamLeave) ?  (url()->previous() == route('leave-all-employee') ? route('leave-all-employee') : route('leave-team')) : route('leave-dashboard') }}"
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
</script>

@endpush
