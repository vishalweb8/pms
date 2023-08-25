<div class="modal fade" tabindex="-1" id="membersModal">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Team Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
            <div class="modal-body">
                <div class="m-0">
                    <label class="col-form-label">Select Team Members</label>
                    {!! Form::select('members_ids[]',$allUsers,isset($projectMembers) ?
                    explode(',',$projectMembers) :
                    null,['multiple' =>
                    'multiple','class' => 'form-control select2', 'id' => 'members_id']) !!}
                </div>
                <span class="error" id="addMembersErr"></span>
            </div>
            <div class="modal-footer m-auto border-0">
                <button type="button" class="btn btn-primary saveMembers">Save</button>
            </div>

        </div>
    </div>
</div>
