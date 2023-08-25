<div class="modal-header">
    <h5 class="modal-title" id="holidayModal">Employee Leave Info</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @if(sizeof($leaves))
    <div class="card mb-2">
        <div class="row">
            <div class="col-lg-4">
                <div class="media p-2">
                    <div class="me-3">
                        @if($user->profile_image)
                        <img alt="" class="avatar-md rounded-circle img-thumbnail hw-70"
                            src="{{ asset('storage/upload/user/images/'.$user->profile_image) }}">
                        @else
                        <div class="avatar-xs hw-70">
                            <span class="avatar-title rounded-circle bg-primary text-white font-size-16">
                                {{ $user->profile_picture }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="align-self-center media-body">
                        <div class="text-black">
                            <h5 class="mb-0">{{ $user->first_name }} {{ $user->last_name }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="align-self-center col-lg-8">
                <div class="text-lg-center mt-2 mt-lg-0 p-2 p-lg-0">
                    <div class="row">
                        <div class="col-4">
                            <div>
                                <h5 class="mb-0">{{ getTotalLeaves($userLeaves) }}</h5>
                                <p class="text-muted text-truncate mb-0">Total Leave</p>
                            </div>
                        </div>
                        <div class="col-4">
                            @php $usedLeaves = $leaves->sum('duration'); @endphp
                            <div>
                                <h5 class="mb-0">{{ number_format($usedLeaves, 1) }}
                                </h5>
                                <p class="text-muted text-truncate mb-0">Used</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div>
                                <h5 class="mb-0">{{ getRemainingLeaves($userLeaves, $leaves) }}</h5>
                                <p class="text-muted text-truncate mb-0">Remaining</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-2">
        @foreach($leaves as $list)
        <div class="col-md-6">
            <div class="mini-stats-wid card mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-truncate">
                            <p class="fw-medium mb-2 font-size-14">
                                <i class="bx bx-calendar"></i>
                                <span class="text-primary">{{ Carbon\Carbon::parse($list->start_date)->format('d M y')
                                    }}</span>
                                to
                                <span class="text-primary">{{ Carbon\Carbon::parse($list->end_date)->format('d M y')
                                    }}</span>
                                ({{ $list->duration }})
                            </p>
                            <h5 class="mb-0 text-truncate font-size-16">{{ $list->reason }}</h5>
                        </div>
                        @if($list->type == "full")
                        <p class="ms-2 text-muted fw-normal badge-light-success font-size-12 badge mb-0 p-2"> Full Day
                            @elseif($list->type == "half" && $list->halfday_status == "firsthalf")
                        <p class="ms-2 text-muted fw-normal badge-light-info font-size-12 badge mb-0 p-2"> First Half
                            @elseif($list->type == "half" && $list->halfday_status == "secondhalf")
                        <p class="ms-2 text-muted fw-normal badge-light-warning font-size-12 badge mb-0 p-2"> Second
                            Half
                            @else
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="comment-not-found d-flex justify-content-center align-items-center h-100 flex-column py-5">
        <img src="/images/no-record-found.svg" class="w-25 d-flex " alt="NotFound" />
        <h6 class="text-muted mt-2">No record available</h6>
    </div>
    @endif
</div>
