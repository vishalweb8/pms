
<div class="input_comment">
    <form action="{{ route('lead.save.comment') }}" method="post"
        id="superAdminCommentActivity">
        @csrf
        <div class="form-group">
            <input type="hidden" name="lead_id" value="{{ $leadRepo->id }}">
            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
            <textarea name="description" id="description" rows="3"
                class="comment-box p-2 form-control" placeholder="Enter Message..." required></textarea>
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
            @if(isset($leadRepo->comments))
            @foreach($leadRepo->comments as $activity)
            <li class="mb-2">
                <div class="status-update-outer">
                    <div class="date-wrapper">
                        <p> {{ $activity->created_at->format('d-M-y')}}</p>
                        <p> {{ $activity->created_at->format('h:i a')}}</p>
                    </div>
                    <div class="status-detail">
                        <div class="vertical-row pb-0"></div>
                        <div class="user">
                            @if($activity->user->profile_image &&
                            file_exists(public_path('storage/upload/user/images/'.$activity->user->profile_image)))
                            <img class="w-100"
                                src="{{asset('storage/upload/user/images')}}/{{$activity->user->profile_image}}" />
                            @else
                            <div class="avatar-xs">
                                <span
                                    class="avatar-title rounded-circle bg-primary text-white font-size-14">
                                    {{ $activity->user->profile_picture }}
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="user-details w-100">
                            <p>{{ $activity->user->first_name }} {{
                                $activity->user->last_name }}</p>
                            <p class="comment">
                                <?php
                                $comment = nl2br($activity->description);
                                ?>
                                {!! $comment !!}
                            </p>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
            @endif
        </ul>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">

    $("#superAdminCommentActivity").validate({
        // initialize the plugin
        rules: {
            description: {
                required: true,
            }
        }

    });
</script>

@endpush
