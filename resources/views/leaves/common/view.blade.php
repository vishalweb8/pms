@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('my-leave-view') }}
@endsection
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">View Leave</h4>
            <div class="btn-outer">
                {{-- @if (Auth::user()->userRole->code == "ADMIN" || Auth::user()->userRole->code == "PM")
                <a href="javascript:void(0)" class="btn btn-primary waves-effect waves-light cancel-btn approve_leave" data-action="approve_leave" data-id="{{ $leaveView->id }}"
                onclick="commentPopup($(this))">Approve</a>
                <a href="javascript:void(0)" class="btn btn-danger waves-effect waves-light cancel-btn reject_leave"
                    data-action="reject_leave" data-id="{{ $leaveView->id }}" onclick="commentPopup($(this))">Reject</a>
                @endif --}}
                @if (empty($leaveView->satatus) || $leaveView->satatus == 'pending')
                    <a href="javascript:void(0)" class="btn btn-info waves-effect waves-light cancel-btn add_comment"
                        data-action="add_comment" data-id="{{ $leaveView->id }}" onclick="commentPopup($(this))">Add Comment</a>
                @endif
                <a class="btn btn-secondary waves-effect waves-light cancel-btn"
                    href="{{ route('leave-dashboard') }}">Back</a>
            </div>
        </div>
    </div>
</div>

<div class="row leave-status-page  profile-details">
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body profile-view">
                <div class="leave-emp-info mb-2">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="media-img">
                                <div class="me-3">
                                    @if(Auth::user()->profile_image &&
                                    file_exists(public_path('storage/upload/user/images/'.Auth::user()->profile_image)))
                                    <img alt="" class="avatar-md rounded-circle img-thumbnail"
                                        src="{{asset('storage/upload/user/images')}}/{{Auth::user()->profile_image}}">
                                    @else
                                    <div class="avatar-md">
                                        <span class="avatar-title rounded-circle bg-primary text-white font-size-24">
                                            {{ Auth::user()->profile_picture }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                {{-- <div class="me-3"><img src="../../images/users/avatar-2.jpg" alt="" class="avatar-md rounded-circle img-thumbnail"></div> --}}
                                <div class="align-self-center media-body mt-1">
                                    <div class="text-muted">
                                        <h5 class="mb-1 font-size-14 mt-0">{{ Auth::user()->full_name }}</h5>
                                        <p class="mb-2">
                                            <p class="mb-2">{{ (Auth::user()->userRole) ? Auth::user()->userRole->name : '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="align-self-center col-lg-8">
                            @include('leaves.common.leave_count_view')
                        </div>

                    </div>
                </div>
                <ul class="personal-info">
                    <li>
                        <div class="title">Leave Assign to</div>
                        <div class="text">
                            @foreach ($requestUsers as $requested)
                            <span class="badge badge-light-default">{{ $requested }}</span>
                            @endforeach
                        </div>
                    </li>
                    <li>
                        <div class="title">Day Type</div>
                        <div class="text">{{ Str::ucfirst($leaveView->type. ' day') }} </div>
                    </li>
                    @if($leaveView->type == 'half')
                    <li>
                        <div class="title">Half Type</div>
                        <div class="text">{{$leaveView->halfday_status}}</div>
                    </li>
                    @endif
                    <li>
                        <div class="title">Start Date</div>
                        <div class="text">{{$leaveView->start_date}}</div>
                    </li>
                    <li>
                        <div class="title">End Date</div>
                        <div class="text">{{$leaveView->end_date}}</div>
                    </li>
                    <li>
                        <div class="title">Return Date</div>
                        <div class="text">{{$leaveView->return_date}}</div>
                    </li>
                    <li>
                        <div class="title">Requested Date</div>
                        <div class="text">{{ Helper::getDateFormat($leaveView->created_at) }}</div>
                    </li>
                    <li>
                        <div class="title">Reason</div>
                        <div class="text">{{$leaveView->reason}}</div>
                    </li>
                    <li>
                        <div class="title">Is adhoc leave?</div>
                        <div class="text">{{ ($leaveView->isadhoc_leave == 1) ? 'Yes' : 'No' }}</div>
                    </li>
                    <li>
                        <div class="title">Availability On Phone</div>
                        <div class="text">{{ ($leaveView->available_on_phone == 1) ? 'Yes' : 'No' }}</div>
                    </li>
                    <li>
                        <div class="title">Availability in City</div>
                        <div class="text">{{ ($leaveView->available_on_city == 1) ? 'Yes' : 'No' }}</div>
                    </li>
                    <li>
                        <div class="title">Emergency Contact</div>
                        <div class="text">{{ $leaveView->emergency_contact }}</div>
                    </li>
                </ul>

            </div>
        </div>
    </div> <!-- end col -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body profile-comment-view" data-simplebar class="sidebar-scroll h-100" id="commentSection">
                @include('leaves.partials.comments')
            </div>
        </div>
    </div>
</div> <!-- end row -->

@include('leaves.common.comments_model')
@endsection
@push('scripts')
<script type="text/javascript">
var requestURL = "{{ route('comment-request') }}";
var getAllHolidayDate = "{{ route('holiday-get-all-dates') }}";
</script>
<script src="{{ asset('js/modules/leaves.js') }}"></script>
@endpush
