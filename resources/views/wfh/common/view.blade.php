@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('my-wfh-request-view') }}
@endsection
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">View Work From Home</h4>
            <div class="btn-outer">
                {{-- @if($wfhView->status != "cancelled" && Auth::user()->userRole)
                    @if ((Auth::user()->userRole->code == "ADMIN" || Auth::user()->userRole->code == "PM" || Auth::user()->userRole->code == "HR") && (Auth::user()->id !=$wfhView->user_id ))
                    <a href="javascript:void(0)" class="btn btn-primary waves-effect waves-light cancel-btn" data-action="approve_wfh" data-id="{{ $wfhView->id }}"
                onclick="commentPopup($(this))">Approve</a>
                <a href="javascript:void(0)" class="btn btn-danger waves-effect waves-light cancel-btn"
                    data-action="reject_wfh" data-id="{{ $wfhView->id }}" onclick="commentPopup($(this))">Reject</a>
                @endif
                @endif --}}
                @if (empty($wfhView->satatus) || $wfhView->satatus == 'pending')
                <a href="javascript:void(0)" class="btn btn-info waves-effect waves-light cancel-btn add_comment"
                    data-action="add_comment" data-id="{{ $wfhView->id }}" onclick="commentPopup($(this))">Add Comment</a>
                @endif
                <a class="btn btn-secondary waves-effect waves-light cancel-btn" href="{{ url()->previous() }}">Back</a>
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
                        <div class="col-lg-12">
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
                                <div class="align-self-center media-body">
                                    <div class="text-muted">
                                        <h5 class="mb-1 font-size-14 mt-0">{{ Auth::user()->full_name }}</h5>
                                        <p class="mb-2">
                                            {{ (Auth::user()->userRole) ? Auth::user()->userRole->name : '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <ul class="personal-info">
                    <li>
                        <div class="title">Assign to</div>
                        <div class="text">
                            @foreach ($requestUsers as $requested)
                            <span class="badge badge-light-default">{{ $requested }}</span>
                            @endforeach
                        </div>
                    </li>
                    <li>
                        <div class="title">Day Type</div>
                        <div class="text">{{ Str::ucfirst($wfhView->wfh_type. ' day') }}</div>
                    </li>
                    @if($wfhView->type == 'half')
                    <li>
                        <div class="title">Half Type</div>
                        <div class="text">{{$wfhView->halfday_status}}</div>
                    </li>
                    @endif
                    <li>
                        <div class="title">Start Date</div>
                        <div class="text">{{$wfhView->start_date}}</div>
                    </li>
                    <li>
                        <div class="title">End Date</div>
                        <div class="text">{{$wfhView->end_date}}</div>
                    </li>
                    <li>
                        <div class="title">Return Date</div>
                        <div class="text">{{$wfhView->return_date}}</div>
                    </li>
                    <li>
                        <div class="title">Requested Date</div>
                        <div class="text">{{ Helper::getDateFormat($wfhView->created_at, 'd-m-Y H:m:s') }}</div>
                    </li>
                    <li>
                        <div class="title">Reason</div>
                        <div class="text">{{$wfhView->reason}}</div>
                    </li>
                    <li>
                        <div class="title">Is Adhoc ?</div>
                        <div class="text">{{$wfhView->is_adhoc ? 'Yes' : 'No' }}</div>
                    </li>
                    @if($wfhView->is_adhoc)
                    <li>
                        <div class="title">Adhoc Status</div>
                        <div class="text">{{($wfhView->adhoc_status == 'team_member') ? 'Team Member' : (($wfhView->adhoc_status == 'directly') ? 'Directly' : 'Not Inform') }}</div>
                    </li>
                    @endif
                </ul>

            </div>
        </div>
    </div> <!-- end col -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body profile-comment-view" data-simplebar class="sidebar-scroll h-100" id="commentSection">
                @include('wfh.partials.comments')
            </div>
        </div>
    </div>
</div> <!-- end row -->

@include('wfh.common.comments_model')
@endsection
@push('scripts')
<script type="text/javascript">
var requestURL = "{{ route('wfh-comment-request') }}";
var getAllHolidayDate = "{{ route('holiday-get-all-dates') }}";
</script>
<script src="{{ asset('js/modules/leaves.js') }}"></script>
@endpush
