@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('employee') }}
@endsection
@section('content')
<div class="row mb-3" id="filter-counts"></div>
<div class="row">
    <div class="col-lg-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="m-0 page-title">{!! Str::title(Str::plural($module_title)) ?? '' !!}</h4>
            <div class="d-md-flex  align-items-center filter-with-search all-emp-filter">
                <div class="d-sm-flex">
                    @include('filters.team_filter')
                </div>
                @if(Helper::hasAnyPermission(['user.create']))
                <a class="btn btn-primary waves-effect waves-light add-btn" href="{{ route('user.create') }}">Add</a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body bg-body">
                        <div class=" ">
                            <table id="indexDataTable"
                                class="all-emp-table project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                                <thead>
                                    <tr>
                                        <th>Created Date</th>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone number</th>
                                        <th>Designation</th>
                                        <th>Team</th>
                                        <th>Status</th>
                                        {{-- <th class="break-all">Address</th> --}}
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
@endsection
@push('scripts')
<script src="{{ asset('/js/data-tables.js') }}"></script>
<script src="{{ asset('/js/modules/users.js') }}"></script>
<script>
    var countAllUser = '1';
    $(function() {
        datatalbe = $('#indexDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '{{ route("user.index") }}',
                'type': "GET",
                data: function(e) {
                    e.team = $('#team').val();
                    e.userType = countAllUser;
                }
            },
            columns: [
                { data: 'created_at', name: 'created_at', searchable: false, visible:false },
                {data: 'DT_RowIndex', name: 'first_name', orderable: false, searchable: false, className: 'table-id'},
                {data: 'name', name: 'full_name', orderable: true},
                {data: 'email', name: 'email'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'officialUser.userDesignation.name', name: 'officialUser.userDesignation.name', searchable: true},
                {data: 'officialUser.userTeam.name', name: 'officialUser.userTeam.name'},
                // {data: 'created_at', name: 'created_at'},
                {data: 'status', name: 'status', orderable: true, searchable: false, width:'50px'},
                // {data: 'full_address', name: 'address1', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'table-action'}
            ],
            "order": [[ 0, "desc" ]], // Order on init. # is the column, starting at 0
            'drawCallback': function(response) {
                $("#filter-counts").html(response.json.userTypes);
            }
        });
        $('#team').change(function() {
            $('#indexDataTable').DataTable().draw(true);
        });
        $(document).on('click','.card-filter', function(e){
            countAllUser = $(this).parent().find('.flex-grow-1').attr('data-leave');
            $('#leaveStatus').val(countAllUser).select2();
            $('#indexDataTable').DataTable().draw(true);
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['user.edit','user.destroy']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif
    });

</script>
@endpush
