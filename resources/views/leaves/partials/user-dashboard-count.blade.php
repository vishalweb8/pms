<div class="card leave-dashboard mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="media">
                    <div class="me-3">
                        @if(Auth::user()->profile_image && file_exists(public_path('storage/upload/user/images/'.Auth::user()->profile_image)))
                        <img alt="" class="avatar-md rounded-circle img-thumbnail" src="{{asset('storage/upload/user/images')}}/{{Auth::user()->profile_image}}">
                        @else
                        <div class="avatar-md">
                            <span class="avatar-title rounded-circle bg-primary text-white font-size-24">
                                {{ Auth::user()->profile_picture }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="align-self-center media-body">
                        <div class="text-muted">
                            <h5 class="mb-1">{{ ucwords(Auth::user()->full_name) }}</h5>
                            <p class="mb-2">Welcome To Leave Dashboard</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="align-self-center col-lg-4">
                @if(!isset($teamLeave))
                @include('leaves.common.leave_count_view')
                @endif
            </div>
            <div class="d-flex justify-content-end align-items-center col-lg-4">
                <div class="clearfix mt-4 mt-lg-0">
                    {{-- <a href="{{ !isset($teamLeave) ? route('leave-add-view') : route('leave-add-team')}}" class="btn btn-primary waves-effect waves-light me-2 mb-1" type="button">{{ !isset($teamLeave) ? "Add Leave" : "Add Team Leave" }}</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>