@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('company-revenue') }}
@endsection
@section('content')
<div class="row mb-3">
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search all-employee-leave-index">
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body bg-body">
                <div class=" ">
                    <table id="indexDataTable"
                        class="project-list-table table nowrap table-borderless w-100 align-middle custom-table dataTable no-footer dtr-inline">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Project Id</th>
                                <th>Project Name</th>
                                <th>Actual Revenue($)</th>
                                <th>Expected Revenue($)</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection
@push('scripts')
<script>
    var countAllLeave = '';
    jQuery(function() {
        // remove and add option for months in filter
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
        var month = ("0" + (dt.getMonth() + 1)).slice(-2);
        $("#daily-task-month").val(parseInt(month)).select2();


        datatalbe = jQuery('#indexDataTable').DataTable({
        "oLanguage": {
            "sEmptyTable": "No Records Found"
        },
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            'url': '{{ route("company.revenue") }}',
            data: function(e) {
                e.year = $('#financialSingleYear').val();
                e.month = $('#daily-task-month').val();
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'table-id'
            },
            {
                data: 'project_id',
                name: 'project_id'
            },
            {
                data: 'project.name',
                name: 'project.name'
            },
            {
                data: 'actual_revenue',
                name: 'actual_revenue'
            },
            {
                data: 'expected_amount',
                name: 'expected_amount'
            }
        ],
        "order": [
            [1, "desc"]
        ] // Order on init. # is the column, starting at 0
    });
});
$('#financialSingleYear,#daily-task-month').change(function() {
    $('#indexDataTable').DataTable().draw(true);
});

</script>
@endpush
