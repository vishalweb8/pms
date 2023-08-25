@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('allemp-wfh-request') }}
@endsection
@section('content')
<div class="row mb-3" id="filter-counts"></div>
<div class="row">
    <div class="col-lg-12">
        <div class="page-title-box d-md-flex align-items-center justify-content-between">
            <h4 class="m-0 page-title">{{ Str::title($module_title) }}</h4>
            <div class="d-md-flex  align-items-center filter-with-search all-emp-filter">
                @if(Config::get('constant.financial_year') != Null && !empty(Config::get('constant.financial_year')))
                <div class="d-sm-flex">
                    @include('filters.year_filter')
                </div>
                @endif
                <div class="d-sm-flex">
                    @include('filters.team_filter')
                </div>

                @if(Config::get('constant.leave_status') != Null && !empty(Config::get('constant.leave_status')))
                <div class="d-sm-flex">
                    <select class="select2 form-control no-search" name="leave_status" id="leaveStatus">
                        <option value="nothing">Select Status</option>
                        @foreach(Config::get('constant.leave_status') as $key => $list)
                        <option value="{{ $key }}">{{ $list }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="d-sm-flex">
                    @include('filters.date_filter')
                </div>
                @if(Helper::hasAnyPermission(['wfhallemp.create']))
                <a class=" text-truncate ms-1 btn btn-primary waves-effect waves-light add-btn"
                    href="{{ route('wfh-all-emp-add') }}">Add Employee WFH</a>
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
                        class="project-list-table table nowrap table-borderless w-100 align-middle custom-table dataTable no-footer dtr-inline">
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
@include('leaves.common.comments_model')

@endsection

@push('scripts')
<script src="{{ asset('js/modules/leaves.js') }}"></script>
<script>
    var requestURL = "{{ route('wfh-comment-request') }}";
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
            'url': '{{ route("wfh-all-emp-index") }}',
            'type': "GET",
            data: function(e) {
                e.fyear = $('#financialYear').val();
                e.team = $('#team').val();
                e.date = $('#selectedDate').val();
                e.status = $('#leaveStatus').val();
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
                orderable: true
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
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'table-action'
            }
        ],
        "order": [
            [0, "desc"]
        ], // Order on init. # is the column, starting at 0
        'drawCallback': function(response) {
            $("#filter-counts").html(response.json.wfhCounts);
        }
    });
});
$('#financialYear , #team, #selectedDate , #leaveStatus').change(function() {
    if($(this).attr('id') == "leaveStatus") {
        $('.card-filter').removeClass('active-filter');
        countAllWFH='';
    }
    $('#indexDataTable').DataTable().draw(true);
});
$(document).on('click','.card-filter', function(e){
    countAllWFH = $(this).parent().find('.flex-grow-1').attr('data-wfh');
    $('#leaveStatus').val(countAllWFH).select2();
    $('#indexDataTable').DataTable().draw(true);
});
</script>
@endpush
