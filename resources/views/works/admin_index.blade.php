@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('all-employees') }}
@endsection
@section('content')

<div class="page-title-box d-xxl-flex  align-items-center justify-content-between">
    <h4 class="m-0 page-title min-w-150">{{ Str::title($module_title) }}</h4>
    <div
        class="d-sm-flex flex-wrap flex-lg-nowrap mt-3 mt-xxl-0  align-items-center filter-with-search all-emp-task-filter">
        <div class="d-sm-flex flex-wrap sod-eod-all-emp-filter">

            <div class="form-group ms-sm-3 mb-0 date-f-w">
                @include('filters.date_filter')
            </div>
            <div class="form-group ms-sm-3 mb-0">
                @if(\Auth::user()->role_id == '1')
                @include('filters.team_filter')
                @else
                @if( $loggedInUser->officialUser &&  $loggedInUser->officialUser->userTeam)
                <select class="form-control select2 no-search" name="team" id="team" disabled>
                    <option value="{{ $loggedInUser->officialUser->userTeam->id }}">{{
                        $loggedInUser->officialUser->userTeam->name }}</option>
                </select>
                @endif
                @endif
            </div>
            <div class="form-group ms-sm-3 mb-0">
                @include('filters.project_status_filter')
            </div>
            <div class="form-group ms-sm-3 mb-0">
                @include('filters.resource_status_filter')
            </div>
            <div class="form-group ms-sm-3 mb-0">
                <select class="select2 form-control no-search" name="verified-by-tl" id="verified-by-tl">
                    <option value="">Verified by TL</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group ms-sm-3 mb-0">
                <select class="select2 form-control no-search" name="verified-by-admin" id="verified-by-admin">
                    <option value="">Verified by Admin</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            {{-- <div class="form-group ms-sm-3 mb-0">
                @include('filters.projects_filter')
            </div> --}}
            <div class="form-group ms-sm-3 mb-0 search-emp-filter">
                <input type="text" class="form-control" name="search" id="search-employee" placeholder="Search Employee"
                    value="">
            </div>
        </div>
        {{-- <a href="javascript:void(0)"
            class="text-truncate ms-3 btn btn-primary waves-effect waves-light dailyTaskFilterBtn"
            type="button">Filter</a> --}}
    </div>
    {{-- @if(Helper::hasAnyPermission(['super-admin-project.create']))
    <a class="btn btn-primary waves-effect waves-light" href="{{route('work-create')}}">Add Task</a>
    @endif --}}
</div>
<div class="row">
    <div class="col-12">
        {{-- <div class="card mt-2 border-0 ">
            <div class="card-body ">
                <div class=" ">
                    <table id="indexDataTable"
                        class="sod-eod-list-table table table-bordered w-100 align-middle custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Date</th>
                                <th>SOD</th>
                                <th>EOD</th>
                                <th>Resource Status</th>
                                <th>Project Status</th>
                                <th>Verified by TL</th>
                                <th>Verified by Admin</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}

        <div class="table-responsive-header table-responsive">
            <table class="table project-list-table table-nowrap align-middle table-borderless">
                <thead>
                    <tr>
                        <!-- <th scope="col" style="width: 100px"></th> -->
                        <th scope="col">Employee Name </th>
                        <th scope="col"> Date</th>
                        <th scope="col">Resource Status</th>
                        <th scope="col">Project Status</th>
                        <th scope="col">Verified by TL</th>
                        <th scope="col">Verified by Admin</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="accordion emp-accordion-main" id="accordionExample">
            {{-- @include('works.partials.daily-task-accordian') --}}
        </div>
        <!-- Data Loader -->
        <div class="auto-load text-center">
            <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path fill="#000"
                    d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                        from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                </path>
            </svg>
        </div>

    </div>

</div>
@endsection
@push('scripts')
<script type="text/javascript">
var DataUpdate = "{{ __('messages.MSG_UPDATE',['name' => 'Data']) }}";
var SometingWentWrong = "{{ __('messages.ERR_SOMETING_WENT_WRONG') }}";
var DataVerified = "{{ __('messages.MSG_SUCCESS_VERIFY',['name' => 'Task']) }}";
var taskVerifiedByAdminURL = "{{ route('task-verified-by-admin') }}";
var page = 1;
var ENDPOINT1 = "{{ url('/') }}";

var getAllHolidayDate = "{{ route('holiday-get-all-dates') }}";
var fetchRecords = "{{ route('fetch-record') }}";
var addTaskRoute = "{{ route('work-create') }}";
var getLeaves = "{{ route('get-leaves') }}";
var editTaskRoute = "{{ route('work-edit','id') }}";
var indexRoute = "{{ route('work-index') }}";
var addedTodayTask = false;
var saveActions = "{{ route('edit-daily-task-store') }}";
</script>
<script src="{{ asset('js/modules/daily_task.js')}}"> </script>
<script type="text/javascript">
const urlParams = new URLSearchParams(window.location.search);
$(document).ready(function() {
    $(document).on('click', '.dailyTaskFilterBtn', function() {
        window.page = 1;
        $.ajax({
            data: {
                date: $('#selectedDate').val(),
                team: $('#team').val(),
                resourceStatus: $('#resource-status').val(),
                projectStatus: $('#project-status').val()
            },
            url: ENDPOINT1 + "/works/filter-daily-task?page=" + page,
            type: "GET",
            success: function(response) {
                if (response.length == 0) {
                    $('#accordionExample').empty();
                    $(".auto-load").show();
                    $(".auto-load").html(
                        `<div class="bg-light-primary p-2 text-primary"><i class="bx bx-confused font-size-22"></i><h6>We don't have more data to display</h6></div>`
                    );
                    return;
                }
                $(".auto-load").hide();
                $('#accordionExample').empty();
                $('#accordionExample').append(response);
                $('.select2').select2();
            },
            error: function(response) {},
        });
    })
});

//    $(function() {
//       datatalbe = $('#indexDataTable').DataTable({
//          processing: true,
//          serverSide: true,
//          ajax: {
//             'url': '{{ route("admin-index") }}',
//             'type': "GET"
//          },
//          columns: [{
//                data: 'DT_RowIndex',
//                name: 'DT_RowIndex',
//                orderable: false,
//                searchable: false,
//                className: 'table-id'
//             },
//             {
//                data: 'userTask.first_name',
//                name: 'userTask.first_name',
//             },
//             {
//                data: 'current_date',
//                name: 'current_date'
//             },
//             {
//                data: 'sod_description',
//                name: 'sod_description'
//             },
//             {
//                data: 'eod_description',
//                name: 'eod_description'
//             },
//             {
//                data: 'emp_status',
//                name: 'emp_status'
//             },
//             {
//                data: 'project_status',
//                name: 'project_status'
//             },
//             {
//                data: 'verified_by_tl',
//                name: 'verified_by_tl'
//             },
//             {
//                data: 'verified_by_admin',
//                name: 'verified_by_admin'
//             },
//             {
//                data: 'action',
//                name: 'action',
//                orderable: false,
//                searchable: false,
//                className: 'table-action'
//             }
//          ],
//          "order": [
//             [1, "desc"]
//          ], // Order on init. # is the column, starting at 0
//       });
//       datatalbe.columns(datatalbe.columns().count() - 1).visible(false);
//       @if(Helper::hasAnyPermission(['super-admin-project.edit']))
//       datatalbe.columns(datatalbe.columns().count() - 1).visible(true);
//       @endif
//    });
</script>
@endpush
