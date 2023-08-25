$(document).ready(function () {

    $("#detailsModal, #worklogModal").modal({
        backdrop: "static",
        keyboard: false,
        focus: false,
    });

    if($("#calendar").length > 0) {

    //--- For calendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        firstDay: "1",
        headerToolbar: {
            start: 'today',
            center: 'title',
            end: 'prev,next'
        }
    });
    calendar.render();

    let currentMonth = $(".fc-toolbar-title").text().split(" ");
    disableFutureDate(currentMonth);
    getMonthlyWorkingHours();

    // on click on prev and next button call ajax for update calender view
    $(".fc-prev-button, .fc-next-button").click(function () {
        let currentMonthYear = $(document).find(".fc-toolbar-title").text().split(" ");
        let fcmonth = disableFutureDate(currentMonthYear);

        getMonthlyWorkingHours(fcmonth);
    });

    $(document).on("click", ".fc-daygrid-day", function () {

        var today = new Date();
        var selectedDate = new Date($(this).attr("data-date"));
        if (selectedDate <= today) {
            // console.log($(this).attr("data-date"));
            var date = $(this).attr("data-date");
            $("#detailsModal").modal("show");
            $.ajax({
                url: getDateWiseTaskDetailsURL,
                type: "GET",
                data: {
                    date: $(this).attr("data-date"),
                },
                success: function (response) {
                    var formatedDate = moment(date).format("DD-MM-YYYY");
                    var title =
                        "Worklog ( " +
                        formatedDate +
                        " | " +
                        response.totalHours +
                        " Hours )";
                    $("#detailsDate").html(title);
                    // $("#totalHours").html(response.totalHours + " Hours");
                    $("#detailsModalBody").html(response.html);
                    $("#detailsModal").modal("show");
                },
                error: function (response) {
                    // toastr.error(response.message);
                    // alert("error");
                },
            });
        }
    });
    // $(".fc-daygrid-day").click(function () {

    // });

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

    function getMonthlyWorkingHours(month = null) {
        $.ajax({
            url: getMonthlyWorkingHoursUrl,
            data: {
                month:month
            },
            success: function (response) {
                // console.log(response);
                $(".badge-light-success").remove();
                $(".badge-light-danger").remove();
                $.each(response, function (index, value) {
                    if (value.total_hours > "8.20") {
                        $(
                            ".fc-daygrid-day[data-date = " +
                                value.date +
                                "] .fc-daygrid-day-top"
                        ).append(
                            "<span class='badge badge-light-success worklog-badge'>" +
                                value.total_hours +
                                "h</span>"
                        );
                    } else {
                        $(
                            ".fc-daygrid-day[data-date = " +
                                value.date +
                                "] .fc-daygrid-day-top"
                        ).append(
                            "<span class='badge badge-light-danger worklog-badge'>" +
                                value.total_hours +
                                "h</span>"
                        );
                    }

                });
            },
            error: function (response) {
                toastr.error(
                    response.message,
                    "Task Dashboard"
                );
            },
        });
    }


    getMonthlyWorkingHours();
}
});

function firstmodal(elem) {
    $(".log_date_picker").datepicker({
        setDate: new Date(),
        endDate: new Date(),
    });
    $(".log_date_picker").datepicker("setDate", new Date());
    $(".log_date_picker").val(moment(new Date()).format("DD-MM-YYYY"));
    $("#worklogModal").modal("show");
    $("#dataUrl").val($(elem).attr("data-url"));
    $("#projectId").val($(elem).attr("data-project-id"));
    $("#taskId").val($(elem).attr("data-task-id"));
    $("#project_task_details").html($(elem).attr("data-project") + ': ' + $(elem).attr("data-task"));
}

$(document).on("hidden.bs.modal", function () {
    $("#logId").val("");
    $("#logDate").val("");
    $("#logHours").val("0").trigger("change");
    $("#logMinutes").val("0.00").trigger("change");
    CKEDITOR.instances.workDescription.setData("");
    $("#logDateErr").html("");
    $("#logTimeErr").html("");
    $("#descriptionErr").html("");
});

$("#logHours").on("change", function () {
    if ($("#logHours").val() > 0) {
        $("#logTimeErr").html("");
    } else {
        if ($("#logMinutes").val() > 0) {
            $("#logTimeErr").html("");
        } else {
           $("#logTimeErr").html("Please Select Time.");
        }
    }
});
$("#logMinutes").on("change", function () {
    if ($("#logMinutes").val() > 0) {
        $("#logTimeErr").html("");
    } else {
        if ($("#logHours").val() > 0) {
            $("#logTimeErr").html("");
        } else {
            $("#logTimeErr").html("Please Select Time.");
        }
    }
});

$(document).on("click", "#saveWorkLog", function () {

    var workDescription = CKEDITOR.instances.workDescription.getData();
    var flag = true;
    if ($("#logDate").val().length == 0) {
        $("#logDateErr").html("Please Select Date.");
        flag = false;
    } else {
        flag = true;
    }
    if (($("#logHours").val().length == 0) || ($("#logMinutes").val() == 0)) {
        if ($("#logHours").val() > 0 || $("#logMinutes").val() > 0) {
            $("#logTimeErr").html("");
            flag = true;
        } else {
            $("#logTimeErr").html("Please Select Time.");
            flag = false;
        }
    } else {
        $("#logTimeErr").html("");
        flag = true;
    }
    if($('#logHours').val() == 0 && ($("#logMinutes").val() == 0)) {
        $("#logTimeErr").html("Please Select Time");
        flag = false;
    } else {
        flag = true;
    }
    if (workDescription.length == 0) {
        $("#descriptionErr").html("Please Enter Work Description.");
        flag = false;
    } else {
        flag = true;
    }
    if (flag == true) {
        $.ajax({
            url: $("#dataUrl").val(),
            type: "POST",
            data: {
                task_id: $("#taskId").val(),
                log_date: $("#logDate").val(),
                log_hours: $("#logHours").val(),
                log_minutes: $("#logMinutes").val(),
                description: workDescription,
            },
            success: function (response) {
                $("#worklogModal").modal("hide");
                toastr.success(response.message);
                setTimeout(function () {
                    location.reload();
                }, 3100);
            },
            error: function (response) {
                console.log(response.errors);
                toastr.error(response.errors);
                // alert("error");
            },
        });
    }
});
