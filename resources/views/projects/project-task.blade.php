<div class="tab-pane {{ ($selectedTab==2) ? 'active' : '' }}" id="task" role="tabpanel">
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <h4 class="m-0 page-title">Tasks Listing</h4>
        {{-- @if (Helper::hasAnyPermission(['project-task.create']))
        <a class="btn btn-primary waves-effect waves-light add-btn"
            href="{{ route('add-project-task', $project->id) }}">Add
            Task</a>
        @endif --}}
        <a class="btn btn-primary waves-effect waves-light add-btn"
            href="{{ route('add-project-task', $project->id) }}">Add
            Task</a>
    </div>
    {{-- Count boxes --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card mini-stats-wid mb-3 mb-2">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">To Do</p>
                            <h4 class="mb-0" id="todoTaskCount">{{ $todoTaskCount ? $todoTaskCount : 0 }}</h4>
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
            <div class="card mini-stats-wid mb-2">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">In Progress</p>
                            <h4 class="mb-0" id="inprogressTaskCount">{{ $inprogressTaskCount ?
                                $inprogressTaskCount : 0 }}</h4>
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
        <div class="col-md-4">
            <div class="card mini-stats-wid mb-2">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Completed</p>
                            <h4 class="mb-0" id="completedTaskCount">{{ $completedTaskCount ?
                                $completedTaskCount : 0 }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-check font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="page-title-box d-xl-flex  align-items-center justify-content-end">

        <div
            class="d-sm-flex flex-wrap flex-lg-nowrap mt-3 mt-xl-0  align-items-center filter-with-search all-emp-task-filter">
            <div class="d-sm-flex">
                <div class="form-group mb-0 date-f-w">
                    {{-- <div class="input-group">
                        <input class="form-control " placeholder="Select Date" id="selectedDate" change="getData()"
                            data-provide="datepicker" data-date-autoclose="true" data-date-format="dd-mm-yyyy"
                            name="select_date" type="text" value="07-02-2022" autocomplete="off">
                    </div> --}}
                    @include('filters.date_filter')
                </div>
                <div class="form-group ms-sm-3 mb-0">
                    <select class="form-control select2 no-search" name="status" id="status">
                        <option value="">Select Status</option>
                        <option value="todo">To Do</option>
                        <option value="inprogress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="form-group ms-sm-3 mb-0">
                    <select class="form-control select2 no-search" name="priority" id="priority">
                        <option value="">Select Priority</option>
                        @foreach ($priorities as $key => $priority)
                        <option value="{{ $key }}">{{ $priority }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body bg-body">
                    <table id="indexDataTable"
                        class="project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="d-none">Id</th>
                                <th>Task</th>
                                <th>Assigned to</th>
                                <th>Date</th>
                                <th>Priority</th>
                                <th>Status</th>
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

    <!-- end row -->

</div>
