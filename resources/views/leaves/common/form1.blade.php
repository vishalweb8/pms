@extends($theme)

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4>Add Leave</h4>
                {!! Form::open(['route' => 'leave-save', 'id'=> "leaveForm"]) !!}


                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-floating mb-3">

                                {!! Form::text('user_name', null, ['class' => 'form-control', 'placeholder' => "Enter Name"]) !!}
                                {!! Form::label('user_name', 'Name') !!}
                                {{-- <input type="text" class="form-control" name="user_name" id="userName" placeholder="Enter Name" value="">
                                <label for="userName">Name</label> --}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="requestTo" aria-label="Floating label select example" name="request_to" multiple>
                                    <option selected>Select your request to</option>
                                    <option value="1">Ravi Chauhan</option>
                                    <option value="2">Admin</option>
                                </select>
                                <label for="requestTo">Send Request To</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <textarea type="text" class="form-control" name="reason" id="reason" placeholder="Enter your reason" value=""></textarea>
                                <label for="reason">Reason</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="leaveType" aria-label="Floating label select example" name="leave_type">
                                    <option selected>Select your leave type</option>
                                    <option value="full">Full Day</option>
                                    <option value="half">Half Day</option>
                                </select>
                                <label for="requestTo">Send Request To</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="halfdayStatus" aria-label="Floating label select example" name="halfday_status">
                                    <option selected>Select halfday type</option>
                                    <option value="firsthalf">First Half</option>
                                    <option value="secondhalf">Second Half</option>
                                </select>
                                <label for="requestTo">Select Halfday Status</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label>Start Date</label>
                            <div class="input-group" id="startDate">
                                <input type="text" name="start_date" class="form-control" placeholder="Start Date" data-date-format="dd-mm-yyyy" data-date-container='#startDate' data-provide="datepicker">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label>End Date</label>
                            <div class="input-group" id="endDate">
                                <input type="text" name="end_date" class="form-control" placeholder="End Date" data-date-format="dd-mm-yyyy" data-date-container='#endDate' data-provide="datepicker">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="duration" id="duration" placeholder="Enter Duration" value="2">
                                <label for="duration">Leave Duration</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <p>Return Date : 9-sep-2021</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <p>Requested Date : 9-sep-2021</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone Number" value="">
                                <label for="phone">Phone</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="phoneAvailabilty" aria-label="Floating label select example" name="phone_availabilty">
                                    <option selected>Select Availabilty</option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                                <label for="phoneAvailabilty">Availabilty on Phone</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-check-primary mb-3">
                                <input class="form-check-input" type="checkbox" id="adhocLeave" name="adhoc_leave" checked="">
                                <label class="form-check-label" for="adhocLeave">
                                    Is adhoc leave
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="cityAvailabilty" aria-label="Floating label select example" name="city_availabilty">
                                    <option selected>Select Availabilty</option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                                <label for="cityAvailabilty">Availabile on City</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="emergency_contact" id="emergencyContact" placeholder="Enter Emergency Contact Number" value="">
                                <label for="emergencyContact">Emergency Contact Number</label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md" style="float:right; ">Save</button>
                        </div>
                    </div>
                {{-- </form> --}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
