
$(document).ready(function () {

      CKEDITOR.config.fillEmptyBlocks = false;
      CKEDITOR.on("dialogDefinition", function (ev) {
          var dialogName = ev.data.name;
          var dialogDefinition = ev.data.definition;
          if (dialogName == "link") {
              var targetTab = dialogDefinition.getContents("target");
              var targetField = targetTab.get("linkTargetType");
              targetField["default"] = "_blank";
          }
      });

    $("#selectedDate").change(function(e) {
        getData();
    });

    $('#team').change(function(){
        getData();
    });

    $('#project-status').change(function(){
        getData();
    });

    $('#resource-status').change(function(){
        getData();
    });

    $("#daily-task-year").change(function(){
        getDailyTask();
    });

    $("#daily-task-month").change(function(){
        getDailyTask();
    });

    // For all key except backspace
    $("#search-employee").keypress(function (e) {
        getData();
    });
    // For only backspace key
    $("#search-employee").keydown(function (e) {
        getData();
    });

    $("#verified-by-tl").change(function (e) {
        getData();
    });

    $("#verified-by-admin").change(function (e) {
        getData();
    });

    // $(document).on('click','.accordion-button',function(){
    //     // console.log($(this).attr("data-target")).modal("show"));
    // });

    infinteDailyTaskLoadMore(1);
    // get today date
    var dt = new Date();
    // Old :-
    // var month = dt.getMonth() + 1;
    // New Changes : For resolve not display underline badge below dates
    var month = ("0" + (dt.getMonth() + 1)).slice(-2);
    var year = dt.getFullYear();
    ajaxCall(year, month);

    let currentMonthYear = $(".fc-toolbar-title").text().split(" ");
    disableFutureDate(currentMonthYear);

    // remove and add option for months in filter
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'Jun', 'July', 'August', 'September', 'October', 'November', 'December'];
     $("#daily-task-month").find("option").remove();

    // var month = (new Date()).getMonth();
    // for (; month < monthNames.length; month++) {
    //     $('#daily-task-month').append('<option>' + monthNames[month] + '</option>');
    // }
    $.each(monthNames, function (index, value) {
        $("#daily-task-month").append(
            $(document.createElement("option")).prop({
                value: index + 1,
                text: value,
            })
        );
    });
    $("#daily-task-month").val(parseInt(month)).select2();

    // add html in sod eod
    $(document).on("change", "#sod_project", function () {
        let sodEditor = CKEDITOR.instances["sodTextArea"];
        let selectedProject = $("#sod_project option:selected").text();
        let projectHTML =
            "<strong project_id = " +
            $(this).val() +
            " id='project_"+$(this).val()+"'>" +
            selectedProject +
            "</strong><ul><li><br></li></ul>";
        let range = sodEditor.createRange();
        if(!sodEditor.document.getById(`project_${$(this).val()}`)) {
            range.moveToPosition(range.root, CKEDITOR.POSITION_BEFORE_END);
            sodEditor.getSelection().selectRanges([range]);
            sodEditor.focus();
            sodEditor.insertHtml(projectHTML);
        }
    });

    $(document).on("change", "#eod_project", function () {
        let eodEditor = CKEDITOR.instances["eodTextArea"];
        let selectedProject = $("#eod_project option:selected").text();
        let projectHTML =
            "<strong project_id = " +
            $(this).val() +
            " id='project_"+$(this).val()+"'>" +
            selectedProject +
            "</strong><ul><li><br></li></ul>";
        let range = eodEditor.createRange();
        if(!eodEditor.document.getById(`project_${$(this).val()}`)) {
            range.moveToPosition(range.root, CKEDITOR.POSITION_BEFORE_END);
            eodEditor.getSelection().selectRanges([range]);
            eodEditor.focus();
            eodEditor.insertHtml(projectHTML);
        }
    });

    // Display task detail on click date
    $(document).on("click", ".fc-day-today .fc-daygrid-day-frame , .fc-day-past .fc-daygrid-day-frame", function () {
        if(jQuery(this).hasClass('fc-day-other')) {
            return;
        }
        $('.fc-daygrid-day').removeClass('active-date');
        $(this).parent().closest("td").addClass('active-date');
        let today = $.datepicker.formatDate("yy-mm-dd", new Date());
        let currentSelectedDate = $(this).parent().closest("td").attr("data-date");
        let isLeave = ($(this).children().hasClass("leaveDate")) ? true : false;
        let isWeekend = ($(this).children().hasClass("weekendDate")) ? true : false;
        if (today == currentSelectedDate) {
            if (addedTodayTask === false) {
                window.location.href = addTaskRoute;
            } else {
                window.location.href = editTaskRoute.replace("id", addedTodayTask);
            }
        } else {
            taskOfDateSelected(currentSelectedDate, isLeave, isWeekend);
        }
    });

    // on click on today button display today task detail
    $(".fc-today-button").click(function () {
        let currentMonthYear = $(".fc-toolbar-title").text().split(" ");
        let fcmonth = disableFutureDate(currentMonthYear);
        ajaxCall(parseInt(currentMonthYear[1]), fcmonth);
        let today = $.datepicker.formatDate("yy-mm-dd", new Date());
        taskOfDateSelected(today);
    });

    // on click on prev and next button call ajax for update calender view
    $(".fc-prev-button, .fc-next-button").click(function () {
        let currentMonthYear = $(".fc-toolbar-title").text().split(" ");
        let fcmonth = disableFutureDate(currentMonthYear);
        ajaxCall(parseInt(currentMonthYear[1]), fcmonth);
    });

    // $(document).on("click", ".daily-task-filter", function () {
    //     var successEntry;
    //     let year = $("#daily-task-year").val();
    //     let month = $("#daily-task-month").val();
    //     let selectedMonth = month.toString().padStart("2", 0);
    //     calendar.gotoDate(year + "-" + selectedMonth + "-01");
    //     let currentMonthYear = $(".fc-toolbar-title").text().split(" ");
    //     let fcmonth = disableFutureDate(currentMonthYear);
    //     ajaxCall(parseInt(currentMonthYear[1]), fcmonth);
    // });

    $(document).on("click", ".saveAction", function () {
        let element = $(this);
        $.ajax({
            url: saveActions,
            type: "POST",
            data: {
                id: $(".accordion-collapse.show #accordionTaskId").val(),
                emp_status: $(".accordion-collapse.show .resourceStatus").val(),
                project_status: $(".accordion-collapse.show .projectStatus").val(),
                verified_by_tl: $('.accordion-collapse.show input[name="verified_by_TL"]:checked').val(),
                verified_by_admin: $('.accordion-collapse.show input[name="verified_by_Admin"]:checked').val(),
                task_entry_date: $('.accordion-collapse.show .current_date').val(),
                user_id: $('.accordion-collapse.show .user_id').val(),
            },
            success: function (response) {
                var empStatus = $(".accordion-collapse.show .resourceStatus").val().replace('-', ' ');
                console.log(empStatus);

                var empId = $(".accordion-collapse.show #accordionTaskId").val();

                if(empStatus == 'occupied'){
                    $(document).find("#empStatus-"+empId).html("<badge class='text-capitalize badge badge-light-success'>"+empStatus+"</badge>");
                }else if(empStatus == 'partially occupied'){
                    $(document).find("#empStatus-"+empId).html("<badge class='text-capitalize badge badge-light-default'>"+empStatus+"</badge>");
                }else if(empStatus == 'free'){
                    $(document).find("#empStatus-"+empId).html("<badge class='text-capitalize badge badge-light-danger'>"+empStatus+"</badge>");
                }else if(empStatus == 'on leave'){
                    $(document).find("#empStatus-"+empId).html("<badge class='text-capitalize badge badge-light-info'>"+empStatus+"</badge>");
                }else {
                    $(document).find("#empStatus-"+empId).html("-");
                }

                var projectStatus = $(".accordion-collapse.show .projectStatus").val().replace('-', ' ');
                if(projectStatus == 'billable'){
                    $(document).find("#projectStatus-"+empId).html("<badge class='text-capitalize badge badge-light-success'>"+projectStatus+"</badge>");
                }else if(projectStatus == 'non billable'){
                    $(document).find("#projectStatus-"+empId).html("<badge class='text-capitalize badge badge-light-danger'>"+projectStatus+"</badge>");
                }else if(projectStatus == 'partially billable'){
                    $(document).find("#projectStatus-"+empId).html("<badge class='text-capitalize badge badge-light-default'>"+projectStatus+"</badge>");
                }else if(projectStatus == 'free'){
                    $(document).find("#projectStatus-"+empId).html("<badge class='text-capitalize badge badge-light-danger'>"+projectStatus+"</badge>");
                }else{
                    $(document).find("#projectStatus-"+empId).html("-");
                }

                var verifyTL = $('.accordion-collapse.show input[name="verified_by_TL"]').prop("checked");

                if(verifyTL == 1){
                    $(document).find("#verifyTL-"+empId).html("<badge class='text-capitalize badge badge-light-success'>Yes</badge>");
                }else{
                    $(document).find("#verifyTL-"+empId).html("<badge class='text-capitalize badge badge-light-danger'>No</badge>");
                }
                if($('.accordion-collapse.show input[name="verified_by_Admin"]').length > 0) {
                    var verifyAdmin = $('.accordion-collapse.show input[name="verified_by_Admin"]:checked').prop("checked");
                    if(verifyAdmin == 1){
                        $(document).find("#verifyAdmin-"+empId).html("<badge class='text-capitalize badge badge-light-success'>Yes</badge>");
                    }else{
                        $(document).find("#verifyAdmin-"+empId).html("<badge class='text-capitalize badge badge-light-danger'>No</badge>");
                    }
                }
                if(response.message) {
                    toastr.success(response.message, response.action_title ||'');
                }
                // window.setTimeout(function () { location.reload() }, 500)
            },
            error: function (response) {
                toastr.error(response.error, response.action_title ||'');
            },
        });
    });

    // validation for add daily task
    if ($("#dailyTaskID").val() == "") {
        $("#dailyTaskForm").validate({
            // initialize the plugin
            rules: {
                sod_project: {
                    required: true,
                },
                sod_description: {
                    required: true,
                },
                emp_status: {
                    required: true,
                }
            },
            messages: {
                sod_project: {
                    required: "Please select project.",
                },
                sod_description: {
                    required: "Please enter your SOD.",
                },
                emp_status: {
                    required: "Please select resource status.",
                },
            },
            errorPlacement: function (error, element) {
                console.log(error);
                error.appendTo(element.next("span"));
            },
        });
    }

    $("#updateDescriptionForm").validate({
        // initialize the plugin
        rules: {
            new_description: {
                required: true,
            },
        },
        messages: {
            new_description: {
                required: "Please enter your description.",
            },
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.next("span"));
        },
    });

    $("#updateFormBtn").on("click", function () {
        if ($("#updateDescriptionForm").valid()) {
            var dailyTaskId = $("#dailyTaskId").val();
            var projectId = $("#projectModalId").val();
            var description = $("#newDescription").val();
            var flag = $(this).attr("data-flag");

            $.ajax({
                url: updateDescription,
                type: "POST",
                data: {
                    _token: tokenelem,
                    id: dailyTaskId,
                    project_id: projectId,
                    description: description,
                    flag: flag,
                },
                success: function (response) {
                    toastr.success(
                        "Your project " +
                        flag.toUpperCase() +
                        " updated successfully.",
                        "Daily Task Management"
                    );
                    // $("#dailyTaskModal").modal("hide");
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                },
                error: function (response) {
                    toastr.error(
                        SometingWentWrong,
                        "Daily Task Management"
                    );
                },
            });
        } else {
            return false;
        }
    });
});
$(window).scroll(function () {
    if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
        page++;
        infinteDailyTaskLoadMore(page);
    }
});

