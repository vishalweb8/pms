@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('project-superadmin-edit') }}
@endsection
@section('content')

<div class="page-title-box d-flex align-items-center justify-content-between">
    <h4 class="m-0 page-title">{{ $project->project_code ? $project->project_code.' - ' : '' }}{{ $project->name }}</h4>
    <a class="btn btn-secondary waves-effect waves-light" href="{{route('super-admin-project-dashboard')}}">Back</a>
</div>

<div class="project-detail-wrapper">
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card mb-1">
                <div class="card-body">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link tab-number {{ (session('selectedTab') != 2) ? 'active' : '' }}" id="projectDetailsTab"
                                data-bs-toggle="tab" href="#projectDetails" role="tab" data-tab-number="1">
                                <span class="d-sm-block">Project Details</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link tab-number {{ (session('selectedTab') == 2) ? 'active' : '' }}" id="paymentHistoryTab"
                                data-bs-toggle="tab" href="#paymentHistory" role="tab" data-tab-number="2">
                                <span class="d-sm-block">Payment History</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content pt-2">
        @include('super-projects.project-details')
        @include('super-projects.payment-history.index')
    </div>
    <!--tabs-content-end--->
</div>
@include('common.modal')
@endsection
@push('scripts')
<script type="text/javascript">
    var changeSelectedTabURL = "{{ route('change-selected-tab-value') }}";
</script>
<script src="{{ asset('js/modules/projects.js')}}"></script>
@endpush
