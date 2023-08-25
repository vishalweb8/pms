@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('project-superadmin') }}
@endsection

@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="card mini-stats-wid mb-3">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        @php
                            $internal = isset($projectCountByType[0]) ? $projectCountByType[0] : 0;
                            $external = isset($projectCountByType[1]) ? $projectCountByType[1] : 0;
                        @endphp
                        <p class="text-muted fw-medium">Total Projects</p>
                        <h4 class="mb-0">{{ $allProjects }}
                        <small class="font-size-12">
                            <span class="ms-2 me-2">{{"EXT - " . $external}}</span>
                            <span>{{"INT - " . $internal}}</span>
                        </small>
                        </h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                            <span class="avatar-title">
                                <i class="bx bx-copy-alt font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Running Projects</p>
                        <h4 class="mb-0">{{ $runningProjects }}
                        <small class="font-size-12">
                            <span class="ms-2 me-2">{{"EXT - " . $runningExternal}}</span>
                            <span>{{"INT - " . $runningInternal}}</span>
                        </small>
                        </h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center ">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-archive-in font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Closed Projects</p>
                        <h4 class="mb-0">{{ $closedProjects }}
                        <small class="font-size-12">
                            <span class="ms-2 me-2">{{"EXT - " . $closedExternal}}</span>
                            <span>{{"INT - " . $closedInternal}}</span>
                        </small>
                        </h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-purchase-tag-alt font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>

            <div class="d-sm-flex align-items-center filter-with-search all-employee-leave-index">
                <div class="d-md-flex ">
                    {!! Form::select('project_status', array('' => 'Select Status')+$projectStatus, null, [ 'class' => 'form-control select2 no-search', 'id' => 'project_status']) !!}
                </div>
                <div class="d-md-flex ">
                    {!! Form::select('project_type', array('' => 'Select Project Type','0' => 'Internal', '1' => 'External'), null, [ 'class' => 'form-control select2 no-search', 'id' => 'project_type']) !!}
                </div>
                @if(Helper::hasAnyPermission(['super-admin-project.create']))
                <a class="btn btn-primary waves-effect waves-light" href="{{route('super-admin-add-project')}}">Add
                    Project</a>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-body bg-body">
                <div class="">
                    <table id="indexDataTable"
                        class="project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Project Id</th>
                                <th>Project Code</th>
                                <th>Project Name</th>
                                <th>Payment</th>
                                {{-- <th>Amount (currency)</th> --}}
                                <!-- <th>Allocation</th>
                                <th>Team Lead</th>
                                <th>Reviewer</th> -->
                                <th>Project Type</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <!-- <th>Technology</th>
                                <th>Client</th> -->
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

@include('common.modal',['modal_size' => 'modal-lg'])
@endsection
@push('scripts')
<script src="{{ asset('js/modules/projects.js')}}"></script>
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
                'url': '{{ route("super-admin-project-dashboard") }}',
                'type': "GET",
                data: function(e) {
                    e.project_type = $('#project_type').val();
                    e.project_status = $('#project_status').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'table-id'
                },
                {
                     data: 'id',
                     name: 'id',
                     visible: false
                },
                {
                    data: 'project_code',
                    name: 'project_code'
                },
                {
                    data: 'name',
                    name: 'name'
                },

                {
                    data: 'projectPaymentType.type',
                    name: 'projectPaymentType.type'
                },
                // {
                //     data: 'amount',
                //     name: 'amount'
                // },
                // {
                //     data: 'projectAllocation.type',
                //     name: 'projectAllocation.type'
                // },
                // {
                //     data: 'projectTeamLead.first_name',
                //     name: 'projectTeamLead.first_name'
                // },
                // {
                //     data: 'projectReviewer.first_name',
                //     name: 'projectReviewer.first_name'
                // },
                {
                    data: 'project_type',
                    name: 'project_type',
                    orderable: true,
                    searchable: false,
                },
                {
                    data: 'projectPriority.name',
                    name: 'projectPriority.name'
                },
                {
                    data: 'projectStatus.name',
                    name: 'projectStatus.name'
                },
                // {
                //     data: 'technologies_ids',
                //     name: 'technologies_ids'
                // },
                // {
                //     data: 'projectClient.first_name',
                //     name: 'projectClient.first_name'
                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'table-action'
                }
            ],
            "order": [[ 1, "desc" ]], // Order on init. # is the column, starting at 0
        });
        console.log(datatalbe);
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['super-admin-project.edit']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif
    });

$('#project_type').change(function() {
    $('#indexDataTable').DataTable().draw(true);
});
$('#project_status').change(function() {
    $('#indexDataTable').DataTable().draw(true);
});
</script>
@endpush
