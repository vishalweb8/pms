@extends($theme)
@section('breadcrumbs')
{{ isset($dailyTask) ? Breadcrumbs::render('daily-task-edit') : Breadcrumbs::render('daily-task-create') }}
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h5 class="mb-0 lh-1"> {{ $dailyTask->userTask->full_name }} (
                {{ \Helper::getDateFormat($dailyTask->created_at) }} )
            </h5>
            <div>
                <a class="btn btn-secondary waves-effect waves-light cancel-btn me-3"
                    href="{{ url()->previous() }}">Back</a>
                <button class="btn btn-primary waves-effect waves-light add-btn daily-task-btn" type="button"
                    onclick="submitDailyTask()">Save</button>
            </div>
        </div>
    </div>
</div>
<form id="dailyTaskForm" method="POST" action="{{ route('edit-daily-task-store') }}">
    <div class="row sod-eod-outer">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="resource-status-card card-body d-sm-flex align-items-end justify-content-between">
                    <div class="media align-items-center">
                        <div class="me-3">
                            <div class="avatar-md">
                                <img src="/images/resource.svg" alt="img" />
                            </div>
                        </div>
                        <div class="d-flex ms-2">
                            <div class="text-muted">

                                @if($dailyTask->verified_by_TL == 1)
                                <input class="form-check-input" type="checkbox" id="" name="verified_by_tl" value="1"
                                    checked>
                                @else
                                <input class="form-check-input" type="checkbox" id="" name="verified_by_tl" value="0">
                                @endif
                                Verify By TL
                            </div>
                        </div>
                        <div class="d-flex ms-4">
                            <div class="text-muted">

                                @if(Helper::hasAnyPermission(['permission.edit','permission.list']))
                                @if($dailyTask->verified_by_Admin == 1)
                                <input class="form-check-input" type="checkbox" name="verified_by_admin" value="1"
                                    checked>
                                @else
                                <input class="form-check-input" type="checkbox" name="verified_by_admin" value="0">
                                @endif
                                @endif
                                Verify By Admin
                            </div>
                        </div>
                    </div>
                    <div class="d-sm-flex">
                        <div class="form-group me-3">
                            <label class="col-form-label">Resource Status</label>
                            {!! Form::select('emp_status', array('' => 'Select...') + config('constant.emp_status'),
                            isset($dailyTask) ? $dailyTask->emp_status : null,['class' =>
                            'form-control select2 no-search']) !!}
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Project Status</label>
                            {!! Form::select('project_status', array('' => 'Select...') +
                            config('constant.project_type'), isset($dailyTask) ? $dailyTask->project_status :
                            null,['class' =>
                            'form-control select2']) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12 col-xl-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>SOD Details</h5>
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    <input type="hidden" name="id" id="dailyTaskID" value="{{isset($dailyTask) ? $dailyTask->id : ''}}">
                    <input type="hidden" name="project_id" id="project_id" value="">
                    <input type="hidden" name='sod_eod_date' value="{{isset($date) ? $date : ''}}">
                    @csrf
                    <div class="form-group mb-3 only-view-text">
                        <textarea class="form-control" placeholder="Enter Your SOD" name="" id="sodTextArea" rows="5">{{isset($dailyTask) ? $dailyTask->sod_description : ''}}
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@push('scripts')

<script type="text/javascript">
var taskVerifiedByAdminURL = "{{ route('task-verified-by-admin') }}";
var dailyTaskUpdate = "{{ route('add-sod') }}";
var getLeaves = "{{ route('get-leaves') }}";
var getAllHolidayDate = "{{ route('holiday-get-all-dates') }}";
var getAllHolidayDate = "{{ route('holiday-get-all-dates') }}";
var fetchRecords = "{{ route('fetch-record') }}";
var addTaskRoute = "{{ route('work-create',':date') }}";
var getLeaves = "{{ route('get-leaves') }}";
var taskVerifiedByAdminURL = "{{ route('task-verified-by-admin') }}";
var page = 1;
var ENDPOINT1 = "{{ url('/') }}";
</script>
<script src="{{ asset('js/modules/daily_task.js') }}"></script>

<script>
CKEDITOR.config.contentsCss = '/css/style.css';
CKEDITOR.config.readOnly = true;
CKEDITOR.replace('sodTextArea', {
    toolbar: [],
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
    }
});
CKEDITOR.replace('eodTextArea', {
    toolbar: [],
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
        }
    }
});
</script>
@endpush

@endsection
