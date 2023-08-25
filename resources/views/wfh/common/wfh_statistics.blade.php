@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('all-wfh-statistics') }}
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search all-employee-leave-index">
                <div class="d-sm-flex">
                    {!! Form::select('date_fitler', $financeYearData, null, [ 'class' => 'form-control select2 no-search', 'id' => 'date_fitler']) !!}
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
                                <th>Id</th>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Approved</th>
                                <th>Pending</th>
                                <th>Rejected</th>
                                <th>Cancelled</th>
                                <th>Total</th>
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
@include('common.modal',['modal_size' => 'modal-lg'])
@endsection
@push('scripts')
<script src="{{ asset('js/modules/leaves.js') }}"></script>
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
            'url': '{{ route("wfh-statistics-all-employee") }}',
            'type': "GET",
            data: function(e) {
                e.fyear = $('#date_fitler').val();
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
                data: 'full_name',
                name: 'full_name',
                searchable: true,
                orderable: true
            },
            {
                data: 'approved_wfh',
                name: 'approved_wfh',
                orderable: true,
                searchable: false
            },
            {
                data: 'pending_wfh',
                name: 'pending_wfh',
                orderable: true,
                searchable: false
            },
            {
                data: 'rejected_wfh',
                name: 'rejected_wfh',
                orderable: true,
                searchable: false
            },
            {
                data: 'cancelled_wfh',
                name: 'cancelled_wfh',
                orderable: true,
                searchable: false
            },
            {
                data: 'total_wfh',
                name: 'total_wfh',
                orderable: true,
                searchable: false
            },

        ],
        "order": [
            [2, "asc"]
        ] // Order on init. # is the column, starting at 0
    });
});
$('#date_fitler').change(function() {
    $('#indexDataTable').DataTable().draw(true);
});
</script>
@endpush
