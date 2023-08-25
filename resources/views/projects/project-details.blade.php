@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('project-details', $project->project_code.' - '.$project->name, $project->id) }}
@endsection
@section('content')

<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="m-0 page-title">{{ $project->project_code }} - {{ $project->name }}</h4>
    <!-- Nav tabs -->

</div>
<div class="project-detail-wrapper">
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card mb-1">
                <div class="card-body">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link tab-number {{ ($selectedTab==1) ? 'active' : '' }}" id="overviewTab"
                                data-bs-toggle="tab" href="#overview" role="tab" data-tab-number="1" data-tab-name="selectedTab">
                                <span class="d-sm-block">Overview</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link tab-number {{ ($selectedTab==2) ? 'active' : '' }}" id="taskTab"
                                data-bs-toggle="tab" href="#task" role="tab" data-tab-number="2" data-tab-name="selectedTab">
                                <span class="d-sm-block">Tasks Listing</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content pt-2">
        @include('projects.project-overview')
        <!--overview-tabs-content-end--->
        @include('projects.project-task')
        <!--task-tabs-content-end--->
    </div>
    <!--tabs-content-end--->
</div>
@include('projects.add-files-modal')
@include('projects.add-member-modal')

@push('scripts')
<script type="text/javascript">
    var saveMembersURL = "{{ route('save-project-members') }}";
    var changePriorityURL = "{{ route('change-project-priority') }}";
    var saveFileLinksURL = "{{ route('save-project-files-link') }}";
    var deleteFileLinksURL = "{{ route('delete-project-files-link') }}";
    var changeSelectedTabURL = "{{ route('change-selected-tab-value') }}";
    var getNewDetailsOfTaskURL = "{{ route('get-new-details-of-task', "$project->id") }}";

    var projectTaskDatatableURL = "{{ route('project-details', "$project->id") }}";
    $(function() {
        datatalbe = $('#indexDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found",
            },
            language: {
                searchPlaceholder: "Search by Task Name"
            },
            processing: true,
            serverSide: true,
            "drawCallback": function(settings) {
                // location.reload();
                changeCounts();
            },
            ajax: {
                'url': projectTaskDatatableURL,
                'type': "GET",
                data: function(e) {
                    e.date = $('#selectedDate').val();
                    e.status = $('#status').val();
                    e.priority = $('#priority').val();
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
                    "visible": false
                    // orderable: false,
                },
                {
                    data: 'name',
                    name: 'name',
                    // orderable: false,
                },
                {
                    data: 'assignee_ids',
                    name: 'assignee_ids'
                },
                {
                    data: 'date',
                    name: 'created_at'
                },
                {
                    data: 'priority',
                    name: 'priority_id'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'table-action'
                }
            ],
            "order": [[ 1, "desc" ]] // Order on init. # is the column, starting at 0
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['project-task.edit']))
        datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif
    });
    $('#selectedDate').change(function() {
        $('#indexDataTable').DataTable().draw(true);
    });
    $('#status').change(function() {
        $('#indexDataTable').DataTable().draw(true);
    });
    $('#priority').change(function() {
        $('#indexDataTable').DataTable().draw(true);
    });

    function changeCounts() {
        // console.log("settings", settings);
        $.ajax({
            url: getNewDetailsOfTaskURL,
            type: "GET",
            data: {
                // id: projectId,
            },
            success: function (response) {
                // alert("hello");
                // toastr.success(response.success);
                console.log("response.data : ", response.data);
                $("#totalHours").html(response.data.updatedTaskHours);
                $("#todoTaskCount").html(response.data.todoTaskCount);
                $("#inprogressTaskCount").html(response.data.inprogressTaskCount);
                $("#completedTaskCount").html(response.data.completedTaskCount);
                // setTimeout(function () {
                //     location.reload();
                // }, 3100);
            },
            error: function (response) {
                // toastr.error(response.message);
            },
        });
    }
</script>
<script src="{{ asset('js/modules/projects.js') }}"></script>

@endpush

@endsection
