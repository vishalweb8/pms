@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('holiday') }}
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.index-title-with-button', ['title' => Str::title(Str::plural($module_title)), 'add_url' =>
        Helper::hasAnyPermission(['holiday.create']) ? route('holiday.create') : '' ])
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
                                        <th>Name</th>
                                        <th>Date</th>
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
    $(function() {
        datatalbe = $('#indexDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found"
            },
            processing: true,
            serverSide: true,
            ajax: {
                'url': '{{ route("holiday.index") }}',
                'type': "GET"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'table-id'},
                {data: 'id', name: 'id',visible:false},
                {data: 'name', name: 'name'},
                {data: 'date', name: 'date'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'table-action'}
            ],
            "order": [[ 1, "desc" ]] // Order on init. # is the column, starting at 0
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['holiday.edit','holiday.destroy']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif
    });
</script>
@endpush
