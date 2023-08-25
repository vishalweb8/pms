<div id="personal_details">
    <div class="card card-user-profile-info">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('first_name', 'First Name', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('first_name', null, ['class' => 'form-control', 'id'=> "first_name",
                        'placeholder'=>"Enter First Name",'autocomplete' => "firstname"]) !!}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('last_name', 'Last Name', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'id'=> "last_name",
                        'placeholder'=> "Enter Last Name",'autocomplete' => "lastname"]) !!}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3">
                        {!! Form::label('gender', 'Gender', ['class' => 'col-form-label pt-0']) !!}
                        <div class="radio-container d-flex align-items-center flex-wrap">
                            {!! Form::radio('gender', 'male', null, ['class' => 'form-check-input mt-0 me-2','id' =>
                            'male']) !!}
                            {!! Form::label('male', 'Male', ['class' => 'form-check-label me-2']) !!}

                            {!! Form::radio('gender', 'female', null, ['class' => 'form-check-input mt-0 me-2','id' =>
                            'female']) !!}
                            {!! Form::label('female', 'Female', ['class' => 'form-check-label me-2']) !!}
                        </div>
                    </div>
                </div>
                @if(Auth::user()->isManagement())
                @php
                    $hide = '';
                    if(request()->route()->getName() == 'user.profile.edit' && (!in_array(\Auth::user()->userRole->code, ['ADMIN', 'HRA']))) {
                        $hide = 'hide';
                    }
                @endphp
                <div class="col-md-6 col-xxl-4  {{$hide}}">
                    <div class="mb-3">
                        {!! Form::label('designation_id', 'Select User Designation', ['class' => 'col-form-label pt-0'])
                        !!}
                        {!! Form::select('designation_id', $userDesignations, (isset($userOfficialDetails) ?
                        $userOfficialDetails->designation_id: null), ['class' => 'form-control form-select2', 'id'=>
                        "designation_id"]) !!}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4 {{$hide}}">
                    <div class="mb-3">
                        {!! Form::label('role_id', 'Select Role', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::select('role_id', $employeeRoles, null, ['class' => 'form-control form-select2',
                        'id'=> "role_id"]) !!}
                    </div>
                </div>
                @endif
                <div class="col-md-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('user_name', 'User Name', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('user_name', null, ['class' => 'form-control', 'id'=> "user_name",
                        'placeholder'=> "Enter User Name",'autocomplete' => "lastname"]) !!}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('personal_email', 'Personal Email', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::email('personal_email', null, ['class' => 'form-control', 'id'=> "personal_email",
                        'placeholder'=> "Enter personal Email",'autocomplete' => "email"]) !!}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('email', 'Email', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::email('email', null, ['class' => 'form-control', 'id'=> "email", 'placeholder'=>
                        "Enter Email",'autocomplete' => "email",'readonly' => (request()->route()->getName() == 'user.profile.edit') ? true : false]) !!}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="form-group mb-3">
                        <div class="d-flex align-items-center justify-content-start mb-2">
                            {!! Form::label('password', 'Password', ['class' => 'col-form-label p-0']) !!}
                            <div class="info cursor-pointer ms-2"
                                title="Password must be 8 characters long and contain special character, lowercase, capital letters & numbers">
                                <i class="fa fa-info-circle"></i>
                            </div>
                        </div>
                        <div class="input-group auth-pass-inputgroup">
                            {!! Form::password('password', ['class' => 'form-control', 'id'=> "password",
                            'autocomplete'=>'new-password', 'onblur'=>"this.setAttribute('readonly',
                            'readonly');",'readonly
                            onfocus'=>"this.removeAttribute('readonly');",'readonly','placeholder'=> "Enter Password"])
                            !!}
                            <a class="btn btn-light password-icon-class" type="button" id="confirm-password-addon">
                                <i class="mdi mdi-eye-outline"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3">
                        @php
                        $birthdate = $wedding = null;
                        if(isset($user)) {
                        $birthdate = $user->birth_date;
                        $wedding = $user->wedding_anniversary;
                        }
                        @endphp
                        {!! Form::label('birth_date', 'Birth Date', ['class' => 'col-form-label pt-0']) !!}
                        <div class="input-group">
                            {!! Form::text('birth_date', $birthdate, ['class' => 'form-control ', 'placeholder' =>
                            'Select Birth Date', 'id' =>
                            'birthdate', 'data-date-format' => "yyyy-mm-dd", 'data-provide' =>
                            'datepicker','data-date-autoclose'=> "true"]) !!}
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3">
                        {!! Form::Label('marital_status', 'Marital Status', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::select('marital_status', array('' => 'Select Marital Status') +
                        config('constant.marital_status'), null, ['class' => 'form-control form-select2', 'id' =>
                        'maritalStatus']) !!}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3">
                        {!! Form::label('wedding_anniversary', 'Wedding Anniversary', ['class' => 'col-form-label
                        pt-0']) !!}
                        <div class="input-group">
                            {!! Form::text('wedding_anniversary', $wedding, ['class' => 'form-control ', 'placeholder'
                            => 'Select Wedding Anniversary', 'id' =>
                            'wedding_date_input', 'data-date-format' => "yyyy-mm-dd", 'data-provide' =>
                            'datepicker','data-date-autoclose'=> "true","disabled" => "disabled","readonly" =>
                            "readonly"]) !!}
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3">
                        {!! Form::Label('blood_group', 'Blood Group', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::select('blood_group', array('' => 'Select Blood Group') +
                        config('constant.blood_groups'), null,['class' => 'form-control form-select2']) !!}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('phone_number', 'Phone Number', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('phone_number', null, ['class' => 'form-control', 'id'=> "phone_number",
                        'placeholder'=> "Enter Phone Number"]) !!}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="form-group mb-3">
                        {!! Form::label('emergency_number', 'Emergency Number', ['class' => 'col-form-label pt-0']) !!}
                        {!! Form::text('emergency_number', null, ['class' => 'form-control', 'id'=> "emergency_number",
                        'placeholder'=> "Enter Emergency Number"]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-user-profile-info">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="drop-files">
                        {!! Form::label('upLoadProfilePhoto', 'Upload Profile Photo', ['class' => 'col-form-label']) !!}
                        <div class="file-upload-wrapper">
                            <x-fileUpload allowedFiles=true preview=true fileSize="10 MB">
                                <i class="mdi mdi-file me-2"></i>
                            </x-fileUpload>
                            <div class="img-wrapper">
                                @if (!empty($user) && !empty($user->profile_image) &&
                                file_exists(public_path('storage/upload/user/images/'.$user->profile_image)))
                                <img class="profile-user-img img-fluid"
                                    src="{{asset('storage/upload/user/images')}}/{{$user->profile_image}}">
                                @else
                                <img class="profile-user-img img-fluid" src="{{ asset('images/no-preview.png') }}"
                                    alt="Preview">
                                @endif
                                @if(!empty($user) && !empty($user->profile_image))
                                <button class="btn mdi mdi-trash-can-outline photo-remove" type="button"
                                    onclick="removeEmployeePreview()"></button>
                                @else
                                <button class="btn mdi mdi-trash-can-outline photo-remove" type="button"
                                    onclick="removeEmployeePreview()" style="display: none"></button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4 mb-md-0">
            <div class="card card-user-profile-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6 class="text-primary mb-3">Current Address</h6>
                            <div class="form-group mb-3">
                                {!! Form::label('temp_address1', 'Address', ['class' => 'col-form-label pt-0']) !!}
                                {!! Form::textarea('temp_address1', null, ['class' => 'form-control', 'rows'=> '3',
                                'id'=> "temp_address1", 'placeholder'=>
                                "Enter Current Address"]) !!}
                            </div>
                            <div class="mb-3">
                                {!! Form::Label('temp_contry', 'Country', ['class' => 'col-form-label pt-0']) !!}
                                {!! Form::select('temp_contry', $countries , $user->temp_contry ?? old('temp_country',101), ['class' => 'form-control
                                form-select2', 'id'=> "temp_country"]) !!}
                            </div>
                            <div class="mb-3">
                                {!! Form::Label('temp_state', 'State', ['class' => 'col-form-label pt-0']) !!}
                                {!! Form::select('temp_state', $tempStates ?? $states , $user->temp_state ?? old('temp_state',1339), ['class' => 'form-control form-select2',
                                'id'=> "temp_state"]) !!}
                            </div>
                            <div class="mb-3">
                                {!! Form::Label('temp_city', 'City', ['class' => 'col-form-label pt-0']) !!}
                                {!! Form::select('temp_city', $tempCities ?? $cities , $user->temp_city ?? old('temp_city',131575), ['class' => 'form-control form-select2',
                                'id'=> "temp_city"]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4 mb-md-0">
            <div class="card card-usre-profile-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="text-primary">Permanent Address</h6>
                                <div class="form-check form-check d-inline-block ms-3">
                                    <input class="form-check-input" name="same_address" type="checkbox"
                                        id="same_address" onclick="fillAddress($(this))">
                                    <label class="form-check-label" for="same_address">
                                        Same as current address
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                {!! Form::label('address1', 'Address', ['class' => 'col-form-label pt-0']) !!}
                                {!! Form::textarea('address1', null, ['class' => 'form-control', 'rows'=> '3', 'id'=>
                                "address1", 'placeholder'=>
                                "Enter Permanent Address"]) !!}
                            </div>
                            <div class="mb-3">
                                {!! Form::Label('contry', 'Country', ['class' => 'col-form-label pt-0']) !!}
                                {!! Form::select('contry', $countries , $user->county ?? old('country',101), ['class' => 'form-control form-select2',
                                'id'=> "country"]) !!}
                            </div>
                            <div class="mb-3">
                                {!! Form::Label('state', 'State', ['class' => 'col-form-label pt-0']) !!}
                                {!! Form::select('state', $states , $user->state ?? old('state',1339), ['class' => 'form-control form-select2',
                                'id'=> "state"]) !!}
                            </div>
                            <div class="mb-3">
                                {!! Form::Label('city', 'City', ['class' => 'col-form-label pt-0']) !!}
                                {!! Form::select('city', $cities , $user->city ?? old('city',131575), ['class' => 'form-control form-select2', 'id'=>
                                "city"]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="user_id" class="user_id" value="{{ $user->id ?? '' }}" />
