@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('all-leave-statistics') }}
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search">
                <div class="d-sm-flex">
                    @include('filters.year_filter')
                </div>
            </div>
        </div>
    </div>
</div><div class="row">
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
                                {{-- <th>Total Granted Leave</th> --}}
                                <th>Granted Leave</th>
                                <th>Compensated Leave</th>
                                <th>Availed Leave</th>
                                <th>Balance</th>
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
            'url': '{{ route("leave-statistics-all-employee") }}',
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
                data: 'full_name',
                name: 'full_name',
                searchable: true,
                orderable: true
            },
            /* {
                data: 'total_grand_leave',
                name: 'total_grand_leave',
                orderable: false,
                searchable: false
            },*/
            {
                data: 'total_leaves',
                name: 'total_leaves',
                orderable: false,
                searchable: false
            },
            {
                data: 'compensated_leave',
                name: 'compensated_leave',
                orderable: false,
                searchable: false
            },
            {
                data: 'used_leaves',
                name: 'used_leaves',
                orderable: false,
                searchable: false
            },
            {
                data: 'remaining_leaves',
                name: 'remaining_leaves',
                orderable: false,
                searchable: false
            },
        ],
        "order": [
            [0, "desc"]
        ] // Order on init. # is the column, starting at 0
    });

    $('#financialYear').change(function() {
        $('#indexDataTable').DataTable().draw(true);
    });

    $(document).on('click', '.sync-statics', function () {

        let element = $(this);
        let id = element.data('id');
        let fyear = $('#financialYear').val();
        console.log('clicked');
        $('#pageLoader').show();

        $.ajax({
            url: "{{ route('sync-leave-statics') }}",
            type: "POST",
            data: {
                _token: tokenelem,
                user_id:id,
                fyear:fyear,
            },
            success: function (response) {
                console.log('response', response);
                toastr.success(response.message, response.action_title || '');
                $('#pageLoader').hide();
                datatalbe.draw(true);

            },
            error: function (response) {
                $('#pageLoader').hide();
                toastr.error(response.message, response.action_title || '');
            },
        });
    });
});
</script>
@endpush
