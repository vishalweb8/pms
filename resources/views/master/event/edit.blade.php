<div class="modal fade" id="Editevent" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="Editevent" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <div class="alert alert-danger" style="display:none"></div> --}}
            <form id="edit_event" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body" id="eventBody">
                    <input type="hidden" name="eventId" id="eventId">
                    <div class="row">
                            <div class="form-group mb-3 col-md-6">
                                <label class="col-form-label">Event Name</label>
                                <input type="text" class="form-control" value="" id="edit-eventName" name="event_name" placeholder="Event name">
                                <span class="error text-danger" id="eventNameErrMsg"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="status" class="col-form-label">Event Date</label>
                                <input class="form-control datepicker" type="text" id="edit-eventDate" name="event_date"
                                placeholder="Select Event Date"
                                data-provide="datepicker"
                                data-date-autoclose="true"
                                data-date-format="dd-mm-yyyy">
                                <span class="error text-danger" id="eventDateErrMsg"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6 position-relative" id="timepicker-input-group1">
                                <label for="status" class="col-form-label">Start Time</label>
                                <input id="timepicker" type="text" name="start_time" class="form-control" placeholder="Select Event Time" data-provide="timepicker">
                                <span class="error text-danger" id="eventStartTimeErrMsg"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6 position-relative" id="timepicker-input-group7">
                                <label for="status" class="col-form-label">End Time</label>
                                <input id="timepicker7" type="text" name="end_time" class="form-control" placeholder="Select Event Time" data-provide="timepicker">
                                <span class="error text-danger" id="eventEndTimeErrMsg"></span>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label class="col-form-label">Description</label>
                                <textarea class="form-control" id="edit-description" rows="5" name="description"> </textarea>
                                <span class="error text-danger" id="descriptionErrMsg"></span>
                            </div>
                            <div class="mb-3 col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a id="view_event" target="_blank"><span class="eventFile"></span></a>
                                    <a id="event-File-Show" class="eventFileShow cursor-pointer" title="Edit"><i class="far fa-edit text-primary me-2"></i></a>
                                </div>
                                <x-fileUpload allowedFiles=true fileType=".doc, .pdf, .docx" fileSize="10 MB">
                                    <i class="mdi mdi-file-pdf me-2"></i>
                                </x-fileUpload>
                                <span class="error text-danger fileUrlErrMsg" id=""></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
    </div>
</div>
