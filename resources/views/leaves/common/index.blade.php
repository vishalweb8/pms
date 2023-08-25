@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('my-leave') }}
@endsection
@section('content')
<div class="row mb-3" id="filter-counts"></div>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search">
                <div class="d-sm-flex">
                    @include('filters.year_filter')
                </div>
                <a href="{{ !isset($teamLeave) ? route('leave-add-view') : route('leave-add-team')}}"
                    class="text-truncate ms-3 btn btn-primary waves-effect waves-light" type="button">{{
                    !isset($teamLeave) ? "Apply Leave" : "Add Team Leave" }}</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body bg-body">
                <div class=" ">
                    <table id="indexDataTable"
                        class="project-list-table table nowrap table-borderless w-100 align-middle custom-table dataTable no-footer dtr-inline">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Day(s) Taken</th>
                                <th>Day Type</th>
                                <th>Status</th>
                                <th>Comments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@include('common.modal')

@endsection
@push('scripts')
<script src="{{ asset('/js/data-tables.js') }}"></script>
<script type="text/javascript">
    var getAllHolidayDate = "{{ route('holiday-get-all-dates') }}";
    var SometingWentWrong = "{{ __('messages.ERR_SOMETING_WENT_WRONG') }}";
</script>
<script src="{{ asset('js/modules/leaves.js') }}"></script>
<script>
    var countAllLeave = '';
    jQuery(function() {
    datatalbe = jQuery('#indexDataTable').DataTable({
        "oLanguage": {
            "sEmptyTable": "No Records Found"
        },
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            'url': '{{ route("leave-dashboard") }}',
            'type': "GET",
            data: function(e) {
                e.fyear = $('#financialYear').val();
                e.leaveType = countAllLeave;
            }
        },
        columns: [
            {
                data: 'created_at',
                name: 'created_at',
                searchable: false,
                visible:false
            },
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'table-id'
            },
            {
                data: 'userLeave.full_name',
                name: 'userLeave.full_name',
                orderable: false
            },
            {
                data: 'start_date',
                name: 'start_date',
                orderable: true
            },
            {
                data: 'end_date',
                name: 'end_date',
                orderable: true
            },
            {
                data: 'duration',
                name: 'duration',
                orderable: true
            },
            {
                data: 'type',
                name: 'type',
                orderable: true
            },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false,
                className: 'table-action'
            },
            {
                data: 'comments',
                name: 'comments',
                orderable: false,
                searchable: false,
                className: 'table-action'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'table-action'
            }
        ],
        "order": [
             [0, "desc"],
            //[3, "desc"]
        ], // Order on init. # is the column, starting at 0
        'drawCallback': function(response) {
            $("#filter-counts").html(response.json.leaveCounts);
        }
    });
    datatalbe.columns(datatalbe.columns().count() - 1).visible(false);
    @if(Helper::hasAnyPermission(['leave.edit', 'leave.destroy', 'leave.view']))
    datatalbe.columns(datatalbe.columns().count() - 1).visible(true);
    @endif
});
$('#financialYear').change(function() {
    $('#indexDataTable').DataTable().draw(true);
});

$(document).on('click','.card-filter', function(e){
    countAllLeave = $(this).parent().find('.flex-grow-1').attr('data-leave');
    $('#indexDataTable').DataTable().draw(true);
});
</script>
@endpush