function disableFutureDate(monthYear) {
    const dt = new Date();
    const month = dt.getMonth() + 1;
    const year = dt.getFullYear();
    let selectedMonth = new Date(Date.parse(monthYear[0] + " 1, " + monthYear[1])).getMonth() + 1;
    let fcmonth = selectedMonth.toString().padStart("2", 0);
    if (month == fcmonth && year == parseInt(monthYear[1])) {
        $(".fc-next-button").attr("disabled", "disabled");
    } else {
        $(".fc-next-button").removeAttr("disabled");
    }
    if ($(".fc-daygrid-day").hasClass("fc-day-future")) {
        $(".fc-day-future .fc-daygrid-day-frame").addClass("fc-day-other");
    }

    return fcmonth;
}

function taskOfDateSelected(date, isLeave, isWeekend, joinDate) {
    $.ajax({
        url: indexRoute,
        type: "GET",
        data: {
            date: date,
            isLeave: isLeave,
            isWeekend: isWeekend,
            joinDate: joinDate
        },
        success: function (response) {
            $("#dailyTaskBorad").html("");
            $("#dailyTaskBorad").html(response.data["html"]);
        },
        error: function (response) {
            toastr.error(SometingWentWrong, "Daily Task Management");
        },
    });
}

function taskVerifiedByAdmin(elem) {
    let status = ($(elem).prop('checked') == true) ? 1 : 0;

    $.ajax({
        url: taskVerifiedByAdminURL,
        type: "POST",
        data: {
            _token: tokenelem,
            id: $(elem).attr("data-id"),
            verified_by_Admin: status
        },
        success: function (response) {
            toastr.success(DataVerified, "Verified By Admin");
        },
        error: function (response) {
            toastr.error(SometingWentWrong, "Verified By Admin");
        },
    });
}

