
var allHolidayDates;
var startDate = new Date();

$(document).ready(function () {


    console.log("ddd");

    setTimeout(function () {
        $("#requestWfhFrom").select2({
            minimumResultsForSearch: 1,
        });
    }, 15);

    $('.select2').select2();


    $("#requestFrom").on("change", function () {
        var requestFrom = this.value;
        fetchrequestFrom(requestFrom);
    });

    $("#requestWfhFrom").on("change", function () {
        var requestFrom = this.value;
        fetchrequestFrom(requestFrom);
    });

    $("#requestFrom").trigger("change");
    $("#requestWfhFrom").trigger("change");
    // WORKING CODE FOR DATE : START :-
    // var datesForDisable = ["21-09-2021", "22-09-2021", "23-09-2021"];
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
        error: function (response) {},
    });

    $(document).on("change", "#leaveType2, #leaveType1", function () {
        setCalculationArgs();
        if ($("#leaveType2").is(":checked")) {
            $(".leave-status").removeClass("d-none");
        } else {
            $(".leave-status").addClass("d-none");
        }
    });

    $(document).on("change", "#firsthalf, #secondhalf", function () {
        setCalculationArgs();
    });

    $(document).on("change", "#adhocLeave,#is_adhoc", function () {
        if ($(this).is(":checked")) {
            $(".adhoc-status").removeClass("d-none");
            startDate = false;
            $("#startDate,#endDate").datepicker("setStartDate", startDate);
        } else {
            $(".adhoc-status").addClass("d-none");
            startDate = new Date();
            $("#startDate,#endDate").datepicker("setStartDate", startDate);
        }
    });

    $("#startDate")
        .datepicker({
            startDate: startDate,
            dateFormat: "dd-mm-yyyy",
            beforeShowDay: disableSpecificDates,
            daysOfWeekDisabled: [0, 6],
            datesDisabled: allHolidayDates,
            autoclose: true,
        })
        .on("changeDate", function (selected) {
            var nextDay = new Date(selected.date);
            nextDay.setDate(nextDay.getDate());
            $("#endDate").datepicker("setStartDate", nextDay);
            var start = selected.date;
            $(this).attr("data-default", start);
            setCalculationArgs();
        });

    $("#endDate")
        .datepicker({
            startDate: startDate,
            dateFormat: "dd-mm-yyyy",
            beforeShowDay: disableSpecificDates,
            daysOfWeekDisabled: [0, 6],
            datesDisabled: allHolidayDates,
            autoclose: true,
        })
        .on("changeDate", function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $("#startDate").datepicker("setEndDate", minDate);
            var start = selected.date;
            $(this).attr("data-default", start);
            setCalculationArgs();
        });
        $.validator.addMethod('dateBefore', function (value, element, params) {
            // if end date is valid, validate it as well
            var end = $(params);
            if (!end.data('validation.running')) {
                $(element).data('validation.running', true);
                setTimeout($.proxy(

                function () {
                    this.element(end);
                }, this), 0);
                // Ensure clearing the 'flag' happens after the validation of 'end' to prevent endless looping
                setTimeout(function () {
                    $(element).data('validation.running', false);
                }, 0);
            }
            let d1 = value.split("-");
            let d2 = end.val().split("-");

            d1 = d1[2].concat(d1[1], d1[0]);
            d2 = d2[2].concat(d2[1], d2[0]);

            // if (parseInt(d1) <= parseInt(d2)) {
            //     alert("valid");
            // }else{
            //     alert('invalid');
            // }
            return this.optional(element) || this.optional(end[0]) || parseInt(d1) <= parseInt(d2);

        }, 'Must be before or equal corresponding end date');

        $.validator.addMethod('dateAfter', function (value, element, params) {
            // if start date is valid, validate it as well
            var start = $(params);
            if (!start.data('validation.running')) {
                $(element).data('validation.running', true);
                setTimeout($.proxy(

                function () {
                    this.element(start);
                }, this), 0);
                setTimeout(function () {
                    $(element).data('validation.running', false);
                }, 0);
            }
            let d11 = value.split("-");
            let d22 = $(params).val().split("-");

            d11 = d11[2].concat(d11[1], d11[0]);
            d22 = d22[2].concat(d22[1], d22[0]);

            return this.optional(element) || this.optional(start[0]) || parseInt(d11) >= parseInt(d22);

        }, 'Must be after or equal corresponding start date');
        var leaveForm = $("#leaveForm");

        leaveForm.validate({
            errorPlacement: function (error, element) {
                let div = element.parent().children().last();
                $(div).after("<span class='error-msg w-100'></span>");
                error.appendTo(div.next("span"));
            },
            rules: {
                reason: {
                    required: true,
                },
                start_date: {
                    dateBefore: '#endDate',
                    required: true,
                },
                end_date: {
                    dateAfter: '#startDate',
                    required: true,
                },
                "request_to[]": {
                    required: true,
                },
                emergency_contact: {
                    digits: true,
                    minlength: 10,
                    maxlength: 10,
                }
            },
        });

        var leaveEditForm = $("#leaveEdit");

        leaveEditForm.validate({
            errorPlacement: function (error, element) {
                let div = element.parent().children().last();
                $(div).after("<span class='error-msg w-100'></span>");
                error.appendTo(div.next("span"));
            },
            rules: {
                reason: {
                    required: true,
                },
                start_date: {
                    dateBefore: '#endDate',
                    required: true,
                },
                end_date: {
                    dateAfter: '#startDate',
                    required: true
                },
                "request_to[]": {
                    required: true,
                },
                emergency_contact: {
                    digits: true,
                    minlength: 10,
                    maxlength: 10,
                },
            },

        });
});

