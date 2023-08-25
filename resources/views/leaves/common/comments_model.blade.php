<div class="modal fade" tabindex="-1" id="comment_modal">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="commentModalForm">
                @csrf
                <input type="hidden" name="commentId" id="commentId" value="">
                <div class="modal-body">
                    <div class="m-0">
                        <label for="comments" class="form-label">Comments</label>
                        <textarea class="form-control init-ck-editor" id="comments" name="comments" rows="3"></textarea>
                    </div>
                    <span class="error" id="commentsErr"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary saveComment" onclick="commentRequests($(this))">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
