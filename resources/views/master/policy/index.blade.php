@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('policy') }}
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.index-title-with-button', ['title' => Str::title(Str::plural($module_title)), 'add_url' =>
        Helper::hasAnyPermission(['policy.create']) ? route('policy.create') : '' ])
        <div class="card">
            <div class="card-body bg-body">
                <div class=" ">
                    <table id="indexDataTable"
                        class="project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Id</th>
                                <th>Title</th>
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
                    'url': '{{ route("policy.index") }}',
                    'type': "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'table-id'},
                    {data: 'id', name: 'id',visible:false},
                    {data: 'title', name: 'title'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'table-action'}
                ],
                "order": [[ 1, "desc" ]] // Order on init. # is the column, starting at 0
            });
            datatalbe.columns(datatalbe.columns().count()-1).visible(false);
            @if(Helper::hasAnyPermission(['policy.edit','policy.destroy']))
                datatalbe.columns(datatalbe.columns().count()-1).visible(true);
            @endif
        });
</script>
@endpush
