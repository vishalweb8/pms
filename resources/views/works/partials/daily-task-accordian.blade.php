@foreach($dailyTask as $k => $v)

<div class="accordion-item">
    <div class="dropdown edit-dropdown">
        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
            @if(Auth::user()->userRole->code == "ADMIN")
            <i class="mdi mdi-dots-vertical font-size-18"></i>
            @endif
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a href="{{route('edit-daily-task',$v->id)}}" class="btn dropdown-item edit edit-record">
                    <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                    Edit</a>
            </li>
        </ul>
    </div>
    <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapse{{$v->id}}" aria-expanded="false" aria-controls="collapse">
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td class="d-flex justify-content-start align-items-center">
                                <div class="avatar-group-item">
                                    <a href="javascript: void(0);" class="d-inline-block">
                                        <div class="avatar-xs">
                                            <span
                                                class="avatar-title rounded-circle bg-primary text-white font-size-14">
                                                @if (!empty($v->userTask))
                                                    @if (!empty($v->userTask) && !empty($v->userTask->profile_image))
                                                    <img class="rounded-circle header-profile-user"
                                                        src="{{asset('storage/upload/user/images')}}/{{$v->userTask->profile_image}}" />
                                                    @else
                                                    {{substr($v->userTask->first_name,0,1)}}{{substr($v->userTask->last_name,0,1)}}
                                                    @endif
                                                @endif
                                            </span>

                                        </div>
                                    </a>
                                </div>
                                <div class="ms-3">
                                    <h5 class="text-truncate font-size-14"><a href="javascript: void(0);"
                                            class="text-dark">{{$v->userTask->full_name ?? ''}}</a></h5>
                                    <p class="text-muted mb-0">
                                        {{isset($v->userTask->officialUser) ?
                                        (isset($v->userTask->officialUser->userTeam) ?
                                        $v->userTask->officialUser->userTeam->name : '') :''}}
                                        @if (!empty($v->userTask->officialUser) && !empty($v->userTask->officialUser->userDesignation) && $v->userTask->officialUser->userDesignation->designation_code == "TL")
                                           - {{ $v->userTask->officialUser->userDesignation->designation_code }}
                                        @endif

                                    </p>
                                </div>
                            </td>
                            <td>{{$v->current_date}}</td>
                            <td id="empStatus-{{ $v->id }}" class="empStatus">
                                @switch($v->emp_status)
                                @case('occupied')
                                <badge class="badge badge-light-success">
                                    {{config('constant.emp_status.'.$v->emp_status)}}
                                </badge>
                                @break
                                @case('partially-occupied')
                                <badge class="badge badge-light-default">
                                    {{config('constant.emp_status.'.$v->emp_status)}}
                                </badge>
                                @break
                                @case('free')
                                <badge class="badge badge-light-danger">
                                    {{config('constant.emp_status.'.$v->emp_status)}}
                                </badge>
                                @break
                                @case('on-leave')
                                <badge class="badge badge-light-info">{{config('constant.emp_status.'.$v->emp_status)}}
                                </badge>
                                @break
                                @default
                                {{ "-" }}
                                @endswitch
                            </td>
                            <td id="projectStatus-{{ $v->id }}">
                                @switch($v->project_status)
                                @case('billable')
                                <badge class="badge badge-light-success">
                                    {{config('constant.project_type.'.$v->project_status)}}
                                </badge>
                                @break
                                @case('non-billable')
                                <badge class="badge badge-light-danger">
                                    {{config('constant.project_type.'.$v->project_status)}}
                                </badge>
                                @break
                                @case('partially-billable')
                                <badge class="badge badge-light-default">
                                    {{config('constant.project_type.'.$v->project_status)}}
                                </badge>
                                @break
                                @case('free')
                                <badge class="badge badge-light-danger">
                                    {{config('constant.project_type.'.$v->project_status)}}
                                </badge>
                                @break
                                @default
                                {{ "-" }}
                                @endswitch
                            </td>
                            <td id="verifyTL-{{ $v->id }}">
                                {!! ($v->verified_by_TL == 1) ? '<badge class="badge badge-light-success">Yes</badge>'
                                :'<badge class="badge badge-light-danger">No</badge>' !!}
                            </td>
                            <td id="verifyAdmin-{{ $v->id }}">
                                {!! ($v->verified_by_Admin == 1) ? '<badge class="badge badge-light-success">Yes</badge>
                                ' :'<badge class="badge badge-light-danger">No</badge>' !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </button>
    </h2>
    <div id="collapse{{$v->id}}" class="accordion-collapse collapse" aria-labelledby="headingOne"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <div class="text-muted">
                <div class="row">
                    <div class="mb-2 col-lg-6 col-xs-12 px-3 only-view-text b-right-dotted">
                        <strong class="text-primary">SOD Detail</strong>
                        <p>
                            @php echo mb_convert_encoding($v->sod_description,'UTF-8', 'UTF-8'); @endphp
                        </p>
                    </div>
                    <div class="mb-2 col-lg-6 col-xs-12 only-view-text">
                        <div>
                            <strong class="text-primary">EOD Detail</strong>
                            <p>
                                @php echo mb_convert_encoding($v->eod_description,'UTF-8', 'UTF-8'); @endphp
                            </p>
                        </div>
                    </div>
                    <?php
                    $role = \App\Models\User::where('id', '=', \Auth()->user()->id)->first();
                    ?>
                    @if(!empty($role) && $role->role_id == 4 || $role->role_id == 1)
                    <div class="p-md-3 bt-accordian-filter">
                        <div class="card-body p-0">
                            <div class="d-md-flex justify-content-end align-items-center filter-with-search">
                                <div class="form-group me-3 mb-md-0">
                                    <input type="hidden" name="task_id" id="accordionTaskId" value="{{ $v->id }}">
                                    <input type="hidden" name="current_date" class="current_date" value="{{ $v->current_date }}">
                                    <input type="hidden" name="user_id" class="user_id" value="{{ $v->user_id }}">

                                    <select class="form-control select2 resourceStatus no-search" name="emp_status"
                                        id="resourceStatus-{{ $v->id }}">
                                        <option>Select Resource Status</option>
                                        @foreach (config('constant.emp_status') as $keys => $emp_status)
                                        <option value="{{ $keys }}" {{ ($v->emp_status == $keys) ? 'selected' : '' }}>
                                            {{ $emp_status }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-md-0">
                                    <select class="form-control select2 projectStatus no-search" name="project_status"
                                        id="projectStatus-{{ $v->id }}">
                                        <option>Select Project Status</option>
                                        @foreach (config('constant.project_type') as $key => $project_type)
                                        <option value="{{ $key }}" {{ ($v->project_status == $key) ? 'selected' : '' }}>
                                            {{ $project_type }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group ms-md-3 mb-md-0">
                                    @if($v->verified_by_TL == 1)
                                    <input type="checkbox" class="form-check-input" name="verified_by_TL"
                                        id="verifyByTL-{{ $v->id }}" value="1" checked>
                                    @else
                                    <input type="checkbox" class="form-check-input" name="verified_by_TL"
                                        id="verifyByTL-{{ $v->id }}" value="0">
                                    @endif
                                    <label class="form-check-label" for="verifyByTL-{{ $v->id }}">Verify By TL</label>
                                </div>
                                @if(!empty($role) && $role->role_id == 1)
                                <div class="form-group ms-md-3 mb-md-0">
                                    @if($v->verified_by_Admin == 1)
                                    <input type="checkbox" class="form-check-input" name="verified_by_Admin"
                                        id="verifyByAdmin-{{ $v->id }}" value="1" checked>
                                    @else
                                    <input type="checkbox" class="form-check-input" name="verified_by_Admin"
                                        id="verifyByAdmin-{{ $v->id }}" value="0">
                                    @endif
                                    <label class="form-check-label" for="verifyByAdmin-{{ $v->id }}">Verify By Admin</label>
                                </div>
                                @endif
                                <div class="form-group ms-md-3 mb-md-0">
                                    <button class="btn btn-primary saveAction">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div>

                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
