@extends($theme)
@section('breadcrumbs')
<!-- {{ Breadcrumbs::render('home') }} -->
@endsection
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="m-0 page-title">My Time Log</h4>
            <div class="d-sm-flex  align-items-center filter-with-search">
                <div class="d-sm-flex">
                    <div class="form-group mb-0 me-3">
                        @include('filters.month_filter')
                    </div>
                    <div class="form-group mb-0">
                        @include('filters.single_year_filter')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mini-stats-wid mb-3">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Last Day</p>
                        <h4 class="mb-0">{{ $lastDay ? $lastDay : 0 }}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                            <span class="avatar-title">
                                <i class="bx bx-time font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">This Week</p>
                        <h4 class="mb-0">{{ $thisWeek ?? 0 }}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center ">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-calendar-check font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">This Month Average </p>
                        <h4 class="mb-0">{{ $aveMonth ? $aveMonth : 0 }}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-timer font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body bg-body">
                <table id="indexDataTable"
                    class="project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Name</th>
                            <th>Log Date</th>
                            <th>Log In Time</th>
                            <th>Log Out Time</th>
                            <th>Duration</th>
                            <th>Total Duration</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection
@push('scripts')
<script>
    var importURL = "{{ route('import-time-log-entries') }}";
</script>
<script src="{{ asset('/js/modules/time_entry.js')}}"></script>
@endpush
@push('scripts')
<script>
    jQuery(function() {
    datatalbe = jQuery('#indexDataTable').DataTable({
        "oLanguage": {
            "sEmptyTable": "No Records Found"
        },
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            'url': '{{ route("my-time-entry") }}',
            'type': "GET",
            data: function(e) {
                e.fyear = $('#financialSingleYear').val();
                e.month = $('#daily-task-month').val();
                e.user_id = "{{auth()->id()}}";
            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'table-id'
            },
            {
                data: 'EmployeeCode',
                name: 'EmployeeCode',
                searchable: false,
                orderable: true
            },
            {
                data: 'full_name',
                name: 'officialDetail.userOfficial.full_name',
                //searchable: false
            },
            {
                data: 'log_date',
                name: 'log_date',
                searchable: false
            },
            {
                data: 'log_in_time',
                name: 'log_in_time',
                searchable: false,
                className: 'verticalTop'
            },
            {
                data: 'log_out_time',
                name: 'log_out_time',
                searchable: false,
                className: 'verticalTop'
            },
            {
                data: 'duration',
                name: 'duration',
                searchable: false,
                orderable: false,
                className: 'verticalTop'
            },
            {
                data: 'total_duration',
                name: 'total_duration',
                searchable: false,
                orderable: false
            }

        ],
        "order": [
            [3, "desc"]
        ] // Order on init. # is the column, starting at 0
    });
});
$('#financialSingleYear,#daily-task-month').on('change',function() {
    $('#indexDataTable').DataTable().draw(true);
});
</script>
@endpush
