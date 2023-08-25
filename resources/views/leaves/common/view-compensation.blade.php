@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('my-leave-compensation-view') }}
@endsection
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">View Leave Compensation</h4>
            <div class="btn-outer">
                <a class="btn btn-secondary waves-effect waves-light cancel-btn"
                    href="{{ route('leave-compensation-dashboard') }}">Back</a>
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
                                <div class="align-self-center media-body">
                                    <div class="text-muted">
                                        <h5 class="mb-1 font-size-14 mt-0">{{ Auth::user()->full_name }}</h5>
                                        <p class="mb-2">
                                            {{ (Auth::user()->userRole) ? Auth::user()->userRole->name : '' }}</p>
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
                        <div class="title">Leave Compensation Assign to</div>
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
                        <div class="title">Requested Date</div>
                        <div class="text">{{ Helper::getDateFormat($leaveView->created_at) }}</div>
                    </li>
                    <li>
                        <div class="title">Reason</div>
                        <div class="text">{{$leaveView->reason}}</div>
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