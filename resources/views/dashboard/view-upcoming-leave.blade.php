<div class="modal-header">
    <h5 class="modal-title" id="holidayModal">Upcoming Leaves</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body bg-body py-0">
    <div class="table-responsive dash-modal-table-scrollbar">
        @if(sizeof($leaves))
        <table id="indexDataTable"
            class="project-list-table table nowrap table-borderless w-100 align-middle custom-table dataTable no-footer">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Duration</th>
                    <th>Total Leave</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $list)
                <tr>
                    <td class="d-flex justify-content-start align-items-center">
                        <div class="avatar-group-item">
                            <div class="avatar-xs d-inline-block">
                                @if(!empty($list->userLeave['profile_image']))
                                <span class="avatar-title rounded-circle bg-light-primary text-primary font-size-12">
                                    <img class="rounded-circle header-profile-user"
                                        src="{{ asset('storage/upload/user/images/'.$list->userLeave->profile_image) }}" />
                                </span>
                                @else
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary text-white font-size-14">
                                        {{ !empty($list->userLeave->profile_picture) ? $list->userLeave->profile_picture : '' }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="text-truncate font-size-14">{{ !empty($list->userLeave->full_name) ? $list->userLeave->full_name : '' }}</h5>
                        </div>
                    </td>
                    <td> {{ isset( $list->userTeam->teamsLeave) ? $list->userTeam->teamsLeave->name : '-' }}</td>
                    <td>{{ Carbon\Carbon::parse($list->start_date)->format('d M') }} -
                        {{ Carbon\Carbon::parse($list->end_date)->format('d M') }}
                    </td>
                    <td>
                        <badge class="badge bg-light-primary text-primary font-size-14 fw-semibold">
                            {{ $list->duration }}
                        </badge>
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
