@extends($theme)
@section('breadcrumbs')
<!-- {{ Breadcrumbs::render('home') }} -->
@endsection
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="m-0 page-title">All Employee Time Log</h4>
            <div class="d-sm-flex  align-items-center filter-with-search all-emp-log-filter">
                <div class="d-sm-flex">
                    <div class="form-group mb-0 me-3">
                        @include('filters.date_filter')
                    </div>
                    <div class="form-group mb-0 ">
                        @if(\Auth::user()->isManagement())
                            @include('filters.team_filter')
                        @else
                        <select class="form-control select2" name="team" id="team" disabled>
                            <option value="{{ Auth::user()->officialUser->userTeam->id }}" selected>{{
                                Auth::user()->officialUser->userTeam->name }}</option>
                        </select>
                        @endif
                    </div>
                </div>
                @if(Helper::hasAnyPermission(['all-emp-time-entry.create']))
                {{-- <button class="text-truncate ms-3 btn btn-primary waves-effect waves-light add-btn"
                    data-bs-toggle="modal" data-bs-target="#TimeEntry">Import Log</button> --}}
                @endif
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
                            <th>Sr no. </th>
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
@include('time-entry.importlog')
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
        // searching: false,
        ajax: {
            'url': '{{ route("all-employee-time-entry") }}',
            'type': "GET",
            data: function(e) {
                e.team = $('#team').val();
                e.date = $('#selectedDate').val();
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
                className:'verticalTop'
            },
            {
                data: 'log_out_time',
                name: 'log_out_time',
                searchable: false,
                className:'verticalTop'
            },
            {
                data: 'duration',
                name: 'duration',
                searchable: false,
                orderable: false,
                className:'verticalTop'
            },
            {
                data: 'total_duration',
                name: 'total_duration',
                searchable: false,
                orderable: false
            },

        ],
        "order": [
            [3, "desc"]
        ] // Order on init. # is the column, starting at 0
    });
});
$('#selectedDate,#team').on('change',function() {
    $('#indexDataTable').DataTable().draw(true);
});
</script>
@endpush
