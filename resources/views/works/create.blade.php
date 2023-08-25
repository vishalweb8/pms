@extends($theme)
@section('breadcrumbs')
{{ isset($dailyTask) ? Breadcrumbs::render('daily-task-edit') : Breadcrumbs::render('daily-task-create') }}
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="page-title-box d-flex align-items-center justify-content-end">
            <a class="btn btn-secondary waves-effect waves-light cancel-btn me-3"
                href="{{ url()->previous() }}">Back</a>
            <button class="btn btn-primary waves-effect waves-light add-btn daily-task-btn" type="button"
                onclick="submitDailyTask()">Save</button>
        </div>
    </div>
</div>
<form id="dailyTaskForm" method="POST" action="{{ route('add-sod') }}">
    <div class="row sod-eod-outer">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="resource-status-card card-body d-sm-flex align-items-end justify-content-between">

                    <div class="media">
                        <div class="me-3">
                            <div class="avatar-md">
                                <img src="/images/resource.svg" alt="img" />
                            </div>
                        </div>
                        <div class="align-self-center media-body">
                            <div class="text-muted">
                                <!-- <p class="mb-2">Welcome To Leave Dashboard</p> -->
                                <h5 class="mb-1 font-size-16 fw-medium ms-3">Enter your <b>SOD</b> with status</h5>
                            </div>
                        </div>
                    </div>
                    <div class="d-sm-flex">
                        <div class="form-group me-3">
                            <label class="col-form-label">Resource Status</label>
                            {!! Form::select('emp_status', array('' => 'Select...') + config('constant.emp_status'),
                            isset($dailyTask) ? $dailyTask->emp_status : null,['class' =>
                            'form-control select2']) !!}
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
        <div class="col-md-12 col-xl-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>SOD Details</h5>
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    <input type="hidden" name="id" id="dailyTaskID" value="{{isset($dailyTask) ? $dailyTask->id : ''}}">
                    <input type="hidden" name="project_id" id="project_id" value="">
                    <input type="hidden" name='sod_eod_date' value="{{isset($date) ? $date : ''}}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="col-form-label">Projects</label>
                        <select class="form-control select2" aria-label="Floating label select example"
                            name="sod_project" id="sod_project">
                            <option value="" selected disabled>Select Your Project</option>
                            @forelse ( $projects as $project)
                            <option value="{{ $project['id'] }}">{{ $project['project_code']." - ".$project['name'] }}
                            </option>
                            @empty
                            <option value="" disabled>No Project Assigned</option>
                            @endforelse
                            {{-- <option value="other">Other</option> --}}
                        </select>
                    </div>
                    <!-- <div class="row">
                        <div class="col-sm-6 form-group mb-3">
                            <label class="col-form-label">Resource Status</label>
                            {!! Form::select('emp_status', array('' => 'Select Your Status') + config('constant.emp_status'), isset($dailyTask) ? $dailyTask->emp_status : null,['class' =>
                            'form-control form-select2']) !!}
                        </div>
                        <div class="col-sm-6 form-group mb-3">
                            <label class="col-form-label">Project Status</label>
                            {!! Form::select('project_status', array('' => 'Select Your Project Status') + config('constant.project_type'), isset($dailyTask) ? $dailyTask->project_status : null,['class' =>
                            'form-control form-select2']) !!}
                        </div>
                    </div> -->

                    <div class="form-group mb-3">
                        <textarea class="form-control" placeholder="Enter Your SOD" name="sod_description"
                            id="sodTextArea" rows="5">{{isset($dailyTask) ? $dailyTask->sod_description : ''}}
                        </textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xl-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Sample SOD Format</h5>
                    <div class="form-group mb-3 only-view-text">
                        <textarea class="form-control" placeholder="Enter Your" name="" id="sampleTextArea" rows="5"
                            disabled>
                            <p><strong id="project_id" project_id="3">102 - Test Project</strong>
                            <ul>
                                <li>Design new inner pages</li>
                                <li>Check login issue</li>
                                <li>R&D on login with gmail</li>
                                <li>Set API for project listing</li>
                            </ul>
                            <p><strong id="project_id" project_id="3">96 - Social Portal</strong>
                            <ul>
                                <li>Design home page</li>
                                <li>Friend request API integration</li>
                                <li>Add Validation for contact page</li>
                            </ul>

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
var page = 1;
var ENDPOINT1 = "{{ url('/') }}";
</script>
<script src="{{ asset('js/modules/daily_task.js') }}"></script>

<script>
CKEDITOR.config.contentsCss = '/css/style.css';
editor = CKEDITOR.replace('sampleTextArea');
editor.config.readOnly = true,
    CKEDITOR.replace('sodTextArea', {
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

CKEDITOR.replace('eodTextArea', {
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
        }
    }
});

CKEDITOR.replace('sampleTextArea', {
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
