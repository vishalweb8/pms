<div class="modal fade timeEntry" id="TimeEntry" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Log File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importForm">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label class="col-form-label">Upload File</label>
                            <x-fileUpload allowedFiles=true fileType=".xls, .xlsx" fileSize=false>
                                <i class="mdi mdi-file-pdf me-2"></i>
                            </x-fileUpload>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveTimeEntry">Save</button>
            </div>
        </div>
    </div>
</div>
