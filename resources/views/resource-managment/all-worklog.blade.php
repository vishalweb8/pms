@extends($theme)
@section('breadcrumbs')
    {{ Breadcrumbs::render('monthly-worklog') }}
@endsection
@section('after-style')
    <style type="text/css">
        #dailyWorkLogTable  th.date-cell div {
            display: flex;
            align-items: center;
            flex-direction: column;
        }
        #dailyWorkLogTable .emp-list-section table tr th {
            border-bottom: none;
            border-left: 1px solid #dcdcdc;
            border-top: 0!important;
            min-width: 64px;

        }
        #dailyWorkLogTable th:first-child,#dailyWorkLogTable td:first-child {
            position:sticky;
            left:0px;
            background-color:grey;
            border-right: 1px solid #dcdcdc;
        }
        #dailyWorkLogTable td:not(:first-child) {
            vertical-align: middle;
            text-align: center;
        }

    </style>
@endsection
@section('content')
<div class="project-detail-wrapper">
    @if(Auth::user()->isManagement())
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card mb-1">
                <div class="card-body">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link tab-number {{ ($selectedTab == '1') ? 'active' : '' }}" id="dailyWorkLogTab"
                                data-bs-toggle="tab" href="#dailywork" role="tab" data-tab-number="1" data-tab-name="selectedTabWorklog">
                                <span class="d-sm-block">Daily Worklogs</span>
                            </a>
                        </li>

                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link tab-number {{ ($selectedTab == '2') ? 'active' : '' }}" id="allEmpWorkTab"
                                data-bs-toggle="tab" href="#allempwork" role="tab" data-tab-number="2" data-tab-name="selectedTabWorklog">
                                <span class="d-sm-block">Filtered Worklogs</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="tab-content pt-2">
        @include('resource-managment.monthly-worklog')
        <!--overview-tabs-content-end--->
        @include('resource-managment.all-emp-worklog')
        <!--task-tabs-content-end--->
    </div>
    <!--tabs-content-end--->
</div>
@include('task-dashboard.details-modal')

@include('common.known-technology-modal')

@endsection
@push('scripts')
<script>
    const getDateWiseTaskDetailsURL = "{{ route('get-date-wise-task-details') }}";
    const changeSelectedTabURL = "{{ route('change-selected-tab-value') }}";
 </script>
<script src="{{ asset('/js/modules/dashboard.js') }}"></script>
<script src="{{ asset('js/modules/task_dashboard.js') }}"></script>

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
    let teamID = $("#team").val();
    let taskMonth = $("#daily-task-month").val();
    let taskYear = $("#financialSingleYear").val();
    let userType = $("#userType").val();
    let startDate = $("#start_date").val();
    let endDate = $("#end_date").val();
    let employee = $('#employee').val();

    @if(session('selectedTabWorklog') == 2)
        let addOnFilter = '&teamID=' + teamID+ '&startDate='+startDate + '&endDate='+endDate + '&employee='+employee;
    
        var siteUrl = "{{route('admin-filtered-worklog')}}?page=" + page + addOnFilter;
        listAjaxCall(siteUrl, "filterdWorkLog",null,false);
    @else
        let addOnFilter = '&teamID=' + teamID + '&taskMonth=' +taskMonth + '&taskYear='+taskYear + '&userType='+userType + '&startDate='+startDate + '&endDate='+endDate + '&employee='+employee;

        var siteUrl = ENDPOINT + "/admin-monthly-worklog?page=" + page + addOnFilter;
        listAjaxCall(siteUrl, "allDailyWorkLog",null,false);
    @endif


    let fixedDivStartPosition = $('.emp-list-section').position();
    let footerDivHeight = $('.footer').outerHeight();
    let screenHeight = $(window).height();
    $('.fixed-header').height(screenHeight - fixedDivStartPosition['top'] - footerDivHeight);

    $(document).ready(function(){
        $("#end_date").datepicker("setStartDate", new Date());
        $("#end_date").datepicker("setEndDate", new Date());
        $("#start_date").datepicker("setEndDate", new Date());
        $(document).on("click", ".tab-number", function () {
            $("#userName").val('');
            var ENDPOINT = "{{ url('/') }}";
            var page = 1;
            let taskMonth = $("#daily-task-month").val();
            let taskYear = $("#financialSingleYear").val();
            let userType = $("#userType").val();
            let startDate = $("#start_date").val();
            let endDate = $("#end_date").val();
            let employee = $('#employee').val();
            let projects = $('#projects').val();
            var tabNumber = $(this).attr("data-tab-number");
            if(tabNumber == 2) {
                let teamID = $("#allempwork #team").val();
                let addOnFilter = '&teamID=' + teamID+ '&startDate='+startDate + '&endDate='+endDate + '&employee='+employee+'&projects='+projects;
                
                var siteUrl = "{{route('admin-filtered-worklog')}}?page=" + page + addOnFilter;
                var tableBodyId = "filterdWorkLog";
            } else {
                let teamID = $("#dailywork #team").val();
                let addOnFilter = '&teamID=' + teamID + '&taskMonth=' +taskMonth + '&taskYear='+taskYear + '&userType='+userType + '&employee='+employee;
    
                var siteUrl = ENDPOINT + "/admin-monthly-worklog?page=" + page + addOnFilter;
                var tableBodyId = "allDailyWorkLog";
            }

            $('#pageLoader').show();
            $.ajax({
                url: changeSelectedTabURL,
                type: "POST",
                data: {
                    tab_no: $(this).attr("data-tab-number"),
                    tab_name: $(this).attr("data-tab-name"),
                },
                success: function (response) {
                    $('#pageLoader').hide();
                    // toastr.success(response.message);
                    $('#allDailyWorkLog,#filterdWorkLog').html('');
                    listAjaxCall(siteUrl, tableBodyId,null,false);
                },
                error: function (response) {
                    toastr.error(response.message);
                },
            });
        });

        $(document).on("click", "#dailyWorkLogTable #allDailyWorkLog a.getTimeEntryDetail", function () {
            $('#pageLoader').show();
            var today = new Date();
            var selectedDate = new Date($(this).attr("data-date"));
            if (selectedDate <= today) {
                var date = $(this).attr("data-date");
                $.ajax({
                    url: getDateWiseTaskDetailsURL,
                    type: "GET",
                    data: {
                        date: $(this).attr("data-date"),
                        id: $(this).attr("data-id"),
                    },
                    beforeSend: function() {
                        $("#detailsDate").html('');
                        $("#detailsModalBody").html('');
                    },
                    success: function (response) {
                        $('#pageLoader').hide();
                        $("#detailsModal").modal("show");
                        var formatedDate = moment(date).format("DD-MM-YYYY");
                        var title =
                            "Worklog ( " +
                            formatedDate +
                            " | " +
                            response.totalHours +
                            " Hours )";
                        $("#detailsDate").html(title);
                        $("#detailsModalBody").html(response.html);
                    },
                    error: function (response) {
                        // toastr.error(response.message);
                        // alert("error");
                    },
                });
            }
        })
    })
</script>
@endpush

