<div class="tab-pane {{ ($selectedTab == 1) ? 'active' : '' }}" id="overview" role="tabpanel">
    <div class="row">
        <div class="col-lg-8 col-xl-9">
            @if (!empty($project->description_for_all))
            <div class="card mb-3">
                <div class="card-body">
                    <div class="project-title">
                        <h5 class="card-title">Project Description</h5>
                    </div>
                    <div class="p-detail-disc">
                        <p>{{ $project->description_for_all }}</p>
                    </div>
                </div>
            </div>
            @endif
            <div class="card mb-3 upload-file-list">
                <div class=" card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Document Links</h5>
                        <button type="button" class="upload-file-btn btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#filesModal"><i class="fa fa-plus"></i>
                            Add</button>
                    </div>
                    <ul class="files-list">
                        @if(!empty($project->files))
                        @foreach($project->files as $k=>$v)
                        <li>
                            <div class="files-cont">
                                <div class="file-type">
                                    <span class="files-icon bg-light-primary"><i
                                            class="mdi mdi-file-pdf-box-outline text-primary"></i></span>
                                </div>
                                <div class="files-info">
                                    <p class="m-0">{{ $v->name }}</p>
                                    <span class="file-name text-ellipsis"><a
                                            class="text-primary text-decoration-underline" href={{ $v->link
                                            }} target="_blank">{{
                                            $v->link }}</a></span>
                                    <span class="file-author"><br/><b>By:</b> {{ $v->created_by_name}}</span>
                                    <span class="file-date">&nbsp; &nbsp; &nbsp;{{ \Carbon\Carbon::parse($v->created_at)->format('M
                                        jS \a\t
                                        g:i a') }}</span>

                                </div>
                                <ul class="files-action">
                                    <li class="dropdown dropdown-action">
                                        <a href="" class="dropdown-toggle btn btn-link" data-bs-toggle="dropdown"
                                            aria-expanded="false"><i class="mdi mdi-dots-vertical font-size-18"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item deleteFileLink" href="javascript:void(0)"
                                                data-project-id="{{ $project->id }}" data-file-link-id="{{ $v->id }}"
                                                id="deleteFileLink">
                                                <i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                Delete</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endforeach
                        @else
                        <li class="border-0">
                            No Document Links Available
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xl-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title m-b-15">Projects Info</h6>
                    <table class="table table-striped table-border mb-0">
                        <tbody>
                            <tr>
                                <td>Total Hours:</td>
                                <td class="text-end" id="totalHours">{{ $totalHours ? $totalHours : 0 }}</td>
                            </tr>
                            <tr>
                                <td>Created At:</td>
                                <td class="text-end">
                                    {{ \Carbon\Carbon::parse($project->created_at)->format('d M, Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Priority:</td>
                                <td class="pe-0 status-select-detail">
                                    @php
                                    if (Helper::hasAnyPermission(['project-overview.edit'])) {
                                    $disabled = '';
                                    } else {
                                    $disabled = 'disabled';
                                    }
                                    @endphp
                                    <select class="text-start bg-primary badge select2 no-search" id="changePriority"
                                        project-id="{{ $project->id }}" {{ $disabled }}>
                                        @foreach ($projectPriority as $key => $priority)
                                        <option value="{{ $key }}" {{ $project->priority_id == $key ? 'selected'
                                            : ''
                                            }}>{{ $priority }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Created by:</td>
                                <td class="text-end text-truncate">{{ $project->created_by_name }}</td>
                            </tr>
                            <tr>
                                <td>Status:</td>
                                <td class="text-end">
                                    @switch($project->status)
                                    @case($project->status == 'On Track')
                                    <badge class="badge badge-light-success font-size-14">
                                        {{ $project->status }}
                                    </badge>
                                    @break
                                    @case($project->status == 'Off Track')
                                    <badge class="badge badge-light-default font-size-14">
                                        {{ $project->status }}
                                    </badge>
                                    @break
                                    @case($project->status == 'On Hold')
                                    <badge class="badge badge-light-danger font-size-14">
                                        {{ $project->status }}
                                    </badge>
                                    @break
                                    @case($project->status == 'Done')
                                    <badge class="badge badge-light-success font-size-14">
                                        {{ $project->status }}
                                    </badge>
                                    @break
                                    @case($project->status == 'Ready')
                                    <badge class="badge badge-light-success font-size-14">
                                        {{ $project->status }}
                                    </badge>
                                    @break
                                    @case($project->status == 'Blocked')
                                    <badge class="badge badge-light-danger font-size-14">
                                        {{ $project->status }}
                                    </badge>
                                    @break
                                    @case($project->status == 'Critical')
                                    <badge class="badge badge-light-info font-size-14">
                                        {{ $project->status }}
                                    </badge>
                                    @break
                                    @case($project->status == 'Delayed')
                                    <badge class="badge badge-light-danger font-size-14">
                                        {{ $project->status }}
                                    </badge>
                                    @break
                                    @default
                                    <badge class="badge badge-light-info font-size-14">
                                        {{ $project->status }}
                                    </badge>
                                    @endswitch
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card project-user mb-3">
                <div class="card-body">
                    <h6 class="card-title m-b-20 d-flex">Team Members <button type="button"
                            class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#membersModal"><i
                                class="fa fa-plus"></i> Add</button>
                    </h6>
                    <ul class="list-box">
                        @if (count($members) > 0)
                        <li>
                            <a href="javascript:void(0);">
                                @foreach($members as $member)
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">
                                            @if($member->profile_image)
                                            <img alt=""
                                                src="{{asset('storage/upload/user/images')}}/{{$member->profile_image}}">
                                            @else
                                            <div class="avatar-xs">
                                                <span class="avatar bg-primary">
                                                    {{ $member->profile_picture }}
                                                </span>
                                            </div>
                                            @endif
                                        </span>

                                    </div>
                                    <div class="list-body">
                                        <span class="message-author">{{ $member->first_name}}
                                            {{ $member->last_name }}</span>
                                        <div class="clearfix"></div>
                                        <span class="message-content">{{$member->designation_name}}</span>
                                    </div>
                                </div>
                                @endforeach
                            </a>
                        </li>
                        @else
                        <li>No Members Available</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
