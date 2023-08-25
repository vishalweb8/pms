@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('leave-compensation') }}
@endsection
@section('content')
<div class="row mb-3">
    @foreach($leaveCountArray as $key => $list)
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body card-filter">
                <div class="d-flex">
                    <div class="flex-grow-1" data-leave={{ $list['name'] }}>
                        <p class="text-muted fw-medium">{{ Str::title($list['name']) }}</p>
                        <h4 class="mb-0">{{ $list['count'] }}</h4>
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
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search">
                @if(Config::get('constant.financial_year') != Null && !empty(Config::get('constant.financial_year')))
                <div class="d-sm-flex">
                    <select class="select2 form-control no-search" name="financial_year" id="financialYear">
                        {{-- <option value="">Select year</option> --}}
                        @foreach(Config::get('constant.financial_year') as $list)
                        <option value="{{ $list }}">{{ $list }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <a href="{{ route('leave-add-compensation-view') }}"
                    class="text-truncate ms-3 btn btn-primary waves-effect waves-light" type="button">{{
                    "Apply Compensation Leave" }}</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- @include('leaves.partials.user-dashboard-count') -->
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
            'url': '{{ route("leave-compensation-dashboard") }}',
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
        ] // Order on init. # is the column, starting at 0
    });
    datatalbe.columns(datatalbe.columns().count() - 1).visible(false);
    @if(Helper::hasAnyPermission(['leavecompensation.edit', 'leavecompensation.destroy', 'leavecompensation.view']))
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
