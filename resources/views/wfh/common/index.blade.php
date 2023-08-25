@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('my-wfh-request') }}
@endsection
@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="m-0 page-title">{{ Str::title($module_title) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search">
                <div class="d-sm-flex">
                    @include('filters.year_filter')
                </div>
                @if(Helper::hasAnyPermission(['wfh.create']))
                <a class=" text-truncate ms-3 btn btn-primary waves-effect waves-light"
                    href="{{ route('wfh-add-view') }}">Add WFH Request</a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body bg-body">
                <div class=" ">
                    <table id="indexDataTable"
                        class="project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                        <thead>
                            <tr>
                                <th>Id</th>
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
</script>
<script src="{{ asset('js/modules/leaves.js') }}"></script>
<script>
    jQuery(function() {
        datatalbe = jQuery('#indexDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found"
            },
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                'url': '{{ route("wfh-dashboard") }}',
                'type': "GET",
                data: function(e) {
                    e.fyear = $('#financialYear').val();
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
                    data: 'userWfh.full_name',
                    name: 'userWfh.full_name',
                    orderable: false,
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
                    data: 'wfh_type',
                    name: 'wfh_type',
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
            "order": [[ 0, "desc" ]], // Order on init. # is the column, starting at 0
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['wfh.edit','wfh.destroy','wfh.view']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif
    });
    $('#financialYear').change(function() {
        $('#indexDataTable').DataTable().draw(true);
    });
</script>
@endpush
