@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('expected-revenue') }}
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title(Str::plural($module_title)) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search all-employee-leave-index">
                <div class="form-group mb-0 me-3">
                    @include('filters.month_filter')
                </div>
                <div class="form-group mb-0 me-3">
                    @include('filters.single_year_filter')
                </div>
                @if (Helper::hasAnyPermission(['expected-revenue.create']))
                <div class="form-group mb-0">
                    <button type="button" class="btn btn-primary waves-effect waves-light add-btn" data-url="{{ route('expectedRevenue.create') }}">Add</button>
                </div>
                @endif
            </div>
        </div>
        <div class="row mb-3" id="filter-counts"></div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body bg-body">
                        <div class=" ">
                            <table id="indexDataTable"
                                class="project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Id</th>
                                        <th>Project</th>
                                        <th>Actual Revenue($)</th>
                                        <th>Expected Revenue($)</th>
                                        <th>Month-Year</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('common.modal')
@endsection

@push('scripts')
<script src="{{ asset('/js/data-tables.js') }}"></script>
<script>
    const getUserDesigTeamUrl = "{{ route('getUserDesigTeam')}}";
    var SometingWentWrong = "{{ __('messages.ERR_SOMETING_WENT_WRONG') }}";
    $(function() {

        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $("#daily-task-month").find("option").remove();

        $.each(monthNames, function (index, value) {
            $("#daily-task-month").append(
                $(document.createElement("option")).prop({
                    value: index + 1,
                    text: value,
                })
            );
        });

        var dt = new Date();
        var month = ("0" + (dt.getMonth()+1)).slice(-2);
        $("#daily-task-month").val(parseInt(month)).select2();

        datatalbe = $('#indexDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found"
            },
            processing: true,
            serverSide: true,
            ajax: {
                'url': '{{ route("expectedRevenue.index") }}',
                data: function(e) {
                    e.year = $('#financialSingleYear').val();
                    e.month = $('#daily-task-month').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'table-id'},
                {data: 'id', name: 'id',visible:false},
                {data: 'project.name', name: 'project.name'},
                {data: 'actual_revenue', name: 'actual_revenue'},
                {data: 'expected_revenue', name: 'expected_revenue'},
                {data: 'month_year', name: 'month'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'table-action'}
            ],
            "order": [[ 1, "desc" ]], // Order on init. # is the column, starting at 0
            'drawCallback': function(response) {
                $("#filter-counts").html(response.json.revenueCount);
            }
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['expected-revenue.edit','expected-revenue.destroy']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif

        $('#financialSingleYear,#daily-task-month').change(function() {
            $('#indexDataTable').DataTable().draw(true);
        });
    });
</script>
@endpush
