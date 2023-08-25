@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('team-wfh-request') }}
@endsection
@section('content')
<div class="row mb-3" id="filter-counts"></div>
<div class="row">
    <div class="col-lg-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="m-0 page-title">{{ Str::title($module_title) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search">
                @if(Config::get('constant.financial_year') != Null && !empty(Config::get('constant.financial_year')))
                <div class="d-sm-flex">
                    @include('filters.year_filter')
                </div>
                @endif
                @if(Helper::hasAnyPermission(['wfhteam.create']))
                <a class=" text-truncate ms-3 btn btn-primary waves-effect waves-light"
                    href="{{ route('wfh-add-team') }}">Add WFH Requests</a>
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
                                <th>Id</th>
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
<script>
    var countAllWFH = '';

    jQuery(function() {
        datatalbe = jQuery('#indexDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found"
            },
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                'url': '{{ route("wfh-team") }}',
                'type': "GET",
                data: function(e) {
                    e.fyear = $('#financialYear').val();
                    e.wfhType = countAllWFH;
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
                    data: 'wfh_id',
                    name: 'wfh_id',
                    searchable: false,
                    orderable: true,
                    visible: false
                },
                {
                    data: 'userWfh.full_name',
                    name: 'userWfh.full_name',
                    searchable: true,
                    orderable: true,
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
            'drawCallback': function(response) {
                $("#filter-counts").html(response.json.wfhCounts);
            }
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['wfh.edit','wfh.destroy','wfh.view']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif
    });
    $('#financialYear').change(function() {
        $('#indexDataTable').DataTable().draw(true);
    });
    $(document).on('click','.card-filter', function(e){
        countAllWFH = $(this).parent().find('.flex-grow-1').attr('data-wfh');
        $('#leaveStatus').val(countAllWFH).select2();
        $('#indexDataTable').DataTable().draw(true);
    });
</script>
@endpush
