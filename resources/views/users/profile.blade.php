@extends($theme)
@include('common.head_portion')
@section('breadcrumbs')
{{ Breadcrumbs::render('profile') }}
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="profile-details">
            <div class="">
                <div class="profile-view">
                    <div class="card ">
                        <div class="card-body">
                            {!! Form::open(['id' => 'profile-upload-form', 'method' => 'post', 'enctype' =>
                            'multipart/form-data']) !!}
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <span>
                                        <i class="bx bx-camera"></i>
                                    </span>
                                    <input accept="image/*" onchange="profileDisplayPreview(this)"
                                        id="contained-button-file" type="file" name="userImage" />
                                    @if($user->profile_image)
                                    <img class="preview-img"
                                        src="{{asset('storage/upload/user/images')}}/{{$user->profile_image}}" />
                                    @else
                                    <img class="preview-img" src="{{url('images/inexture-logo-icon.svg')}}" />
                                    @endif
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <div class="profile-info-left">
                                            <h5 class="user-name m-t-0 mb-1">{{$user->full_name}}</h5>
                                            <span class="mb-3 badge bg-gradient shine user-designation">{{($user->officialUser
                                                && $user->officialUser->userDesignation) ?
                                                $user->officialUser->userDesignation->name : ''}}</span>
                                            <p class="mb-0">
                                                {{($user->officialUser) ? (($user->officialUser->userTeam) ?
                                                $user->officialUser->userTeam->name : '') : ''}}
                                            </p>
                                            <p class="staff-id mb-0">Employee ID :
                                                {{ ($user->officialUser) ? $user->officialUser->emp_code : '-'}}
                                            </p>
                                            <p class="doj b-0">Date of Join :
                                                {{($user->officialUser)? $user->officialUser->joining_date : '-'}}
                                            </p>
                                            <div class="text-left mt-4 profile-action-btn">
                                                <button data-bs-toggle="modal" type="submit"
                                                    class="btn btn-primary saveButtonHide mb-1"
                                                    style="display: none">Save
                                                    Profile</button>
                                                {!! Form::close() !!}
                                                <button data-bs-toggle="modal" data-bs-target=".change-password-modal"
                                                    class="btn btn-primary mb-1" type="button">Change Password</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <ul class="personal-info">
                                            <li>
                                                <div class="title">Phone:</div>
                                                <div class="text"><a href="">{{($user->phone_number) ?
                                                        $user->phone_number: '-' }}</a>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">Email:</div>
                                                <div class="text text-lowercase"><a href="">{{($user->email) ?
                                                        $user->email: '-' }}</a></div>
                                            </li>
                                            <li>
                                                <div class="title">Birthday:</div>
                                                <div class="text">{{($user->birth_date) ? $user->birth_date : '-' }}
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">Address:</div>
                                                <div class="text">{{($user->full_address) ? $user->full_address : '-' }}
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">Gender:</div>
                                                <div class="text">{{($user->gender) ? ucfirst($user->gender) : '-' }}
                                                </div>
                                            </li>
                                            <li>
                                                {{-- @dd($user->officialUser->reporting_ids); --}}
                                                <div class="title">Reports to:</div>
                                                <div class="text d-flex">
                                                    <div class="text fw-bold">
                                                        {{ ($user->reportings) ? implode(', ',$user->reportings) : '-'}}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                {{-- @dd($user->officialUser->reporting_ids); --}}
                                                <div class="title">My Mentor:</div>
                                                <div class="text d-flex">
                                                    <div class="text fw-bold">
                                                        {{ ($user->mentors) ? implode(', ',$user->mentors) : '-'}}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                {{-- @dd($user->officialUser->reporting_ids); --}}
                                                <div class="title">My Members:</div>
                                                <div class="text d-flex">
                                                    <div class="text fw-bold">
                                                        {{ ($user->members) ? implode(', ',$user->members) : '-'}}
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="pro-edit"></div>
                        </div>
                    </div>

                    <!-- Tab panes -->
                    <div class="tab-content py-3 text-muted">

                        <div class="tab-pane active" id="profile1" role="tabpanel">
                            <div class="row">
                                {{-- Personal Information --}}
                                @if (!empty($user))
                                <div class="col-md-6 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="card-title">Personal Information
                                                 <a href="#" class="edit-icon add-btn"
                                                    data-url="{{route('user.profile.edit')}}" data-bs-toggle="modal"
                                                data-bs-target=".emergency-contact-modal"><i
                                                    class="bx bx-pencil"></i></a>
                                            </h5>
                                            <ul class="personal-info">
                                                <li>
                                                    <div class="title">Blood Group</div>
                                                    <div class="text">{{($user->blood_group) ? $user->blood_group :
                                                        '-'}}</div>
                                                </li>
                                                <li>
                                                    <div class="title">Personal Email</div>
                                                    <div class="text">
                                                        {{($user->personal_email) ? $user->personal_email : '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Marital status</div>
                                                    <div class="text">
                                                        {{($user->marital_status) ? ucfirst($user->marital_status) :
                                                        '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Wedding Anniversary</div>
                                                    <div class="text">
                                                        {{($user->wedding_anniversary) ? $user->wedding_anniversary :
                                                        '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Emergency Number</div>
                                                    <div class="text">
                                                        {{($user->emergency_number) ? $user->emergency_number : '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Current Address</div>
                                                    <div class="text">{{ $user->getTempFullAddressAttribute() }}</div>
                                                </li>
                                                <li>
                                                    <div class="title">Permanent Address</div>
                                                    @php
                                                    $country = ($user->contry) ? $user->country->name : '';
                                                    $state = ($user->state) ? $user->states->name : '';
                                                    $city = ($user->city) ? $user->cities->name : '';
                                                    @endphp
                                                    <div class="text">
                                                        {{(implode(",
                                                        ",array_filter([$user->address1,$user->address2,$city,$state,$country,$user->zipcode])))
                                                        ? implode(",
                                                        ",array_filter([$user->address1,$user->address2,$city,$state,$country,$user->zipcode]))
                                                        : '-' }}
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                {{-- Official Information --}}
                                <div class="col-md-6 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="card-title">Official Information
                                                <a href="#" class="edit-icon add-btn"
                                                data-url="{{route('user.profile.edit.office')}}" data-bs-toggle="modal"
                                                data-bs-target=".emergency-contact-modal"><i
                                                class="bx bx-pencil"></i></a>
                                            </h5>
                                            @if (isset($user->officialUser) && $user->officialUser->count())
                                            <ul class="personal-info">
                                                <li>
                                                    <div class="title">Joining Date</div>
                                                    <div class="text">
                                                        {{($user->officialUser) ? (($user->officialUser->joining_date)
                                                        ?$user->officialUser->joining_date : '-' ) : '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Confirmation Date</div>
                                                    <div class="text">
                                                        {{($user->officialUser) ?
                                                        (($user->officialUser->confirmation_date)
                                                        ?$user->officialUser->confirmation_date : '-' ) : '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Task Entry Start Date</div>
                                                    <div class="text">
                                                        {{($user->officialUser) ?
                                                        (($user->officialUser->task_entry_date)
                                                        ?$user->officialUser->task_entry_date : '-' ) : '-'}}
                                                    </div>
                                                </li>
                                                {{-- <li>
                                                    <div class="title">Offered CTC</div>
                                                    <div class="text">
                                                        {{($user->officialUser) ? ($user->officialUser->offered_ctc ?
                                                        $user->officialUser->offered_ctc : '-') :
                                                        '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Current CTC</div>
                                                    <div class="text">
                                                        {{($user->officialUser)? ($user->officialUser->current_ctc ?
                                                        $user->officialUser->current_ctc : '-') :
                                                        '-'}}
                                                    </div>
                                                </li> --}}
                                                <li>
                                                    <div class="title">Experience</div>
                                                    <div class="text">
                                                        <?php
                                                            $exp_data = Helper::getExperience($user);

                                                        ?>
                                                        {{ __("{$exp_data['year']} year(s) and {$exp_data['month']} month(s)") }}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Skype ID</div>
                                                    <div class="text">
                                                        {{($user->officialUser) ? (($user->officialUser->skype_id)
                                                        ?$user->officialUser->skype_id : '-' ) : '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Company Email ID</div>
                                                    <div class="text">
                                                        {{($user->officialUser) ?
                                                        (($user->officialUser->company_email_id)
                                                        ?$user->officialUser->company_email_id : '-' ) : '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Company Gmail ID</div>
                                                    <div class="text">
                                                        {{($user->officialUser) ?
                                                        (($user->officialUser->company_gmail_id)
                                                        ?$user->officialUser->company_gmail_id : '-' ) : '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Company Gitlab ID</div>
                                                    <div class="text">
                                                        {{($user->officialUser) ?
                                                        (($user->officialUser->company_gitlab_id)
                                                        ?$user->officialUser->company_gitlab_id : '-' ) : '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Company Github ID</div>
                                                    <div class="text">
                                                        {{($user->officialUser) ?
                                                        (($user->officialUser->company_github_id)
                                                        ?$user->officialUser->company_github_id : '-' ) : '-'}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Technologies</div>
                                                    <div class="text">
                                                        {{ ($user->technologies) ? implode(', ',$user->technologies) :
                                                        '-'}}
                                                    </div>
                                                </li>
                                            </ul>
                                            @else
                                            @include('common.data-not-available')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- Bank information --}}
                                <div class="col-md-6 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="card-title">Bank Information
                                                <a href="#" class="edit-icon add-btn"
                                                data-url="{{route('user.profile.edit.bank')}}"
                                                data-bs-toggle="modal" data-bs-target=".emergency-contact-modal"><i
                                                class="bx bx-pencil"></i></a>
                                            </h5>
                                            @if ($user->userBank)
                                                <h6 class="section-title">Contact Information</h6>
                                                <ul class="personal-info">
                                                    <li>
                                                        <div class="title">Name as per  Aadhar card</div>
                                                        <div class="text">
                                                            {{ $user->userBank->aadharcard_name ?? '-'}}
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Aadhar No.</div>
                                                        <div class="text">
                                                            {{ $user->userBank->aadharcard_number ?? '-'}}
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">PAN No.</div>
                                                        <div class="text">
                                                            {{ $user->userBank->pan_number ?? '-'}}
                                                        </div>
                                                    </li>
                                                </ul>
                                                @if ($user->userBank->personal_bank_name != null ||
                                                $user->userBank->personal_account_number != null ||
                                                $user->userBank->personal_bank_ifsc_code != null)
                                                <hr>
                                                <h6 class="section-title">Personal Bank Information</h6>
                                                <ul class="personal-info">
                                                    <li>
                                                        <div class="title">Bank name</div>
                                                        <div class="text">
                                                            {{($user->userBank) ? ($user->userBank->personal_bank_name !=
                                                            null ? $user->userBank->personal_bank_name : '-') :
                                                            '-'}}
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Bank account No.</div>
                                                        <div class="text">
                                                            {{($user->userBank) ? ($user->userBank->personal_account_number
                                                            != null ? $user->userBank->personal_account_number : '-') :
                                                            '-'}}
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">IFSC Code</div>
                                                        <div class="text">
                                                            {{($user->userBank) ? ($user->userBank->personal_bank_ifsc_code
                                                            != null ? $user->userBank->personal_bank_ifsc_code : '-') :
                                                            '-'}}
                                                        </div>
                                                    </li>
                                                </ul>
                                                @endif
                                                @if ($user->userBank->salary_bank_name != null ||
                                                $user->userBank->salary_account_number != null ||
                                                $user->userBank->salary_bank_ifsc_code != null)
                                                <hr>
                                                <h6 class="section-title">Salary Bank Information</h6>
                                                <ul class="personal-info">
                                                    <li>
                                                        <div class="title">Bank name</div>
                                                        <div class="text">
                                                            {{($user->userBank) ? ($user->userBank->salary_bank_name != null
                                                            ? $user->userBank->salary_bank_name : '-') : '-'}}
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Bank account No.</div>
                                                        <div class="text">
                                                            {{($user->userBank) ? ($user->userBank->salary_account_number !=
                                                            null ? $user->userBank->salary_account_number : '-') :
                                                            '-'}}
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">IFSC Code</div>
                                                        <div class="text">
                                                            {{($user->userBank) ? ($user->userBank->salary_bank_ifsc_code !=
                                                            null ? $user->userBank->salary_bank_ifsc_code : '-') :
                                                            '-'}}
                                                        </div>
                                                    </li>
                                                </ul>
                                                @endif
                                            @else
                                                @include('common.data-not-available')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- Education Information --}}
                                <div class="col-md-6 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="card-title">Education Information
                                                <a href="#" class="edit-icon add-btn"
                                                    data-url="{{route('user.profile.edit.education')}}"
                                                data-bs-toggle="modal" data-bs-target=".emergency-contact-modal"><i
                                                    class="bx bx-pencil"></i></a>
                                            </h5>
                                            <div class="experience-box">
                                                @forelse ($user->userEducation as $k => $v)
                                                <ul class="experience-list">
                                                    <li>
                                                        <div class="experience-user">
                                                            <div class="before-circle"></div>
                                                        </div>
                                                        <div class="experience-content">
                                                            <div class="timeline-content">
                                                                <a href="#/" class="name">{{$v->university_board}}</a>
                                                                <div>
                                                                    {{config('constant.qualification.'.$v->qualification)}}
                                                                </div>
                                                                <span class="time">Passing year -
                                                                    {{$v->passing_year }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                @empty
                                                @include('common.data-not-available')
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Experience Information --}}
                                <div class="col-md-6 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="card-title">Experience Information
                                                <a href="#" class="edit-icon add-btn"
                                                    data-url="{{route('user.profile.edit.experience')}}"
                                                data-bs-toggle="modal" data-bs-target=".emergency-contact-modal"><i
                                                    class="bx bx-pencil"></i></a>
                                            </h5>
                                            @forelse ($user->userExperience as $k => $v)
                                            <ul class="personal-info">
                                                <li>
                                                    <div class="title">Previous Company Name</div>
                                                    <div class="text">{{$v->previous_company}}</div>
                                                </li>
                                                <li>
                                                    <div class="title">Joined Date</div>
                                                    <div class="text">{{$v->joined_date}}</div>
                                                </li>
                                                <li>
                                                    <div class="title">Released Date </div>
                                                    <div class="text">{{$v->released_date}}</div>
                                                </li>
                                                <li>
                                                    <div class="title">Designation </div>
                                                    <div class="text">
                                                        {{($v->userDesignation) ? $v->userDesignation->name : '-' }}
                                                    </div>
                                                </li>
                                            </ul>
                                            @if($user->userExperience->count()-1 != $k )
                                            <hr>
                                            @endif
                                            @empty
                                            @include('common.data-not-available')
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                {{-- Family Information --}}
                                <div class="col-md-6 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="card-title">Family Information
                                                <a href="#" class="edit-icon add-btn"
                                                    data-url="{{route('user.profile.edit.family')}}"
                                                data-bs-toggle="modal" data-bs-target=".emergency-contact-modal"><i
                                                    class="bx bx-pencil"></i></a>
                                            </h5>
                                            @forelse ($user->userFamily as $k => $v)
                                            <ul class="personal-info">
                                                <li>
                                                    <div class="title">Name</div>
                                                    <div class="text">{{$v->name}}</div>
                                                </li>
                                                <li>
                                                    <div class="title">Relationship</div>
                                                    <div class="text">{{config('constant.relations.'.$v->relation)}}
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Phone </div>
                                                    <div class="text">{{$v->contact_number}}</div>
                                                </li>
                                            </ul>
                                            @if($user->userFamily->count()-1 != $k )
                                            <hr>
                                            @endif
                                            @empty
                                            @include('common.data-not-available')
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane profile-project-outer" id="messages1" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="project-title"><a href="#">Office Management</a></h5>
                                            <small class="block text-ellipsis m-b-15">
                                                <span class="fw-bold">1</span> <span class="text-muted">open tasks,
                                                </span>
                                                <span class="fw-bold">9</span> <span class="text-muted">tasks
                                                    completed</span>
                                            </small>
                                            <p class="text-muted mt-3">Lorem Ipsum is simply dummy text of the printing
                                                and
                                                typesetting industry. When an unknown printer took a galley of type and
                                                scrambled it...
                                            </p>
                                            <div class="pro-deadline">
                                                <label class="col-form-label"> Deadline:</label>
                                                <div class="text-muted">
                                                    17 Apr 2019
                                                </div>
                                            </div>
                                            <div class="project-members m-b-15">
                                                <label class="col-form-label">Project Leader :</label>
                                                <div class="avatar-group">
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-1.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="project-members m-b-15">
                                                <label class="col-form-label"> Team :</label>
                                                <div class="avatar-group">
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-4.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-5.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <div class="avatar-xs">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-success text-white font-size-16">
                                                                    A
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-2.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-1 d-flex justify-content-between">Progress <span
                                                    class="text-success">40%</span></p>
                                            <div class="progress progress-xs mb-0">
                                                <div style="width: 40%" class="progress-bar bg-success"
                                                    data-original-title="40%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end-card--->
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="project-title"><a href="#">Life Ray - SSO</a></h5>
                                            <small class="block text-ellipsis m-b-15">
                                                <span class="fw-bold">1</span> <span class="text-muted">open tasks,
                                                </span>
                                                <span class="fw-bold">9</span> <span class="text-muted">tasks
                                                    completed</span>
                                            </small>
                                            <p class="text-muted mt-3">Lorem Ipsum is simply dummy text of the printing
                                                and
                                                typesetting industry. When an unknown printer took a galley of type and
                                                scrambled it...
                                            </p>
                                            <div class="pro-deadline">
                                                <label class="col-form-label"> Deadline:</label>
                                                <div class="text-muted">
                                                    17 Apr 2019
                                                </div>
                                            </div>
                                            <div class="project-members m-b-15">
                                                <label class="col-form-label">Project Leader :</label>
                                                <div class="avatar-group">
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-1.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="project-members m-b-15">
                                                <label class="col-form-label"> Team :</label>
                                                <div class="avatar-group">
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-4.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-5.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <div class="avatar-xs">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-success text-white font-size-16">
                                                                    A
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-2.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-1 d-flex justify-content-between">Progress <span
                                                    class="text-success">40%</span></p>
                                            <div class="progress progress-xs mb-0">
                                                <div style="width: 40%" class="progress-bar bg-success"
                                                    data-original-title="40%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end-card--->
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="project-title"><a href="#">Kob Games</a></h5>
                                            <small class="block text-ellipsis m-b-15">
                                                <span class="fw-bold">1</span> <span class="text-muted">open tasks,
                                                </span>
                                                <span class="fw-bold">9</span> <span class="text-muted">tasks
                                                    completed</span>
                                            </small>
                                            <p class="text-muted mt-3">Lorem Ipsum is simply dummy text of the printing
                                                and
                                                typesetting industry. When an unknown printer took a galley of type and
                                                scrambled it...
                                            </p>
                                            <div class="pro-deadline">
                                                <label class="col-form-label"> Deadline:</label>
                                                <div class="text-muted">
                                                    17 Apr 2019
                                                </div>
                                            </div>
                                            <div class="project-members m-b-15">
                                                <label class="col-form-label">Project Leader :</label>
                                                <div class="avatar-group">
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-1.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="project-members m-b-15">
                                                <label class="col-form-label"> Team :</label>
                                                <div class="avatar-group">
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-4.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-5.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <div class="avatar-xs">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-success text-white font-size-16">
                                                                    A
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-2.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-1 d-flex justify-content-between">Progress <span
                                                    class="text-success">40%</span></p>
                                            <div class="progress progress-xs mb-0">
                                                <div style="width: 40%" class="progress-bar bg-success"
                                                    data-original-title="40%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end-card--->
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3 d-flex mb-3">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h5 class="project-title"><a href="#">Helpr Admin</a></h5>
                                            <small class="block text-ellipsis m-b-15">
                                                <span class="fw-bold">1</span> <span class="text-muted">open tasks,
                                                </span>
                                                <span class="fw-bold">9</span> <span class="text-muted">tasks
                                                    completed</span>
                                            </small>
                                            <p class="text-muted mt-3">Lorem Ipsum is simply dummy text of the printing
                                                and
                                                typesetting industry. When an unknown printer took a galley of type and
                                                scrambled it...
                                            </p>
                                            <div class="pro-deadline">
                                                <label class="col-form-label"> Deadline:</label>
                                                <div class="text-muted">
                                                    17 Apr 2019
                                                </div>
                                            </div>
                                            <div class="project-members m-b-15">
                                                <label class="col-form-label">Project Leader :</label>
                                                <div class="avatar-group">
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-1.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="project-members m-b-15">
                                                <label class="col-form-label"> Team :</label>
                                                <div class="avatar-group">
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-4.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-5.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <div class="avatar-xs">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-success text-white font-size-16">
                                                                    A
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="avatar-group-item">
                                                        <a href="javascript: void(0);" class="d-inline-block">
                                                            <img src="images/users/avatar-2.jpg" alt=""
                                                                class="rounded-circle avatar-xs">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-1 d-flex justify-content-between">Progress <span
                                                    class="text-success">40%</span></p>
                                            <div class="progress progress-xs mb-0">
                                                <div style="width: 40%" class="progress-bar bg-success"
                                                    data-original-title="40%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end-card--->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings1" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"> Basic Salary Information</h5>
                                            <form>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Salary basis </label>
                                                            <select class="select2 form-control">
                                                                <option value="0">Select salary basis type</option>
                                                                <option value="1">Hourly</option>
                                                                <option value="2">Daily</option>
                                                                <option value="3">Weekly</option>
                                                                <option value="4">Monthly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Salary amount <small
                                                                    class="text-muted">per month</small></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">$</span>
                                                                </div>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Type your salary amount" value="0.00">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Payment type</label>
                                                            <select class="select2 form-control">
                                                                <option value="0">Select</option>
                                                                <option value="1">Hourly</option>
                                                                <option value="2">Daily</option>
                                                                <option value="3">Weekly</option>
                                                                <option value="4">Monthly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h5 class="card-title"> PF Information</h5>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">PF contribution</label>
                                                            <select class="select2 form-control">
                                                                <option value="0">Select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">PF No. <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4" value="select2-data-74-kpig">
                                                        <div class="form-group" value="select2-data-73-css7">
                                                            <label class="col-form-label">Employee PF rate</label>
                                                            <input type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Additional rate <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="select2 form-control">
                                                                <option value="0">Select</option>
                                                                <option value="1">1%</option>
                                                                <option value="2">2%</option>
                                                                <option value="3">3%</option>
                                                                <option value="4">4%</option>
                                                                <option value="5">5%</option>
                                                                <option value="6">6%</option>
                                                                <option value="7">7%</option>
                                                                <option value="8">8%</option>
                                                                <option value="9">9%</option>
                                                                <option value="10">10%</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Total rate</label>
                                                            <input type="text" class="form-control" placeholder="N/A"
                                                                value="11%">
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <h5 class="card-title"> ESI Information</h5>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">ESI contribution</label>
                                                            <select class="select2 form-control">
                                                                <option value="0">Select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">ESI No. <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Employee ESI rate</label>
                                                            <input type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Additional rate <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="select2 form-control">
                                                                <option value="0">Select</option>
                                                                <option value="1">1%</option>
                                                                <option value="2">2%</option>
                                                                <option value="3">3%</option>
                                                                <option value="4">4%</option>
                                                                <option value="5">5%</option>
                                                                <option value="6">6%</option>
                                                                <option value="7">7%</option>
                                                                <option value="8">8%</option>
                                                                <option value="9">9%</option>
                                                                <option value="10">10%</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Total rate</label>
                                                            <input type="text" class="form-control" placeholder="N/A"
                                                                value="11%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="submit-section text-center my-3">
                                                    <button class="btn btn-primary submit-btn"
                                                        type="submit">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- end row -->
            </div>
            <!--end--card-body -->
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<!--  personal info modal -->
<div class="modal fade change-password-modal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModal"
    aria-hidden="true" id="changePasswordModalId">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="changePasswordModal">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                {!! Form::open(['id' => 'change-password-form', 'method' => 'post']) !!}
                <div class="row row-cols-1">
                    <div class="col form-group input-group">
                        <label for="curr-password" class="col-form-label">Current Password</label>
                        <input type="password" name="old_password" class="form-control" id="curr-password">
                        <a class="btn btn-light password-icon-class show-pass-toggle" type="button">
                            <i class="mdi mdi-eye-outline"></i>
                        </a>
                    </div>
                    <div class="col form-group input-group">
                        <label for="new-password" class="col-form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" id="new-password">
                        <a class="btn btn-light password-icon-class show-pass-toggle" type="button">
                            <i class="mdi mdi-eye-outline"></i>
                        </a>
                    </div>
                    <div class="col form-group input-group">
                        <label for="confirm-password" class="col-form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" id="confirm-password">
                        <a class="btn btn-light password-icon-class show-pass-toggle" type="button">
                            <i class="mdi mdi-eye-outline"></i>
                        </a>
                    </div>
                </div>
                <p><b>Note :</b> Your password must be at least 8 characters, minimum 1 special character, 1 capital
                    letter, 1
                    small letter and 1 numeric letter.</p>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                {!! Form::close() !!}

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@include('common.modal',['modal_size' => 'modal-xl'])
@endsection
@push('scripts')
<script src="{{ asset('/js/modules/users.js') }}"></script>
<script type="text/javascript">
    const changePasswordUrl = "{{ route('user.change.password')}}";
    var SometingWentWrong = "{{ __('messages.ERR_SOMETING_WENT_WRONG') }}";

    $(document).ready(function() {
        $('#dynamicModal').on('shown.bs.modal', function () {
            initPersonalForm();
        });
        $( document ).ajaxComplete(function( event, xhr, settings ) {
            if ( (settings.type === "POST" || settings.type === "PATCH") && xhr.status == 200 && settings.for != 'fetchState' && settings.for != 'fetchCity') {
                setTimeout(function(){
                    window.location.reload();
                },500);
            }
        });

        $(document).on('click','#repeater-button', function(){
            setTimeout(function(){
                $(".select2-container").remove();
                $("select").select2();
                $(".select2-container").css("width", "100%");
            }, 100);
        });
    });

</script>

@endpush