function fetchrequestFrom(requestFrom){
    $("#requestTo").html("");
    if(typeof getRequestFrom !== 'undefined') {
        // return new Promise(function (resolve, reject) {
            $.ajax({
                url: getRequestFrom,
                type: "POST",
                data: {
                    request_from: requestFrom,
                    _token: tokenelem,
                },
                dataType: "json",
                success: function (result) {
                    // $("#reqeustTo").empty();
                    $("#reqeustTo option").removeAttr('selected');
                    $("#reqeustTo").val('');
                    let selected_options = [];
                    $.each(result, function (key, value) {
                        selected_options.push(key);
                    });
                    $("#reqeustTo").val(selected_options);
                    $("#reqeustTo").select2().trigger('change');
                },
                error: function (error) {
                },
            });
        // });
    }
}

function commentPopup(elem) {

    let dataAction = $(elem).data('action');
    let dataId = $(elem).data('id');
    let title = $("#comment_modal .modal-title");
    let action = $("#comment_modal .saveComment");

    $("#commentId").val(dataId);
    $("#comment_modal").modal("show");
    $("#comments").val("");
    $("#commentsErr").text("");
    title.text("");
    action.attr("action", '');

    switch (dataAction) {
        case "approve_leave":
            title.text("Approve Leave");
            action.attr("action", dataAction);
            break;
        case "reject_leave":
            title.text("Reject Leave");
            action.attr("action", dataAction);
            break;
        case "cancel_leave":
            title.text("Cancel Leave");
            action.attr("action", dataAction);
            break;
        case "approve_wfh":
            title.text("Approve Work From Home");
            action.attr("action", dataAction);
            break;
        case "reject_wfh":
            title.text("Reject Work From Home");
            action.attr("action", dataAction);
            break;
        case "cancel_wfh":
            title.text("Cancel Work From Home");
            action.attr("action", dataAction);
            break;
        case "approve_leave_compensation":
            title.text("Approve Leave Compensation");
            action.attr("action", dataAction);
            break;
        case "reject_leave_compensation":
            title.text("Reject Leave Compensation");
            action.attr("action", dataAction);
            break;
        case "cancel_leave_compensation":
            title.text("Cancel Leave Compensation");
            action.attr("action", dataAction);
            break;
        case "add_comment":
            title.text("Add Comment");
            action.attr("action", dataAction);
            break;
        default:

    }
}
// This function is used for Approve Leave
function commentRequests(elem) {
    $(elem).prop("disabled", true);
    let dataAction = $(elem).attr("action");
    var comments = CKEDITOR.instances.comments.getData();
    if (comments.length > 0) {
        $('#pageLoader').show();
        $.ajax({
            url: requestURL,
            type: "POST",
            data: {
                _token: tokenelem,
                commentId: $("#commentId").val(),
                comments: comments,
                dataAction: dataAction,
            },
            success: function (response) {
                $('#pageLoader').hide();
                if(response.data['status'] == 'approved' || response.data['status'] == 'rejected')
                {
                    $('.leave-status-div').hide();
                }else{
                    window.location.reload();
                }
                toastr.success(response.message);
                $("#commentId").val("");
                $("#comment_modal").modal("hide");
                $("#comments").val("");
                $("#commentSection").html("");
                $("#commentSection").html(response.data['html']);
                $(elem).prop("disabled", false);
            },
            error: function (response) {
                $('#pageLoader').hide();
                toastr.error("Something went wrong !!");
            },
        });
    } else {
        $("#commentsErr").text("Please enter your comments.");
        $(elem).prop("disabled", false);
        return false;
    }
}

function disableSpecificDates(date) {
    holidatDates = allHolidayDates;
    var datestring = jQuery.datepicker.formatDate("dd-mm-yy", date);

    for (var i = 0; i < holidatDates.length; i++) {
        if ($.inArray(datestring, holidatDates) != -1) {
            return [false];
        }
    }
    var weekend = $.datepicker.noWeekends(date);
    return weekend;
}

