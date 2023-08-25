@foreach ($workLogs as $workLog)
<li class="mt-3">
    <div class="files-cont">
        <div class="file-type me-3">
            @if($workLog->logUser->profile_image)
            {{-- <img alt="" src="{{asset('storage/upload/user/images')}}/{{$workLog->logUser->profile_image}}"> --}}
            <img class="preview-img rounded-circle header-profile-user"
                src="{{asset('storage/upload/user/images')}}/{{$workLog->logUser->profile_image}}" />
            @else
            <div class="avatar-xs">
                <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-14">
                    {{ $workLog->logUser->profile_picture }}
                </span>
            </div>
            @endif
        </div>
        <div class="files-info ms-2 worklog-description">
            <span class="d-block file-author">{{ $workLog->logUser->full_name }}</span>
            <span class="d-block file-date fw-semibold">{{ number_format($workLog->log_time, 2) }} Hours |
                <span class="file-date">{{ $workLog->log_date->format('d-m-Y') }}</span>
            </span>

            <p class="mt-3">
                {!! $workLog->description !!}
            </p>
        </div>
        <ul class="files-action">
            @if ((Auth::user()->id == $workLog->user_id) || Helper::hasAnyPermission(['worklog.edit']))
            <li class="dropdown dropdown-action">
                <a href="" class="dropdown-toggle btn btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i
                        class="mdi mdi-dots-vertical font-size-18"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    @if ((Auth::user()->id == $workLog->user_id) || Helper::hasAnyPermission(['worklog.edit']))
                    <a class="dropdown-item " href="javascript:void(0)" id="editTaskWorkLog"
                        data-task-log-id="{{ $workLog->id }}">
                        <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                        Edit</a>
                    @endif
                    @if ((Auth::user()->id == $workLog->user_id) || Helper::hasAnyPermission(['worklog.destroy']))
                    <a class="dropdown-item" href="javascript:void(0)" id="deleteTaskWorkLog"
                        data-task-log-id="{{ $workLog->id }}">
                        <i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                        Delete</a>
                    @endif
                </div>
            </li>
            @endif
        </ul>
    </div>
</li>
@endforeach
