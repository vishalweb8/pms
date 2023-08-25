@extends($theme)
@section('content')

<div class="row">
    <div class="col-lg-12">
        @include('common.index-title-with-button', ['title' => Str::title(Str::plural($module_title)), 'add_url' =>
        route('roles.create') ])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body bg-body">
                        <div class=" ">
                            <table id="indexDataTable"
                                class="project-list-table table nowrap table-bordereless w-100 align-middle custom-table dataTable no-footer dtr-inline">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Guard Name</th>
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
@include('common.modal')
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
                'url': '{{ route("roles.index") }}',
                'type': "GET"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'table-id'},
                {data: 'id', name: 'id',visible:false},
                {data: 'name', name: 'name'},
                {data: 'guard_name', name: 'guard_name'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'table-action'}
            ],
            "order": [[ 1, "asc" ]], // Order on init. # is the column, starting at 0
            "drawCallback": function () {
                $('.dataTables_wrapper > .row:first-child').addClass('test');
            }
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['roles.edit','roles.destroy']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif
    });

</script>
@endpush
