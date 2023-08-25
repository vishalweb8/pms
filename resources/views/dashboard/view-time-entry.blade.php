<div class="modal-header">
    <h5 class="modal-title" id="holidayModal"> My Time Log Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body bg-body py-0">
    <div class="table-responsive dash-modal-table-scrollbar">
        @if(sizeof($timeEntry))
        <table id="indexDataTable"
            class="project-list-table table nowrap table-borderless w-100 align-middle custom-table dataTable no-footer">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Log Date</th>
                    <th>Total Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timeEntry as $list)
                <tr>
                    <td class="d-flex justify-content-start align-items-center">
                        <div class="avatar-group-item">
                            <div class="avatar-xs d-inline-block">
                                @if(auth()->user()->profile_image)
                                <span class="avatar-title rounded-circle bg-light-primary text-primary font-size-12">
                                    <img class="rounded-circle header-profile-user"
                                        src="{{ asset('storage/upload/user/images/'.auth()->user()->profile_image) }}" />
                                </span>
                                @else
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary text-white font-size-14">
                                        {{ auth()->user()->profile_picture }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="text-truncate font-size-14">{{ auth()->user()->full_name }}</h5>
                        </div>
                    </td>
                    <td> {{ $list->EmployeeCode }}</td>
                    <td>{{ Carbon\Carbon::parse($list->log_date)->format('d M Y') }}</td>
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
