<div class="tab-pane {{ (session('selectedTab') != 2) ? 'active' : '' }}" id="projectDetails" role="tabpanel">
    <div class="row">
        <div class="col-md-6">
            <div class="card project-activity_detail h-100">
                <div class="card-body">
                    {!! Form::model($project,['route' => 'super-admin-edit-project', 'id' => 'superProjectDetailEditForm',
                    'class' => 'add-form']) !!}
                    @csrf
                    <input type="hidden" name="id" id="project_id" value="{{ $project->id }}">
                    @include('super-projects.partials.form-edit')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    
        <div class="col-md-6">
            <div class="card project-activity_card h-100">
                <div class="card-body">
                    <div class="input_comment">
                        <form action="{{ route('super-admin-save-activity') }}" method="post"
                            id="superAdminProjectActivity">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <textarea name="project_comment" id="project_comment" rows="3"
                                    class="comment-box p-2 form-control" placeholder="Enter Message..."></textarea>
                                <div class="d-flex align-items-end justify-content-end mt-2">
                                    <button type="submit" class="btn-rounded chat-send w-md btn btn-primary ms-2">
                                        <span class="d-none d-sm-inline-block me-2">Send</span>
                                        <i class="mdi mdi-send"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between">
                                <!-- <button type="button" class="btn btn-outline-primary w-md" id="clearActivityBox">Clear</button> -->
    
                            </div>
                        </form>
                    </div>
                    <div class="show_comment">
                        <div class="comment_scrollbar">
                            <ul class="status-timeline mt-3">
                                @foreach($activities as $activity)
                                <li class="mb-2">
                                    <div class="status-update-outer">
                                        <div class="date-wrapper">
                                            <p> {{ $activity->created_at->format('d-M-y')}}</p>
                                            <p> {{ $activity->created_at->format('h:i a')}}</p>
                                        </div>
                                        <div class="status-detail">
                                            <div class="vertical-row pb-0"></div>
                                            <div class="user">
                                                @if($activity->activityUser->profile_image &&
                                                file_exists(public_path('storage/upload/user/images/'.$activity->activityUser->profile_image)))
                                                <img class="w-100"
                                                    src="{{asset('storage/upload/user/images')}}/{{$activity->activityUser->profile_image}}" />
                                                @else
                                                <div class="avatar-xs">
                                                    <span
                                                        class="avatar-title rounded-circle bg-primary text-white font-size-14">
                                                        {{ $activity->activityUser->profile_picture }}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="user-details w-100">
                                                <?php
                                                    $options = collect([
                                                        ['btn_file_name' => 'edit', 'permission' => 'super-admin-project.edit', 'link' => route('super-admin-edit-activity', ['project_id' => $project->id, 'id' => $activity->id])],
                                                        ['btn_file_name' => 'delete', 'permission' => 'super-admin-project.edit', 'link' => route('super-admin-delete-activity', ['project_id' => $project->id, 'id' => $activity->id])],
                                                    ]);
                                                    $dropdown_class = "dropdown-action";
                                                ?>
                                                <div class="d-inline-flex align-items-center justify-content-between w-100">
                                                <p>{{ $activity->activityUser->first_name }} {{
                                                    $activity->activityUser->last_name }}
                                                </p>
                                                @if(in_array(\Auth::user()->userRole->code, ['ADMIN', 'PM']))
                                                @include('common.action-dropdown', compact('options', 'dropdown_class'))
                                                @endif
                                                </div>
                                                <p class="comment">
                                                    <?php
                                                    $comment = nl2br($activity->comments);
                                                    ?>
                                                    {!! $comment !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>