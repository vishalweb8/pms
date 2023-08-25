<div class="modal fade worklogModal" id="worklogModal">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="align-items: baseline;">
                <div class="flex">
                    <h5 class="modal-title">Add Worklog</h5>
                    <span id="project_task_details"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="url" id="dataUrl" value="">
            <input type="hidden" name="task_id" id="taskId" value="">
            <input type="hidden" name="project_id" id="projectId" value="">
            <input type="hidden" name="user_id" id="taskUserId" value="{{ Auth::user()->id }}">
            <div class="form-group mb-0  date-f-w">
                <label for="logDate" class="col-form-label">Date</label>
                <input class="form-control log_date_picker" placeholder="Select Date" id="logDate" data-provide="datepicker"
                    data-date-autoclose="true" data-date-format="dd-mm-yyyy" name="log_date" type="text" autocomplete="off"
                    value="">
                <span class="error" id="logDateErr"></span>
            </div>
            <div class="form-group mt-3">
                <label for="logTime" class="col-form-label">Log Time (h.mm)</label>
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control select2-modal" name="log_hours" id="logHours">
                            @foreach (config('constant.task_hours') as $hours)
                            @if (old('log_hours') == $hours)
                            <option value="{{ $hours }}" selected>{{ $hours }}</option>
                            @else
                            <option value="{{ $hours }}">{{ $hours }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        {!! Form::select('log_minutes', config('constant.task_minutes') , null, ['class'=> 'form-control select2-modal', 'id' => 'logMinutes' ]) !!}
                    </div>
                </div>
                <span class="error" id="logTimeErr"></span>
            </div>
            <div class="form-group mt-3">
                <label for="workDescription" class="col-form-label">Work Description</label>
                <textarea class="form-control" placeholder="Enter Work Description" name="description" id="workDescription"
                    rows="5">{{ old('description') }}
                                        </textarea>
                <span class="error" id="descriptionErr"></span>
            </div>
            <div class="form-group d-flex justify-content-center mt-4 mb-2">
                <button type="submit" class="btn btn-primary w-md" id="saveWorkLog">Save</button>
            </div>
        </div>
    </div>
    </div>
</div>
