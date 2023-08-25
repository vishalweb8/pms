<!-- sample modal content -->
<div id="dailyTaskModal" class="modal fade" tabindex="-1" aria-labelledby="dailyTaskModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @if (isset($taskDescription) && !empty($taskDescription))
                <div class="modal-header">
                    <h5 class="modal-title" id="dailyTaskModalTitle">{{ $taskDescription->project_id }} - {{ $taskDescription->projectTask->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form id="updateDescriptionForm">
                        <input type="hidden" name="project_id" id="projectModalId" value="">
                        <input type="hidden" name="daily_task_id" id="dailyTaskId" value="">
                        <div class="form-group mb-3">
                            <label class="col-form-label" for="newDescription" id="descriptionLabel"></label>
                            <textarea class="form-control" rows="5" name="new_description" id="newDescription" placeholder="Description">{{ $taskDescription->sod_description }}</textarea>

                            <span></span>
                        </div>
                        <span></span>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="updateFormBtn" data-flag="" onclick="updateDescription(this)">Save</button>
                </div>
            @endif
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
