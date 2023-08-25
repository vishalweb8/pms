@extends($theme)
@section('breadcrumbs')
    {{ Breadcrumbs::render('monthly-time-entry') }}
@endsection
@section('after-style')
    <style type="text/css">
        .emp-list-section th.date-cell div {
            display: flex;
            align-items: center;
            flex-direction: column;
        }
        .emp-list-section table tr th {
            border-bottom: none;
            border-left: 1px solid #dcdcdc;
            border-top: 0!important;
            min-width: 64px;

        }
        .emp-list-section th:first-child, .emp-list-section td:first-child {
            position:sticky;
            left:0px;
            background-color:grey;
            border-right: 1px solid #dcdcdc;
        }
        .emp-list-section td:not(:first-child) {
            vertical-align: middle;
            text-align: center;
        }

    </style>
@endsection
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Monthly Time Entry</h4>
            <div class="d-sm-flex  align-items-center filter-with-search">
                <div class="d-sm-flex">
                    <div class="form-group mb-0 me-3">
                        @include('filters.user_type_filter')
                    </div>
                    <div class="form-group mb-0 me-3">
                        @include('filters.month_filter')
                    </div>
                    <div class="form-group mb-0">
                        @include('filters.single_year_filter')
                    </div>
                    <div class="form-group mb-0  ms-sm-3">
                        @if(in_array(\Auth::user()->userRole->code, ['ADMIN', 'PM', "HRA"]))
                            @include('filters.team_filter')
                        @else
                        <select class="form-control select2" name="team" id="team" disabled>
                            <option value="{{ Auth::user()->officialUser->userTeam->id }}" selected>{{
                                Auth::user()->officialUser->userTeam->name }}</option>
                        </select>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card emp-list-section ">
            <div class="card-body">
                <div class="table-responsive fixed-header">
                    <input type="hidden" value="1" id="findDataFlag">
                    <input type="hidden" value="dailyTimeEntry" id="checkPage">
                    <table class="table table-centered table-bordered mb-0 table-hover" id="dailyWorkLogTable">
                        <thead>
                            <tr>
                                <th class="stick-column col-no-1 p-1 bg-white select-project-team">
                                    <input type="text" placeholder="Search Employees..." id="userName" class="form-control border-0" />
                                </th>
                                <th class="stick-column col-no-2">Total</th>
                                <th class="stick-column col-no-3">Avg</th>
                                @foreach($range as $key=> $val)
                                    <th class="date-cell">{{$key}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="allDailyTimeEntry"></tbody>
                    </table>
                </div>
                <!-- Data Loader -->
                <div class="auto-load text-center">
                    <svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
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
@include('common.modal',['modal_size' => 'modal-lg'])
@endsection
@push('scripts')
<script src="{{ asset('/js/modules/dashboard.js') }}"></script>
<script type="text/javascript">
    var dt = new Date();
    var month = ("0" + (dt.getMonth() + 1)).slice(-2);

    // remove and add option for months in filter
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'Jun', 'July', 'August', 'September', 'October', 'November', 'December'];
    $("#daily-task-month").find("option").remove();

    $.each(monthNames, function (index, value) {
        $("#daily-task-month").append(
            $(document.createElement("option")).prop({
                value: index + 1,
                text: value,
            })
        );
    });
    $("#daily-task-month").val(parseInt(month)).select2();

    var ENDPOINT = "{{ url('/') }}";
    var page = 1;
    infinteTimeEntry(page);

    let fixedDivStartPosition = $('.emp-list-section').position();
    let footerDivHeight = $('.footer').outerHeight();
    let screenHeight = $(window).height();
    $('.fixed-header').height(screenHeight - fixedDivStartPosition['top'] - footerDivHeight);


</script>
@endpush
