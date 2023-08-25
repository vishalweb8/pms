@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('daily-task') }}
@endsection

@section('content')

<div class="page-title-box d-sm-flex align-items-center justify-content-between date-list-outer">
    <h4 class="m-0 page-title">{{ Str::title($module_title) }}</h4>
    <div class="d-flex select-inner">
        <div class="form-group">
            <select class="form-control select2" id="daily-task-year">
                @for ($i = Carbon\Carbon::now()->format('Y'); $i >= config('constant.start_year'); $i--)
                <option value="{{$i}}">{{$i}}</option>
                @endfor
            </select>
        </div>
        <div class="form-group">
            <select class="form-control select2" id="daily-task-month">
            </select>
        </div>
        {{-- <div class="form-group">
            <button class="btn btn-primary waves-effect waves-light daily-task-filter">Filter</button>
        </div> --}}

    </div>
</div>
<div class="row d-none">
    <div class="col-12">
        <div class="date-list-outer d-sm-flex">
            <div class="date-inner" id="list-wrapper">
                <div class="scroller scroller-left"><i class="mdi mdi-chevron-left"></i></div>
                <div class="scroller scroller-right"><i class="mdi mdi-chevron-right"></i></div>
                <div class="wrapper">
                    <div class="nav nav-tabs list days-list-div daysListing" id="list" role="tablist">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6  mt-2">
        <div class="card h-100">
            <div class="card-body">
                <div class="sod-eod-calendar-outer">
                    <div id="calendar" joinDate="{{ $joinDate ? $joinDate->joining_date : ''  }}"></div>
                    <div class="status-idicator">
                        <ul class="list-unstyled p-0 d-sm-flex flex-wrap">
                            <li><span class="bg-success"></span>Done</li>
                            <li><span class="bg-danger"></span>Pending</li>
                            <li><span class="bg-info"></span>On Leave</li>
                            <li><span class="bg-warning"></span>Holiday/Weekend</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <div class="card  h-100">
            <div class="card-body" id="dailyTaskBorad">
                @include('works.partials.daily-task-list')
            </div>
        </div>
    </div>
</div>

@include('common.modal')
@endsection
@push('scripts')
<script src="{{ asset('/js/data-tables.js') }}"></script>
<script src="{{ asset('js/modules/daily_task.js') }}"></script>

<script>
    var getAllHolidayDate = "{{ route('holiday-get-all-dates') }}";
var fetchRecords = "{{ route('fetch-record') }}";
var addTaskRoute = "{{ route('work-create') }}";
var getLeaves = "{{ route('get-leaves') }}";
var editTaskRoute = "{{ route('work-edit','id') }}";
var indexRoute = "{{ route('work-index') }}";
var addedTodayTask = false;
var taskVerifiedByAdminURL = "{{ route('task-verified-by-admin') }}";
var page = 1;
var ENDPOINT1 = "{{ url('/') }}";
var TaskEntryDate = "{{ (isset(Auth::user()->officialUser->task_entry_date) && !empty(Auth::user()->officialUser->task_entry_date)) ? \Carbon\Carbon::parse(Auth::user()->officialUser->task_entry_date)->format('Y-m-d') :'2022-01-31' }}";
var saveActions = "{{ route('edit-daily-task-store') }}";
if(TaskEntryDate) {
    TaskEntryDate = moment(TaskEntryDate);
}
</script>
@endpush
