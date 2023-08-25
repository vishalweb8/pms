@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('lead-detail') }}
@endsection
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">View Lead</h4>
            <div class="btn-outer">
                <a class="btn btn-secondary waves-effect waves-light cancel-btn"
                    href="{{ route('lead.list') }}">Back</a>
            </div>
        </div>
    </div>
</div>

<div class="row profile-details">
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body profile-view">
                <div class="leave-emp-info mb-2">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="media-img">
                                <div class="me-3">
                                    @if($leadRepo->user->profile_image &&
                                    file_exists(public_path('storage/upload/user/images/'.$leadRepo->user->profile_image)))
                                    <img alt="" class="avatar-md rounded-circle"
                                        src="{{asset('storage/upload/user/images')}}/{{$leadRepo->user->profile_image}}" style="object-fit: cover;">
                                    @else
                                    <div class="avatar-md">
                                        <span class="avatar-title rounded-circle bg-primary text-white font-size-24">
                                            {{ $leadRepo->user->profile_picture }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                <div class="align-self-center media-body">
                                    <div class="text-muted">
                                        <h5 class="mb-1 font-size-14 mt-3">{{ $leadRepo->user->full_name }}</h5>
                                        <p class="mb-2">
                                            {{ ($leadRepo->user->userRole) ? $leadRepo->user->userRole->name : '' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="align-self-center col-lg-8">
                        </div>

                    </div>
                </div>
                <ul class="personal-info">
                    <li>
                        <div class="title">Lead Title</div>
                        <div class="text">{{ $leadRepo->lead_title }} </div>
                    </li>
                    <li>
                        <div class="title">Company Name</div>
                        <div class="text">{{ $leadRepo->company_name }} </div>
                    </li>
                    <li>
                        <div class="title">First Name</div>
                        <div class="text">{{ Str::ucfirst($leadRepo->first_name) }} </div>
                    </li>
                    <li>
                        <div class="title">Last Name</div>
                        <div class="text">{{ Str::ucfirst($leadRepo->last_name) }} </div>
                    </li>
                    <li>
                        <div class="title">Email</div>
                        <div class="text">{{$leadRepo->email}}</div>
                    </li>
                    <li>
                        <div class="title">Phone</div>
                        <div class="text">{{$leadRepo->phone}}</div>
                    </li>
                    <li>
                        <div class="title">Skype Id</div>
                        <div class="text">{{$leadRepo->skype_id}}</div>
                    </li>
                    <li>
                        <div class="title">Website</div>
                        <div class="text">{{$leadRepo->website}}</div>
                    </li>
                    <li>
                        <div class="title">Industry</div>
                        <div class="text">{{ isset($leadRepo->industry->name) ? $leadRepo->industry->name : '' }}</div>
                    </li>
                    <li>
                        <div class="title">Source</div>
                        <div class="text">{{ isset($leadRepo->leadSource->name) ? $leadRepo->leadSource->name : '' }}</div>
                    </li>
                    <li>
                        <div class="title">Status</div>
                        <div class="text">{{ isset($leadRepo->leadStatus->name) ? $leadRepo->leadStatus->name : '' }}</div>
                    </li>
                    <li>
                        <div class="title">City</div>
                        <div class="text">{{ isset($leadRepo->city->name) ? $leadRepo->city->name : '' }}</div>
                    </li>
                    <li>
                        <div class="title">State</div>
                        <div class="text">{{ isset($leadRepo->state->name) ? $leadRepo->state->name : '' }}</div>
                    </li>
                    <li>
                        <div class="title">Country</div>
                        <div class="text">{{ isset($leadRepo->country->name) ? $leadRepo->country->name : '' }}</div>
                    </li>
                </ul>

            </div>
        </div>
    </div> <!-- end col -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body profile-comment-view" data-simplebar class="sidebar-scroll h-100" id="commentSection">
                @include('leads.partials.comments')
            </div>
        </div>
    </div>
</div> <!-- end row -->

@endsection