function taskVerifiedByTL(elem) {
    let status = ($(elem).prop('checked') == true) ? 1 : 0;

    $.ajax({
        url: taskVerifiedByAdminURL,
        type: "POST",
        data: {
            _token: tokenelem,
            id: $(elem).attr("data-id"),
            verified_by_TL: status
        },
        success: function (response) {
            toastr.success(DataVerified, "Verified By TL");
        },
        error: function (response) {
            toastr.error(SometingWentWrong, "Verified By TL");
        },
    });
}

function submitDailyTask() {
    $(this).prop("disabled", true);
    let content = CKEDITOR.instances["sodTextArea"].getData();
    var parser = new DOMParser();
    var doc = parser.parseFromString(content, "text/html");
    const htmlContent = doc.body;
    var projectIdArr = [];
    $($(htmlContent).find("strong")).each(function (e, v) {
        let projectIds = $(v).attr('project_id');
        projectIdArr.push(projectIds);
    });

    $("#project_id").val(projectIdArr);
    $("#dailyTaskForm").trigger("submit");
}

function ajaxCall(year, month) {
    $.ajax({
        url: fetchRecords,
        type: "POST",
        data: {
            _token: tokenelem,
            year: year,
            month: month
        },
        success: function (response) {
            successEntry = response.dailyTaskData;
            let noOfDay = new Date(year, month, 0).getDate();
            addDaysList(noOfDay, month, year, successEntry);
        },
        error: function (response) {
            toastr.error(
                SometingWentWrong,
                "Daily Task Management"
            );
        },
    });
}

