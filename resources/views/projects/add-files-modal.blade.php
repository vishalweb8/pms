<div class="modal fade" tabindex="-1" id="filesModal">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Document Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
            <div class="modal-body">
                <div class="m-0">
                    <div id="name-container-list">
                        <div class="form-group">
                            <label class="col-form-label">Document Name</label>
                            <input type="text" name="file_name" class="form-control files" id="fileName" />
                        </div>
                        <span class="error" id="fileNameErr"></span>
                    </div>
                    <div id="name-container-list">
                        <div class="form-group">
                            <label class="col-form-label">Document Link</label>
                            <input type="text" name="name" class="form-control files" id="filesLink" />
                        </div>
                        <span class="error" id="filesLinkErr"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer m-auto border-0">
                <button type="button" class="btn btn-primary saveFilesLink">Save</button>
            </div>
        </div>
    </div>
</div>
