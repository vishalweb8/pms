@extends($theme)
@section('breadcrumbs')
<!-- {{ Breadcrumbs::render('home') }} -->
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        @if(isset($nonFillableDates) && empty($nonFillableDates))
        <div class="alert alert-success alert-dismissible fade show pe-1" role="alert">
            <i class="mdi mdi-check-all me-2"></i>
            <strong class="me-2">Well done!</strong> Your all SOD entries are up to date.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @else
        <div class="alert alert-danger alert-dismissible fade show pe-1" role="alert">
            <div>
                <p class="mb-0"><i class="mdi mdi-block-helper me-2"></i><strong class="me-2">Oops!</strong>Your SOD for the following dates are pending. Please update on priority.</p>
            </div>
            @if((count($nonFillableDates) > 0))
            <?php
                $exist_entries = \App\Models\DailyTask::where('user_id', Auth()->id())->whereIn('current_date', $nonFillableDates->toArray())->get();

            ?>
            @foreach($nonFillableDates as $date)
            <?php
                $date_format = \Carbon\Carbon::parse($date)->format('Y-m-d');
                $link = route('work-create', $date_format);
                $exist_entry = Auth::user()->userDailyTask()->where('current_date', $date_format)->first();
                if(!empty($exist_entries) && $exist_entries->count() && $exist_entries->where('current_date', $date_format)) {
                    $exist_entry = $exist_entries->where('current_date', \Carbon\Carbon::parse($date)->format('d-m-Y'))->first();
                    if(!empty($exist_entry)){
                        $link = route('work-edit', $exist_entry->id);
                    }
                    // dump($exist_entries, $exist_entry); exit();
                }
            ?>
            <a href="{{ $link }}">
                <badge class="bg-danger-alert badge mt-1 mr-1 fw-normal lh-base">{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</badge>
            </a>
            @endforeach
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>
    <div class="col-xl-4 box-small mb-4">
        <div class="card overflow-hidden">
            <div class="bg-soft-today">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-black">Employee on Leave Today</h5>
                        </div>
                    </div>
                    <div class="col-5 align-self-end text-end">
                        <img src="/images/dash-today-leave.svg" alt="" class="holder-img img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">{{ Helper::totalEmployee() ? Helper::totalEmployee() : '0'
                                        }}</h5>
                                    <p class="text-muted mb-0 text-truncate">Total Employee</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">
                                        {{ Helper::fullLeaves()->total_duration ? Helper::fullLeaves()->total_duration : '0' }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">Full Day Leave</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">
                                        {{ Helper::halfLeaves() ? Helper::halfLeaves()->total_duration : '0' }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">Half Day Leave</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center text-md-end">
                                <a data-url="{{route('view-todayLeave-modal')}}" href="javascript: void(0);"
                                    class="btn btn-primary waves-effect waves-light btn-sm bg-today add-btn">View All <i
                                        class="mdi mdi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 box-small mb-4">
        <div class="card overflow-hidden">
            <div class="bg-soft-info">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-black">Upcoming Leaves</h5>
                        </div>
                    </div>
                    <div class="col-5 align-self-end text-end">
                        <img src="/images/dash-upcoming-leave.svg" alt="" class="holder-img img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">{{ Helper::totalUpcomingLeaves() ?
                                        count(Helper::totalUpcomingLeaves()) : '0' }}</h5>
                                    <p class="text-muted mb-0 text-truncate">Total Employee</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">
                                        {{ Helper::fullUpcomingLeaves()->total_duration ? Helper::fullUpcomingLeaves()->total_duration :
                                        '0' }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">Full Day Leave</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">
                                        {{ Helper::halfUpcomingLeaves() ? Helper::halfUpcomingLeaves()->total_duration :
                                        '0' }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">Half Day Leave</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center text-md-end">
                                <a data-url="{{ route('view-upcomingLeave-modal') }}" href="javascript: void(0);"
                                    class="btn btn-primary waves-effect waves-light btn-sm bg-upcoming add-btn">View All
                                    <i class="mdi mdi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 box-small mb-4">
        <div class="card overflow-hidden">
            <div class="bg-soft-primary">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-black">Employee on WFH Today</h5>
                        </div>
                    </div>
                    <div class="col-5 align-self-end text-end">
                        <img src="/images/profile-img.png" alt="" class="holder-img img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">{{ Helper::totalWfh() ? Helper::totalWfh() : '0' }}</h5>
                                    <p class="text-muted mb-0 text-truncate">Total Employee</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">
                                        {{ Helper::fullWfh()->total_duration ? Helper::fullWfh()->total_duration : '0'
                                        }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">Full Day WFH</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">
                                        {{ Helper::halfWfh() ? Helper::halfWfh()->total_duration : '0' }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">Half Day WFH</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center text-md-end">
                                <a data-url="{{ route('view-todayWFH-modal') }}" href="javascript: void(0);"
                                    class="btn btn-primary waves-effect waves-light btn-sm bg-wfh add-btn">View All <i
                                        class="mdi mdi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 box-small mb-4">
        <div class="card overflow-hidden">
            <div class="bg-soft-leave">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-black">My Leave Info</h5>
                        </div>
                    </div>
                    <div class="col-5 align-self-end text-end">
                        <img src="/images/dash-leave-info.svg" alt="" class="holder-img img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">{{ number_format((!empty($userLeaves->total_leave) ? $userLeaves->total_leave : Config::get('constant.total_leave')), 1) }}</h5>
                                    <p class="text-muted mb-0 text-truncate">Total Leave</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">
                                        {{ number_format(Helper::usedLeaves(\Auth::user()->id)->total_duration ?
                                        Helper::usedLeaves(\Auth::user()->id)->total_duration : '0', 1) }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">Used Leaves</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">
                                        {{number_format(number_format((!empty($userLeaves->total_leave) ? $userLeaves->total_leave : Config::get('constant.total_leave')), 1) - number_format(Helper::usedLeaves(\Auth::user()->id)->total_duration ?
                                            Helper::usedLeaves(\Auth::user()->id)->total_duration : '0', 1),1) }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">Remaining</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center text-md-end">
                                {{-- <a data-url="{{route('admin-modal')}}" href="javascript: void(0);"
                                    class="btn btn-primary waves-effect waves-light btn-sm">More
                                    Info <i class="mdi mdi-arrow-right ms-1"></i></a> --}}
                                <a data-url="{{route('view-myleave-modal')}}" href="javascript: void(0);"
                                    class="btn btn-primary waves-effect waves-light btn-sm add-btn">More
                                    Info
                                    <i class="mdi mdi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xl-4 box-small mb-4">
        <div class="card overflow-hidden">
            <div class="bg-soft-secondary">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-black">Holidays - {{ now()->year }}</h5>
                        </div>
                    </div>
                    <div class="col-5 align-self-end text-end">
                        <img src="/images/holiday-dash.png" alt="" class="holder-img img-fluid" />
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">{{ Helper::totalHolidays() ? count(Helper::totalHolidays())
                                        : '0' }}</h5>
                                    <p class="text-muted mb-0 text-truncate">Total Holidays</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">{{ Helper::thisMonthHolidays() ?
                                        count(Helper::thisMonthHolidays()) : '0' }}</h5>
                                    <p class="text-muted mb-0 text-truncate">This Month</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">{{ Helper::nextMonthHolidays() ?
                                        count(Helper::nextMonthHolidays()) : '0' }}</h5>
                                    <p class="text-muted mb-0 text-truncate">Next Month</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center text-md-end">
                                <a data-url="{{route('admin-modal')}}" href="javascript: void(0);"
                                    class="btn btn-primary waves-effect waves-light btn-sm bg-holiday add-btn" {{
                                    Helper::totalHolidays() ? Helper::totalHolidays() : 'disabled' }}>More
                                    Info
                                    <i class="mdi mdi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 box-small mb-4">
        <div class="card overflow-hidden">
            <div class="bg-log">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-black">My Time Log</h5>
                        </div>
                    </div>
                    <div class="col-5 align-self-end text-end">
                        <img src="/images/time-log.png" alt="" class="holder-img img-fluid" />
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">{{ $lastDay ? $lastDay : 0 }}</h5>
                                    <p class="text-muted mb-0 text-truncate">Last Day</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">
                                        {{ $thisWeek ?? 0 }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">This Week</p>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="font-size-15">{{ $aveMonth ? $aveMonth : 0 }}</h5>
                                    <p class="text-muted mb-0 text-truncate">This Month Average</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center text-md-end">
                                <a data-url="{{route('view-today-time-entry')}}" href="javascript: void(0);"
                                    class="btn btn-primary waves-effect waves-light btn-sm add-btn">
                                    Today's Entry
                                </a>
                                <a data-url="{{route('view-timeentry-modal')}}" href="javascript: void(0);"
                                    class="btn btn-primary waves-effect waves-light btn-sm bg-log-btn add-btn">
                                    More Info
                                    <i class="mdi mdi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end--row-->



@include('common.modal',['modal_size' => 'modal-lg'])
@endsection