function setCalculationArgs() {

    let start_date = $("#startDate").attr("data-default");
    let end_date = $("#endDate").attr("data-default");
    let leave_type = $("#leaveType2").is(":checked") ? 'half' : 'full';
    if (start_date && end_date) {
        start_date = new Date(start_date);
        end_date = new Date(end_date);
        leaveCalculation(start_date, end_date, allHolidayDates, leave_type);
    }
}

function leaveCalculation(dateStart, dateEnd, allHolidayDates, type) {
    var totalBusinessDays = 0;
    // normalize both start and end to beginning of the day
    dateStart.setHours(0, 0, 0, 0);
    dateEnd.setHours(0, 0, 0, 0);
    var current = new Date(dateStart);
    current.setDate(current.getDate() + 1);
    var day;
    while (current <= dateEnd) {
        day = current.getDay();
        var mystring = jQuery.datepicker.formatDate("dd-mm-yy", current);
        if (day < 1 || day > 5 || $.inArray(mystring, allHolidayDates) != -1) {
            ++totalBusinessDays;
        }
        current.setDate(current.getDate() + 1);
    }
    var days = (dateEnd - dateStart) / 1000 / 60 / 60 / 24 - totalBusinessDays;
    var compensationDays = (dateEnd - dateStart) / 1000 / 60 / 60 / 24;

    if (type == "half") {
        $("#duration").val((days + 1) / 2);
        $("#compDuration").val((compensationDays + 1) / 2);
    } else {
        $("#duration").val(days + 1);
        $("#compDuration").val(compensationDays + 1);
    }

    if ($("#firsthalf").is(":checked") && (!$("#leaveType1").is(":checked"))) {
        $("#returnDate").val(moment(dateEnd).format("DD-MM-YYYY"));
    } else {
        [new Date(dateEnd)].forEach(function (d) {
            let nextDate = getNextBusinessDay(d, allHolidayDates);
            $("#returnDate").val(moment(nextDate).format("DD-MM-YYYY"));
        });
    }
    $("#requestDate").val(moment().format("DD-MM-YYYY"));
}

function getNextBusinessDay(date, allHolidayDates) {
    // Copy date so don't affect original
    date = new Date(+date);
    // Add days until get not Sat or Sun
    do {
        date.setDate(date.getDate() + 1);
    } while (
        !(date.getDay() % 6) ||
        isInArray(allHolidayDates, moment(date).format("DD-MM-YYYY"))
    );
    return date;
}

function isInArray(array, value) {
    if(typeof array !== 'undefined') {
        return (
            (
                array.find((item) => {
                    return item == value;
                }) || []
            ).length > 0
        );
    }
    return false;
}

jQuery(document).on("click", 'a.cancel', function (e) {
    e.preventDefault();

    let element = jQuery(this);
    cancelConfirmation(element);

});

jQuery(document).on("click", 'a.cancel_leave', function (e) {
    e.preventDefault();
    let element = jQuery(this);
    commentPopup(element)
});

jQuery(document).on("click", 'a.cancel_wfh', function (e) {
    e.preventDefault();
    let element = jQuery(this);
    commentPopup(element)
});

jQuery(document).on("click", 'a.cancel_leave_compensation', function (e) {
    e.preventDefault();
    let element = jQuery(this);
    commentPopup(element)
});

function cancelConfirmation(element) {
    let msg = "You wont be able to revert this!";
    Swal.fire({
        title: "Are you sure?",
        text: msg,
        type: "warning",
        icon: "warning",
        cancelButtonText: "Cancel",
        showCancelButton: true,
        cancelButtonClass: "btn-secondary",
        showConfirmButton: true,
        confirmButtonText: "Yes, cancel it!",
        confirmButtonClass: "btn-primary",
    }).then(
        function (e) {
            if (e.value === true) {
                let cancel_url = $(element).attr("href");
                console.log(cancel_url);
                $.ajax({
                    data: {
                        _token: tokenelem,
                    },
                    url: cancel_url,
                    type: "POST",
                    success: function (response) {
                        datatalbe.draw();
                        toastr.success(response.message);
                        location.reload();
                    },
                    error: function (response) {
                        toastr.error(SometingWentWrong);
                    },
                });
            } else {
                e.dismiss;
            }
        },
        function (dismiss) {
            return false;
        }
    );
}

function enableEditSelect() {
    if ($("#leaveEdit").valid()) {
        document.getElementById("reqeustTo").disabled = false;
    }

}
function enableAddSelect() {
    if ($("#leaveForm").valid()) {
        document.getElementById("reqeustTo").disabled = false;
    }
}
