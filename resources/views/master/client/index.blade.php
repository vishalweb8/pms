@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('clients') }}
@endsection
@section('content')
<div class="row client-index">
    <div class="col-lg-12">
        @include('common.index-title-with-button', ['title' => Str::title(Str::plural($module_title)), 'add_url' =>
        Helper::hasAnyPermission(['client.create']) ? route('client.create'): '' ])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body bg-body">
                        <div class="">
                            <table id="indexDataTable"
                                class="project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        {{-- <th>Address</th> --}}
                                        <th>Project by Client</th>
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
@include('common.modal',['modal_size' => 'modal-lg'])
@endsection
@push('scripts')
<script src="{{ asset('/js/data-tables.js') }}"></script>
<script src="{{ asset('/js/modules/users.js') }}"></script>
{!! Helper::ajaxFillDropdown('country_id', 'state_id', route('get-state'), ['state_id','city_id']) !!}
{!! Helper::ajaxFillDropdown('state_id', 'city_id', route('get-city')) !!}
<script>
    jQuery(function() {
        datatalbe = jQuery('#indexDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found"
            },
            processing: true,
            serverSide: true,

            ajax: {
                'url': '{{ route("client.index") }}',
                'type': "GET"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'table-id'},
                {data: 'id', name: 'id'},
                {data: 'name', name: 'first_name'},
                {data: 'email', name: 'email'},
                {data: 'phone_number', name: 'phone_number'},
                // {data: 'address1', name: 'address1',orderable: false},
                {data: 'getProjectByClient.name', name: 'getProjectByClient.name',orderable: false},

                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'table-action'}
            ],
            "order": [[ 1, "desc" ]] // Order on init. # is the column, starting at 0
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['client.edit','client.destroy']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif
    });

</script>
@endpush
