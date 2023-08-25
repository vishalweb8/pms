@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('task-dashboard') }}
@endsection

@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="card mini-stats-wid mb-3 admin-box-1">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Total Tasks</p>
                        <h4 class="mb-0">{{ $totalTask }}</h4>
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
    <div class="col-md-3">
        <div class="card mini-stats-wid admin-box-2">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">To Do Tasks</p>
                        <h4 class="mb-0">{{ $todoTask }}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center ">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-user font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mini-stats-wid admin-box-3">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">In Progress Tasks</p>
                        <h4 class="mb-0">{{ $inprogressTask }}</h4>
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
    <div class="col-md-3">
        <div class="card mini-stats-wid admin-box-4">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Completed Tasks</p>
                        <h4 class="mb-0">{{ $completedTask }}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-bar-chart-alt font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="m-0 page-title">Task Dashboard</h4>
</div>

<div class="row">
    <div class="col-md-6 mt-2">
        <div class="card  h-100 task-board-table">
            <div class="card-body ">
                <h4 class="m-0 page-title">My Task(s)</h4>
                <div class="">
                    <table id="indexDataTable"
                        class="table mb-0 table">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">Id</th>
                                <th width="15%">Project Name</th>
                                <th>Task Name</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6  mt-2">
        <div class="card h-100">
            <div class="card-body">
                <div class="sod-eod-calendar-outer sod-eod-daily-task">
                    <div id="calendar"></div>
                    <div class="status-idicator">
                        <ul class="list-unstyled p-0 d-sm-flex flex-wrap">
                            {{-- <li><span class="bg-success"></span>Done</li>
                            <li><span class="bg-danger"></span>Pending</li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('task-dashboard.woklog-modal')
@include('task-dashboard.details-modal')

@endsection
@push('scripts')
<script src="{{ asset('js/modules/task_dashboard.js') }}"></script>
<script>
    $(function() {
        $(".select2-modal").select2({
            dropdownParent: $(".select2-modal").parents('.modal-content')
        });
        datatalbe = $('#indexDataTable').DataTable({
            // searching: false,
            "lengthChange": false,
            "oLanguage": {
                "sEmptyTable": "No Records Found"
            },
            "language": {
                "infoFiltered": "",
            },
            processing: true,
            serverSide: true,
            ajax: {
                'url': '{{ route("task-dashboard-table") }}',
                'type': "GET"
            },
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    "visible": false
                },
                {
                    data: 'project_name',
                    name: 'project_name',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    class: "action",
                },
            ],
            "order": [[ 0, "desc" ]] // Order on init. # is the column, starting at 0
        });
    });
</script>
<script type="text/javascript">
    CKEDITOR.config.contentsCss = '/css/style.css';
    CKEDITOR.replace('workDescription', {
        toolbar: [{
                name: 'basicstyles',
                groups: ['basicstyles', 'cleanup'],
                items: ['Bold', 'Italic', 'Underline', ]
            },
            {
                name: 'paragraph',
                groups: ['list'],
                items: ['BulletedList']
            },
            {
                name: 'links',
                items: ['Link', 'Unlink']
            },
        ],
        on: {
            pluginsLoaded: function() {
                var cmd = this.addCommand('tag', {
                    allowedContent: {
                        input: {
                            attributes: ['type', 'value'],
                        },
                        strong: {
                            attributes: ['project_id', 'id'],
                        }
                    }
                });

                // This is a custom command, so we need to register it.
                this.addFeature(cmd);
            },
            // instanceReady: function (evt) {
            //     console.log("loaded : ", evt);
            //     this.execCommand('bulletedlist');
            // },
            // focus: function (evt) {
            //     console.log("loaded : ", evt);
            //     this.execCommand('bulletedlist');
            // },
        },
    });
    CKEDITOR.on( 'dialogDefinition', function( ev ) {
    var dialogName = ev.data.name;
        var dialogDefinition = ev.data.definition;
        if ( dialogName == 'link' ) {
            var targetTab = dialogDefinition.getContents( 'target' );
            var targetField = targetTab.get( 'linkTargetType' );
            targetField[ 'default' ] = '_blank';
        }
    });


</script>

<script>
    const getMonthlyWorkingHoursUrl = "{{route('getMonthlyWorkingHours')}}";
    const getDateWiseTaskDetailsURL = "{{ route('get-date-wise-task-details') }}";
 </script>
@endpush
