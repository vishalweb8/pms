<div class="d-flex justify-content-between ">
    <div class="mb-3 font-size-18 fw-normal">
        <span class="mb-3 font-size-14 fw-normal">Status</span>
        @if ($wfhView->status == "pending")
        <span class='badge badge-light-warning'>Pending</span>
        @endif
        @if ($wfhView->status == "approved")
        <span class='badge badge-light-success'>Approved</span>
        @endif
        @if ($wfhView->status == "rejected")
        <span class='badge badge-light-danger'>Rejected</span>
        @endif
        @if ($wfhView->status == "cancelled")
        <span class='badge badge-light-info'>Cancelled</span>
        @endif
    </div>
    @if($wfhView->created_by != NULL)
    <div class="mb-6 font-size-18 fw-normal">
        <span class="mb-3 font-size-14 fw-normal">Requested By</span>
        <span class='badge badge-light-default'>{{($wfhView->created_by != NULL) ? $wfhView->userCreated->full_name : ''}}</span>
    </div>
    @endif
</div>
@if(in_array(request()->route()->getName(),['wfh-comments-view']))
<div class="col-md-12 reason-box-show-hide">
    <div class="mini-stats-wid card mb-4">
        <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="fw-medium mb-2 font-size-14">
                        <span
                            class="text-primary">Reason</span>
                    </p>
                    <span class="mb-0 reason-box">{{ $wfhView->reason }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<ul class="status-timeline">
    <li class="mb-2">
        @forelse ( $wfhComments as $comments )
        <div class="status-update-outer">
            <div class="date-wrapper">
                <p>{{ Helper::getDateFormat($comments->created_at) }}</p>
                <p>{{ Carbon\Carbon::parse($comments->created_at)->format('h:i A') }}</p>
            </div>
            <div class="status-detail">
                <div class="vertical-row pb-0"></div>
                <div class="user">
                @if($comments->reviewUser->profile_image && file_exists(public_path('storage/upload/user/images/'.$comments->reviewUser->profile_image)))
                    <img class="w-100" alt="img" src="{{asset('storage/upload/user/images')}}/{{$comments->reviewUser->profile_image}}">
                @else
                    <div class="avatar-xs">
                        <span class="avatar-title bg-primary text-white font-size-14">
                            {{ $comments->reviewUser->profile_picture }}
                        </span>
                    </div>
                @endif
                </div>
                <div class="user-details w-100">
                    <p>
                        {{ $comments->reviewUser->full_name }}
                        @if ($comments->status == "rejected")
                        <span class='badge font-size-10 ms-2 badge-light-danger'>Rejected</span>
                        @endif
                        @if ($comments->status == "approved")
                        <span class='badge font-size-10 ms-2 badge-light-success'>Approved</span>
                        @endif
                        @if ($comments->status == "cancelled")
                        <span class='badge font-size-10 ms-2 badge-light-info'>Cancelled</span>
                        @endif
                    </p>
                    <div class="comment">{!! $comments->comments !!}</div>
                </div>
            </div>
        </div>
        @empty
        @if($wfhView->status != "cancelled")
        <div class="wfm-comment-not-found d-flex justify-content-center align-items-center h-100 flex-column">
            <img src="/images/no-record-found.svg" class="w-25 d-flex" alt="NotFound" />
            <h6 class="text-muted mt-2">No record found</h6>
        </div>
        @endif
        @endforelse
    </li>
</ul>
