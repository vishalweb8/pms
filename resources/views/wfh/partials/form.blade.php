{!! Form::hidden('_url', url()->previous() )!!}
<div class="row">
    <div class="col-md-6">
        <div class="row">
            @isset($teamWfh)
            <div class="form-group mb-3">
                {!! Form::Label('floatingSelectGrid', 'Select Request from employee', ['class' => 'col-form-label']) !!}
                @if(isset($id) && $id != null)
                {!! Form::select('user_id', $requestUsersFrom, null, ['class' => 'form-control select2', 'id' => 'requestWfhFrom', 'disabled']) !!}
                @else
                {!! Form::select('user_id', $requestUsersFrom, null, ['class' => 'form-control select2', 'id' => 'requestWfhFrom']) !!}
                @endif
            </div>
            @endisset
            <div class="col-md-12 form-group">
                {!! Form::hidden('id', null) !!}
                {!! Form::label('reqeustTo', 'Send Request To', ['class' => 'col-form-label']) !!}
                @if(isset($teamWfh))
                 {!! Form::select('request_to[]', $allUsers, null, ['class' => 'form-control select2', 'id' => 'reqeustTo', 'multiple' => 'multiple']) !!}
                {{-- <select class="form-control select2" id="reqeustTo" name="request_to[]" multiple></select> --}}
                @else
                {!! Form::select('request_to[]', $allUsers, (isset($workFromHome)) ? null : array_keys($requestUsers), ['class' => 'form-control select2', 'id' => 'reqeustTo', 'multiple' => 'multiple']) !!}
                {{-- @foreach($requestUsers as $key => $list)
                    <input type="hidden" name="request_to1[]" value="{{ $key }}" id="requestTo1">
                @endforeach --}}
                 {{-- <select class="form-control" name="request_to[]" id="reqeustTo" disabled>
                    @foreach($requestUsers as $key => $list)
                    <option value="{{ $key }}" selected>{{ $list }}</option>
                    @endforeach
                </select>  --}}
                @endif
                @error('request_to')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="billing-name" class="col-form-label d-block">Day Type</label>
                <div class="form-check form-check-inline font-size-16">
                    {!! Form::radio('wfh_type', 'full', null, ['class' => 'form-check-input', 'id' => 'leaveType1','checked'=>'checked']) !!}
                    {!! Form::label('leaveType1', 'Full Day', ['class' => 'form-check-label font-size-13']) !!}
                </div>
                <div class="form-check form-check-inline font-size-16">
                    {!! Form::radio('wfh_type', 'half', null, ['class' => 'form-check-input', 'id' => 'leaveType2']) !!}
                    {!! Form::label('leaveType2', 'Half Day', ['class' => 'form-check-label font-size-13']) !!}
                </div>
                @error('wfh_type')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 form-group leave-status {{ (isset($workFromHome) && $workFromHome->wfh_type == 'half') ? '' : 'd-none'}}">
                <label for="billing-name" class="col-form-label d-block">Request Status</label>

                <div class="form-check form-check-inline font-size-16">
                    {!! Form::radio('halfday_status', 'firsthalf', null, ['class' => 'form-check-input', 'id' => 'firsthalf', 'checked' => 'checked']) !!}
                    {!! Form::label('firsthalf', 'First Half', ['class' => 'form-check-label font-size-13']) !!}
                </div>
                <div class="form-check form-check-inline font-size-16">
                    {!! Form::radio('halfday_status', 'secondhalf', null, ['class' => 'form-check-input', 'id' => 'secondhalf']) !!}
                    {!! Form::label('secondhalf', 'Second Half', ['class' => 'form-check-label font-size-13']) !!}
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('reason', 'Reason', ['class' => 'col-form-label']) !!}
        {!! Form::textarea('reason', null, ['class' => 'form-control', 'placeholder' => 'Enter your reason', 'id' => 'reason', 'rows' => '4']) !!}
        @error('reason')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

</div>
<div class="row">
    <div class="col-md-3 form-group mt-4">
        <div class="form-check form-switch form-switch-md" dir="ltr">
            <label class="col-form-label pe-2" for="is_adhoc">Is Adhoc WFH</label>
            <input class="form-check-input" type="checkbox" name="is_adhoc" id="is_adhoc" {{ (isset($workFromHome) &&
                $workFromHome->is_adhoc == 1) ? 'checked' : '' }}>
        </div>
    </div>

    <div class="col-md-3 form-group">
        <div class="adhoc-status {{ (isset($workFromHome) && $workFromHome->is_adhoc == 1) ? '' : 'd-none'}}">
            {!! Form::label('adhocStatus', 'Select Adhoc Status', ['class' => 'col-form-label']) !!}
            {!! Form::select('adhoc_status', ['team_member' => 'Team member', 'directly' => 'Directly', 'not_inform' =>
            'Not
            Inform'], null, ['class' => 'form-control select2',
            'id' => 'adhocStatus']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 form-group">
        <label class="col-form-label">Start Date</label>
        <div class="input-group">
            {!! Form::text('start_date', null, ['class' => 'form-control ', 'placeholder' => 'Select Start Date', 'id' =>
            'startDate', 'data-date-format' => "dd-mm-yyyy", 'data-provide' => 'datepicker','data-date-autoclose'=>
            "true",'autocomplete' => 'off','data-default' => isset($workFromHome) ? Carbon\Carbon::parse($workFromHome->start_date) : '']) !!}
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
            "true",'autocomplete' => 'off','data-default' => isset($workFromHome) ? Carbon\Carbon::parse($workFromHome->end_date) : '']) !!}
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
        @error('end_date')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 form-group">
        <label class="col-form-label">Total Day(s) of WFH</label>
        <div class="alert alert-secondary leave-day-alert">
            <span class="badge badge-secondary"><span class="fw-600"> {!! Form::text('duration', null, ['class' => 'form-control','readonly', 'id' => 'duration']) !!} </span></span> Day(s)
        </div>
    </div>


    <div class="col-md-3 form-group">
        {!! Form::label('returnDate', 'Return Date', ['class' => 'col-form-label']) !!}
        {!! Form::text('return_date', null, ['class' => 'form-control', 'readonly', 'id' => 'returnDate']) !!}
    </div>

</div>



<div class="row">
    <div class="col-md-12 text-center">
        <a href="{{ isset($teamWfh) ?  (url()->previous() == route('wfh-all-emp-index') ? route('wfh-all-emp-index') : route('wfh-team')) : route('wfh-dashboard') }}" class="btn btn-secondary my-3 me-3 text-white">Back</a>
        <button type="submit" class="btn btn-primary my-3">Save</button>
    </div>
</div>
@push('scripts')
<script>
    var getRequestFrom = "{{ route('wfh-get-request-from') }}";
    var getAllHolidayDate = "{{ route('holiday-get-all-dates') }}";
</script>
<script src="{{ asset('js/modules/leaves.js') }}"></script>
@if(Auth::user()->roles()->whereIn('code', ['ADMIN', "PM"])->count()) {
<script type="text/javascript">
    $("#startDate,#endDate").datepicker("setStartDate", "2022-01-31");
</script>
@endif
@endpush
