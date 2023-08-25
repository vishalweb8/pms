<div class="modal-header">
    <h5 class="modal-title"> {{$todayTitle}} Time Log Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body bg-body py-0">
    <div class="table-responsive dash-modal-table-scrollbar">
        @if(sizeof($timeEntry))
        <table id="indexDataTable"
            class="project-list-table table nowrap table-borderless w-100 align-middle custom-table dataTable no-footer">
            <thead>
                <tr>
                    <th>Log Date</th>
                    <th>Log In Time</th>
                    <th>Log Out Time</th>
                    <th>Duration</th>
                    <th>Total Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timeEntry as $list)
                <tr>
                    <td>{{ Carbon\Carbon::parse($list->log_date)->format('d M Y') }}</td>
                    <td class='verticalTop'>
                        <badge class="badge badge-light-info font-size-14 fw-fw-semibold mb-1">{!! implode('</badge><br/><badge class="badge badge-light-info font-size-14 fw-fw-semibold mb-1">',explode(',',$list->log_in_time)) !!}</badge>
                    </td>
                    <td class='verticalTop'>
                        <badge class="badge badge-light-warning font-size-14 fw-fw-semibold mb-1"> {!! implode('</badge><br/><badge class="badge badge-light-warning font-size-14 fw-fw-semibold mb-1">',explode(',',$list->log_out_time)) !!}</badge>
                    </td>
                    <td class='verticalTop'>
                        <badge class="badge badge-light-default font-size-14 fw-fw-semibold mb-1">{!! implode('</badge><br/><badge class="badge badge-light-default font-size-14 fw-fw-semibold mb-1">',$list->duration) !!}</badge>

                    </td>
                    <td>
                        @if($list->total_duration < '08:20:00')
                            <badge class="badge badge-light-danger font-size-14 fw-fw-semibold">{{ $list->total_duration }}</badge>
                        @else
                            <badge class="badge badge-light-success font-size-14 fw-fw-semibold">{{ $list->total_duration }}</badge>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="comment-not-found d-flex justify-content-center align-items-center h-100 flex-column py-5">
            <img src="/images/no-record-found.svg" class="w-25 d-flex " alt="NotFound" />
            <h6 class="text-muted mt-2">No record available</h6>
        </div>
        @endif
    </div>
</div>
