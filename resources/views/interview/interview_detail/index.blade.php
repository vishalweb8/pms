@extends($theme)
{{-- @section('breadcrumbs')
{{ Breadcrumbs::render('interview-detail') }}
@endsection --}}
@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.index-title-with-button', ['title' => Str::title(Str::plural($module_title)), 'add_url' =>
        Helper::hasAnyPermission(['technology.create']) ? route('interview-detail.create') : '' ])
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Total Experience</th>
                                        <th>Current CTC</th>
                                        <th>Expected CTC</th>
                                        <th>Notice Period</th>
                                        <th>Current Organization</th>
                                        <th>Location</th>
                                        <th>Reference Name</th>
                                        <th>Source By</th>
                                        <th>Remark</th>
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

    </div>
</div>
@include('common.modal',['modal_size' => 'modal-xl'])
@endsection
@push('scripts')
<script src="{{ asset('/js/data-tables.js') }}"></script>
<script>
    jQuery(function() {
        datatalbe = jQuery('#indexDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found"
            },
            processing: true,
            serverSide: true,
            ajax: {
                'url': '{{ route("interview-detail.index") }}',
                'type': "GET"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'table-id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'total_experience', name: 'total_experience'},
                {data: 'current_ctc', name: 'current_ctc'},
                {data: 'expected_ctc', name: 'expected_ctc'},
                {data: 'notice_period', name: 'notice_period'},
                {data: 'current_organization', name: 'current_organization'},
                {data: 'location', name: 'location'},
                {data: 'reference_id', name: 'reference_id'},
                {data: 'source_by', name: 'source_by'},
                {data: 'remark', name: 'remark'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'table-action'}
            ]
        });
    });

</script>
@endpush