// Add days listing in current month
function addDaysList(daysInMonth, currMonth, currYear, successEntry) {
    var allHolidayDates;
    var allLeaves;
    var joiningDate = $("#calendar").attr("joinDate");
    $.ajax({
        async: false,
        data: {
            _token: tokenelem,
        },
        url: getLeaves,
        type: "POST",
        success: function (response) {
            // console.log(response.leaves[0].halfday_status);
            window.addedTodayTask = response.dailyTaskAdded;
            allLeaves = Object.keys(response.allLeaves).map(function (key) {
                return response.allLeaves[key];
            });
        },
        error: function (response) { },
    });

    var holidaySplit = [];
    $.ajax({
        async: false,
        data: {
            _token: tokenelem,
        },
        url: getAllHolidayDate,
        type: "POST",
        success: function (response) {
            allHolidayDates = response.allHoiliday;
        },
        error: function (response) { },
    });

    let today = new Date().getDate();
    let spanClass = '';
    for (let i = 1; i <= daysInMonth; i++) {
        let holidayFlag = false;
        let leaveFlag = false;
        let successDataFlag = false;
        // let date = currMonth + ' ' + i + ',' + currYear;
        let date = `${currYear}-${currMonth}-${((i < 10)? '0'+i : i)}`;
        let selectedDay = new Date(date).getDay();
        let todayMonth = new Date().getMonth() + 1;
        let todayyear = new Date().getFullYear();
        let temp = moment(date);
        let current_formatted_date = temp.format('DD-MM-YYYY');

        // check current date is in holiday date or not
        // $.each(allHolidayDates, function (k, v) {
        //     holidaySplit = v.split("-");
        //     let newHoliday = new Date(+holidaySplit[2], holidaySplit[1] - 1, +holidaySplit[0]);
        //     let newDateObj = new Date(date);
        //     if (jQuery.datepicker.formatDate("dd-mm-yy", newHoliday) == jQuery.datepicker.formatDate("dd-mm-yy", newDateObj)) {
        //         holidayFlag = true;
        //         return false;
        //     }
        // });
        if(jQuery.inArray(current_formatted_date, allHolidayDates) > -1) {
            holidayFlag = true;
        }

        if(jQuery.inArray(current_formatted_date, allLeaves) > -1) {
            leaveFlag = true;
            holidayFlag = false;
        }
        // $.each(allLeaves, function (k, v) {
        //     leaveSplit = v.split("-");
        //     let newleave = new Date(+leaveSplit[2], leaveSplit[1] - 1, +leaveSplit[0]);
        //     let newDateObj = new Date(date);
        //     if (jQuery.datepicker.formatDate("dd-mm-yy", newleave) == jQuery.datepicker.formatDate("dd-mm-yy", newDateObj)) {
        //         leaveFlag = true;
        //         holidayFlag = false;
        //         return false;
        //     }
        // });

        if(jQuery.inArray(current_formatted_date, successEntry) > -1) {
            successDataFlag = true;
        }
        // $.each(successEntry, function (k, v) {
        //     successDataEntrySplit = v.split("-");
        //     let newDate = new Date(+successDataEntrySplit[2], successDataEntrySplit[1] - 1, +successDataEntrySplit[0]);
        //     let newDateObj = new Date(date);
        //     if (jQuery.datepicker.formatDate("dd-mm-yy", newDate) == jQuery.datepicker.formatDate("dd-mm-yy", newDateObj)) {
        //         successDataFlag = true;
        //         return false;
        //     }
        // });

        // console.log(i, selectedDay, todayMonth, currMonth, currYear, successEntry);

        let dayCount = i.toString().padStart("2", 0);
        let activeToday = (i == today && todayMonth == currMonth && todayyear == currYear) ? 'active' : '';
        if (i > today && todayMonth == currMonth && todayyear == currYear) {
            spanClass = '';
        } else if (currMonth > todayMonth && todayyear == currYear) {
            spanClass = '';
        } else if ((selectedDay == 6 || selectedDay == 0 || holidayFlag == true) && successDataFlag == false) {
            spanClass = "bg-warning text-black weekendDate";
        } else if (leaveFlag == true) {
            spanClass = "bg-info text-black leaveDate";
        } else if ((i <= today || todayMonth <= currMonth || todayyear <= currYear) && successDataFlag == true) {
            spanClass = "bg-success text-white";
        } else if ((todayMonth > currMonth || todayyear > currYear) && successDataFlag == true) {
            spanClass = 'bg-success text-white';
        } else {
            spanClass = 'bg-danger text-white';
        }

        if (activeToday == "active") {
            spanClass = "";
        }
        // var formattedToday = $.datepicker.formatDate('d-m-yy', new Date());

        //new joining date code
        // if(formattedToday > joiningDate){
        //     spanClass = "day-disable";
        //     $(".fc-daygrid-day").addClass(spanClass);
        // }

        let currentDay = $.datepicker.formatDate("yy-mm-dd", new Date());
        if (i == today) {
            taskOfDateSelected(currentDay, leaveFlag);
        }
        let dates = currYear + "-" + currMonth + "-" + dayCount;
        let current_date = moment(dates);
        if(current_date.isBefore(TaskEntryDate)) {
            spanClass = "";
            $(".fc-daygrid-day[data-date = " + dates + "] .fc-daygrid-day-frame").addClass("fc-day-other");
        }

        $(".fc-daygrid-day[data-date = " + dates + "] .fc-daygrid-day-events").addClass(spanClass);
    }
}

