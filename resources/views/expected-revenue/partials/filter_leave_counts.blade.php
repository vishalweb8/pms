
@foreach($revenueCounts as $key => $count)
    <div class="col-md-3">
        <div class="card mini-stats-wid card-filter-main">
            <div class="card-body card-filter @if(request('leaveType') == $key) active-filter @endif">
                <div class="d-flex">
                    <div class="flex-grow-1" data-leave={{ $key }}>
                        <p class="text-muted fw-medium">{{ Str::title($key) }}</p>
                        <h4 class="mb-0">{{ $count}}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-purchase-tag-alt font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
