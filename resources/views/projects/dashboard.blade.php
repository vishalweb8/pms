@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('project-dashboard') }}
@endsection

@section('content')

<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="m-0 page-title">My Projects</h4>
    {{-- @if(Helper::hasAnyPermission(['super-admin-project.create']))
    <a class="btn btn-primary waves-effect waves-light add-btn" href="{{route('super-admin-add-project')}}">Add
        Project</a>
    @endif --}}
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
                                <th class="d-none">Id</th>
                                <th>Project Code</th>
                                <th>Project Name</th>
                                <th>Reviewer</th>
                                <th>Created Date</th>
                                <th>Priority</th>
                                <th>Status</th>
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

{{-- @include('common.modal') --}}
@endsection
@push('scripts')
<script>
    $(function() {
        datatalbe = $('#indexDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found"
            },
            "language": {
                "infoFiltered": ""
            },
            processing: true,
            serverSide: true,
            ajax: {
                'url': '{{ route("project-dashboard") }}',
                'type': "GET"
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
                },
                {
                    data: 'project_code',
                    name: 'project_code',
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'projectReviewer.first_name',
                    name: 'projectReviewer.first_name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'projectPriority.name',
                    name: 'projectPriority.name'
                },
                {
                    data: 'projectStatus.name',
                    name: 'projectStatus.name'
                }
            ],
            "order": [[ 1, "desc" ]] // Order on init. # is the column, starting at 0
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['super-admin-project.edit']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif
    });
</script>
@endpush