//--- For calendar
var calendarEl = document.getElementById('calendar');
var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    firstDay: "1",
    headerToolbar: {
        start: 'today',
        center: 'title',
        end: 'prev,next'
    },
});

function infinteDailyTaskLoadMore(page) {
    let siteUrl = ENDPOINT1 + "/works/filter-daily-task?page=" + page;

    // if(filter != null){
    //     siteUrl = ENDPOINT + "/daily-task/more?page=" + page + "&team_id=" +filter;
    // }
    listAjaxCall1(siteUrl);
}

function listAjaxCall1(url) {
    $.ajax({
        url: url,
        datatype: "html",
        type: "GEt",
        data: {
            date: $('#selectedDate').val(),
            team: $('#team').val(),
            resourceStatus: $('#resource-status').val(),
            projectStatus: $('#project-status').val(),
            searchEmployee: $("#search-employee").val(),
            verifiedByTL: $("#verified-by-tl").val(),
            verifiedByAdmin: $("#verified-by-admin").val(),
        },
        beforeSend: function () {
            $(".auto-load").show();
        },
    })
        .done(function (response) {
            if (response.length == 0) {
                $(".auto-load").html(`<div class="bg-light-primary p-2 text-primary"><i class="bx bx-confused font-size-22"></i><h6>We don't have more data to display</h6></div>`);
                return;
            }
            $(".auto-load").hide();
            $('#accordionExample').append(response);
            $(".select2").select2();
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log("Server error occured");
        });
}

