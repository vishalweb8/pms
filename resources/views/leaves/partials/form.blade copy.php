<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    {!! Form::text('user_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name', 'id' => 'userName']) !!}
                    {!! Form::label('userName', 'Name') !!}
                </div>
                <div class="mb-3">
                    <!-- {!! Form::select('request_to', $request_to_list, null, ['class' => 'form-select', 'id' => 'reqeustTo', 'multiple']) !!} -->
                    {!! Form::label('requestTo', 'Send Request To') !!}
                    <select class="select2 form-control select2-multiple"
                        multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Alaskan/Hawaiian Time Zone">
                            <option value="AK">Alaska</option>
                            <option value="HI">Hawaii</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-0">
                    {!! Form::textarea('reason', null, ['class' => 'form-control', 'placeholder' => 'Enter your reason', 'id' => 'reason', 'rows' => '6', 'style' => 'height: auto']) !!}
                    {!! Form::label('reason', 'Reason') !!}
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-floating mb-3">
            <!-- {!! Form::select('leave_type', [], null, ['class' => 'form-select', 'placeholder' => 'Select your leave type' ,'id' => 'leaveType']) !!}
            {!! Form::label('leaveType', 'Select leave type') !!} -->
            <div class="mb-3">
                {!! Form::label('leaveType', 'Select leave type') !!}
                <select class="form-select select2" id="floatingSelectGrid" aria-label="Floating label select example">
                    <option selected>Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <!-- {!! Form::select('halfday_status', ['1' => 'Full Day', '2' => 'Half Day'], null, ['class' => 'form-select', 'placeholder' => 'Select halfday type' ,'id' => 'halfdayStatus']) !!} -->
            {!! Form::label('halfdayStatus', 'Select halfday type', ['class' => 'text-primary']) !!}
            <div class="radio-container d-flex align-items-center">
                <div class="form-check me-2">
                    <input class="form-check-input" type="radio" name="formRadios"
                        id="Half_Day">
                    <label class="form-check-label" for="Half_Day">
                        Full Day
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="formRadios"
                        id="Half_Day">
                    <label class="form-check-label" for="Half_Day">
                        Half Day
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label>Select Leave Date</label>

        <div class="input-daterange input-group" id="datepicker6" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
            <input type="text" class="form-control" name="start" placeholder="Start Date" />
            <input type="text" class="form-control" name="end" placeholder="End Date" />
        </div>
        <div class="">
            <!-- <div class="input-group" id="startDate">
                {!! Form::text('start_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Start Date', 'id' => 'startDate', 'data-date-format' => 'dd-mm-yyyy', 'data-date-container' => '#startDate', 'data-provide' => 'datepicker']) !!}
                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
            </div> -->
            <div class="input-group" id="endDate">
                <div class="form-floating input-group date datepicker" data-provide="datepicker">
                {!! Form::text('start_date', null, ['class' => 'form-control ', 'placeholder' => 'Start Date', 'id' => 'startDate', 'data-date-format' => 'dd-mm-yyyy', 'data-date-container' => '#startDate', 'data-provide' => 'datepicker']) !!}
                    <div class="input-group-addon">
                        <i class="far fa-calendar-alt font-size-15 text-primary"></i>
                    </div>
                    {!! Form::label('start_date', 'start date') !!}
                </div>
            </div>
        </div>
        <div class="">
            <div class="input-group" id="endDate">
                <div class="form-floating input-group date datepicker" data-provide="datepicker">
                    {!! Form::text('end_date', null, ['class' => 'form-control', 'placeholder' => 'End Date', 'id' => 'endDate',
                    'data-date-format' => 'dd-mm-yyyy', 'data-date-container' => '#endDate', 'data-provide' => 'datepicker']) !!}
                    <div class="input-group-addon">
                        <i class="far fa-calendar-alt font-size-15 text-primary"></i>
                    </div>
                    {!! Form::label('end_date', 'end date') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mb-3">
            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Enter Phone', 'id' => 'phone'])!!}
            {!! Form::label('phone', 'Phone') !!}
        </div>
        <div class="mb-3">
            <!-- {!! Form::select('phone_availabilty', ['1' => 'Yes', '2' => 'No'], null, ['class' => 'form-select', 'placeholder' => 'Select halfday type' ,'id' => 'phoneAvailabilty']) !!} -->
            {!! Form::label('phoneAvailabilty', 'Availabilty on Phone', ['class' => 'text-primary']) !!}
            <div class="radio-container d-flex align-items-center">
                <div class="form-check me-2">
                    <input class="form-check-input" type="radio" name="formRadios"
                        id="yes">
                    <label class="form-check-label" for="yes">
                        Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="formRadios"
                        id="No">
                    <label class="form-check-label" for="No">
                        No
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mb-3">
            {!! Form::text('duration', null, ['class' => 'form-control', 'placeholder' => 'Enter Duration', 'disabled' => 'disabled', 'Value' => '0' ,'id' => 'duration']) !!}
            {!! Form::label('duration', 'Leave Duration', ['class' => 'text-primary m-0']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    {!! Form::label('Return_Date', 'Return Date', ['class' => 'text-primary d-block m-0']) !!}
                    <span>15-sept-2021</span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                {!! Form::label('Requested_Date', 'Requested Date', ['class' => 'text-primary d-block m-0']) !!}
                    <span>15-sept-2021</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6  mb-3  d-flex align-items-center">
        <div class="form-check">
            <input class="form-check-input" name="adhoc_leave" type="checkbox" id="adhocLeave">
            <label class="form-check-label" for="adhocLeave">
            Is adhoc leave
            </label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <!-- {!! Form::select('city_availabilty', ['1' => 'Yes', '2' => 'No'], null, ['class' => 'form-select', 'placeholder' =>
            'Select halfday type' ,'id' => 'cityAvailabilty']) !!} -->
            {!! Form::label('cityAvailabilty', 'Availabilty on city', ['class' => 'text-primary']) !!}
            <div class="radio-container d-flex align-items-center">
                <div class="form-check me-2">
                    <input class="form-check-input" type="radio" name="formRadios"
                        id="yes1">
                    <label class="form-check-label" for="yes1">
                        Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="formRadios"
                        id="No1">
                    <label class="form-check-label" for="No1">
                        No
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-3">
            {!! Form::text('emergency_contact', null, ['class' => 'form-control', 'placeholder' => 'Enter Emergency Contact Number', 'id' => 'phone'])!!}
            {!! Form::label('emergencyContact', 'Emergency Contact Number') !!}
        </div>
    </div>

    <div>
        <button type="submit" class="btn btn-primary w-md" style="float:right; ">Save</button>
    </div>
</div>
