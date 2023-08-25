<div class="modal fade AddEvent" id="Addevent" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="Addevent" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="event_form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">

                        <div class="form-group mb-3 col-md-6">
                            <label class="col-form-label">Event Name</label>
                            <input type="text" class="form-control" value="" id="eventName" name="event_name" placeholder="Event name">
                            <span class="error text-danger" id="eventNameMsg"></span>
                        </div>

                        <div class="form-group mb-3 col-md-6">
                            <label for="status" class="col-form-label">Event Date</label>
                            <input class="form-control datepicker" type="text" id="eventDate" name="event_date" placeholder="Select Event Date" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd-mm-yyyy">
                            <span class="error text-danger" id="eventDateMsg"></span>
                        </div>
                        <div class="form-group mb-3 col-md-6 position-relative" id="timepicker-input-group5">
                            <label for="status" class="col-form-label">Event Start Time</label>
                            <input id="timepicker5" type="text" name="start_time" class="form-control" placeholder="Select Start Time" data-provide="timepicker">
                            <span class="error text-danger" id="eventStartTimeMsg"></span>
                        </div>
                        <div class="form-group mb-3 col-md-6 position-relative" id="timepicker-input-group6">
                            <label for="status" class="col-form-label">Event End Time</label>
                            <input id="timepicker6" type="text" name="end_time" class="form-control" placeholder="Select End Time" data-provide="timepicker">
                            <span class="error text-danger" id="eventEndTimeMsg"></span>
                        </div>

                        <div class="form-group mb-3 col-md-12">
                            <label class="col-form-label">Description</label>
                            <textarea class="form-control" id="description" rows="5" name="description"> </textarea>
                            <span class="error text-danger" id="descriptionMsg"></span>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="col-form-label">Upload Event</label>
                            <x-fileUpload allowedFiles=true fileType=".doc, .pdf, .docx" fileSize="10 MB">
                                <i class="mdi mdi-file-pdf me-2"></i>
                            </x-fileUpload>
                            <span class="error text-danger fileUrlMsg" id=""></span>
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
