@extends($theme)
@section('breadcrumbs')
    {{ Breadcrumbs::render('all-employee') }}
@endsection
@section('content')

@include('resource-managment.resource-mgt-count')
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">All Employee</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card emp-list-section ">
            <div class="card-body">
                <div class="table-responsive">
                    <input type="hidden" value="1" id="findDataFlag">
                    <input type="hidden" value="employeePage" id="checkPage">
                    <table class="table table-centered  mb-0" id="teamFilterDiv">
                        <thead>
                            <tr>
                                <th class="p-1 bg-white select-project-team">
                                    @if(in_array(\Auth::user()->userRole->code, ['ADMIN', 'PM']))
                                        {!! Form::select('team_id', $allEmployeeData['teams'], null, ['class' => 'form-control form-select2 select2', 'id'=> "team_id"]) !!}
                                    @else
                                        <select class="form-control select2" name="team_id" id="team_id" disabled>
                                            <option value="{{ Auth::user()->officialUser->userTeam->id }}" selected>{{
                                                Auth::user()->officialUser->userTeam->name }}</option>
                                        </select>
                                        {!! Form::hidden('team_id', Auth::user()->officialUser->userTeam->id) !!}
                                    @endif

                                </th>
                                <th>
                                    <div class="row align-items-md-center">
                                    <div class="col-md-3 ">
                                        <span>Projects</span>
                                        {{-- @include('filters.project_type_filter') --}}
                                    </div>
                                    <div class="col-md-9">
                                    @php
                                        $options = [
                                            // ['class' => 'bg-hold', 'title' => "On-Hold Project"],
                                            ['class' => 'bg-internal', 'title' => "Non-Billable Project"],
                                            ['class' => 'bg-external', 'title' => "Billable Project"],
                                            ['class' => 'bg-danger', 'title' => "No Project"],
                                        ];
                                    @endphp
                                    @include('common.color-indication', ['alignment_class' => 'float-end', 'options' => $options])
                                </div>
                                </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="allEmployees"></tbody>
                    </table>
                </div>
                 <!-- Data Loader -->
                <div class="auto-load text-center">
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                        y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#000"
                            d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50"
                                to="360 50 50" repeatCount="indefinite" />
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

@include('common.known-technology-modal')
@endsection
@push('scripts')
<script>
    const REMOVE_PROJECT_URL = "{{route('remove-project-members')}}";
    var fixed_team = {{ in_array(\Auth::user()->userRole->code, ['ADMIN', 'PM']) ? 0 : 1 }};
    if (fixed_team) {
        fixed_team = $(`input[type="hidden"][name='team_id']`).val();
    }
</script>
<script src="{{ asset('/js/modules/dashboard.js') }}"></script>
<script type="text/javascript">
    var ENDPOINT = "{{ url('/') }}";
    var page = 1;
    var siteUrl = ENDPOINT + "/admin-employee?page=" + page;
    if(fixed_team) {
        siteUrl += `&team_id=${fixed_team}`;
    }
    listAjaxCall(siteUrl, "allEmployees");
</script>
@endpush