calendar.render();
$(".fc-other-month .fc-day-number").hide();

function getData() {

    window.page = 1;
    var data = {};

    data['date'] = $("#selectedDate").val();
    data['team'] = $("#team").val();
    data['projectStatus'] = $("#project-status").val();
    data['resourceStatus'] = $("#resource-status").val();
    data["searchEmployee"] = $("#search-employee").val();
    data["verifiedByTL"] = $("#verified-by-tl").val();
    data["verifiedByAdmin"] = $("#verified-by-admin").val();

    $.ajax({
        url: "/works/filter-daily-task?page=" + page,
        type: 'GET',
        data: data,
        success: function(response) {
            if (response.length == 0) {
                $('#accordionExample').empty();
                $(".auto-load").show();
                $(".auto-load").html(
                    `<div class="bg-light-primary p-2 text-primary"><i class="bx bx-confused font-size-22"></i><h6>We don't have more data to display</h6></div>`
                    );
                return;
            }
            $(".auto-load").hide();
            $('#accordionExample').empty();
            $('#accordionExample').append(response);
            $('.select2').select2();
        },
        error: function(response) {},
    });
}

function getDailyTask()
{
    var successEntry;
    let year = $("#daily-task-year").val();
    let month = $("#daily-task-month").val();
    let selectedMonth = month.toString().padStart("2", 0);
    calendar.gotoDate(year + "-" + selectedMonth + "-01");
    let currentMonthYear = $(".fc-toolbar-title").text().split(" ");
    let fcmonth = disableFutureDate(currentMonthYear);
    ajaxCall(parseInt(currentMonthYear[1]), fcmonth);
}

/* Add common function for add key/value into URL */
function setUrlParameters(key, value) {
    const url = location.protocol + '//' + location.host + location.pathname;
    const urlParams = new URLSearchParams(window.location.search);

    // check the queryString have the key exist
    if(urlParams.has(key)) {
        urlParams.delete(key); // remove the exist key
    }
    urlParams.set(key, value); // set the new value for the given key
    window.history.replaceState('', '', url + '?' + urlParams.toString());
}
