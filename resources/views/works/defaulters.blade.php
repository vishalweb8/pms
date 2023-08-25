@extends($theme)
@section('breadcrumbs')
<!-- {{ Breadcrumbs::render('home') }} -->
@endsection
@section('content')
<div class="page-title-box d-xl-flex  align-items-center justify-content-between">
    <h4 class="m-0 page-title">SOD Defaulters</h4>

    <div class="d-sm-flex flex-wrap flex-lg-nowrap mt-3 mt-xl-0  align-items-center filter-with-search all-emp-task-filter">
        <div class="d-sm-flex">
            <div class="form-group ms-sm-3 mb-0">
                {!! Form::select('date_fitler', $date_filters, null, [ 'class' => 'form-control select2 no-search', 'id' => 'date_fitler']) !!}
            </div>
            <div class="form-group ms-sm-3 mb-0">
                @if(in_array(\Auth::user()->userRole->code, ['ADMIN', 'PM']))
                @include('filters.team_filter')
                @else
                <select class="form-control" name="team" id="team" disabled>
                    <option value="{{ $loggedInUser->officialUser->userTeam->id }}" selected>{{
                        $loggedInUser->officialUser->userTeam->name }}</option>
                </select>
                @endif
            </div>
        </div>
        {{-- <a href="javascript:void(0)"
            class="text-truncate ms-3 btn btn-primary waves-effect waves-light dailyTaskFilterBtn"
            type="button">Filter</a> --}}
    </div>
    {{-- @if(Helper::hasAnyPermission(['super-admin-project.create']))
    <a class="btn btn-primary waves-effect waves-light" href="{{route('work-create')}}">Add Task</a>
    @endif --}}
</div>
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card emp-list-section ">
            <div class="card-body">
                <!-- Tab panes -->
                <div class="tab-content py-3 text-muted">
                    <div class="tab-pane active" id="all-employee" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-centered  mb-0" id="teamFilterDiv">
                                <thead>
                                    <tr>
                                        <th class="p-1 bg-white select-project-team">
                                            {{-- {!! Form::select('emp_team_id', $teams, null, ['class' => 'form-control form-select2', 'id'=> "emp_team_id"])
                                            !!} --}}
                                            Date
                                        </th>
                                        <th>Employee
                                            @php
                                                $options = [
                                                    ['class' => 'all-emp-name-list', 'title' => "Normal User"],
                                                    ['class' => 'bg-light-green', 'title' => "Approved Leave"],
                                                    ['class' => 'bg-light-yellow', 'title' => "Pending Leave"],
                                                ];
                                            @endphp
                                            @include('common.color-indication', ['alignment_class' => 'float-end', 'options' => $options])
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="defaulterEntries" class="resourceEntries"></tbody>
                            </table>
                        </div>
                    </div>
                    <!---end--first-tab---->
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
<script type="text/javascript">
    var ENDPOINT = "{{ url('/') }}";
    var page = 1;
</script>
<script src="{{ asset('/js/modules/defaulters.js') }}"></script>
@endpush
