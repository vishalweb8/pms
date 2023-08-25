<div class="modal fade" tabindex="-1" id="reject_modal">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectLeaveForm">
                @csrf
                <input type="hidden" name="leave_id" id="leaveId" value="">
                <div class="modal-body">
                    <div class="m-0">
                        <label for="rejectComments" class="form-label">Comments</label>
                        <textarea class="form-control" id="rejectComments" rows="3"></textarea>
                    </div>
                    <span class="error" id="rejectCommentsErr"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" onclick="rejectLeave()">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
