$(document).ready(function () {
    setTimeout(function () {
        $("#currency").select2({
            minimumResultsForSearch: 1,
        });
        $("#team_lead_id").select2({
            minimumResultsForSearch: 1,
        });
        $("#reviewer_id").select2({
            minimumResultsForSearch: 1,
        });
        $("#bde_id").select2({
            minimumResultsForSearch: 1,
        });
    }, 15);
    $(".date-picker")
        .datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
        });
    $("#start_date")
        .datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
        })
        .on("changeDate", function (selected) {
            var nextDay = new Date(selected.date);
            nextDay.setDate(nextDay.getDate() + 1);
            $("#end_date").datepicker("setStartDate", nextDay);
        });
    $("#end_date")
        .datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
        })
        .on("changeDate", function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $("#start_date").datepicker("setEndDate", minDate);
        });

    $("#logDate").datepicker({
        endDate: new Date(),
        maxDate: new Date(),
    });

    $("#filesModal").modal({
        backdrop: "static",
        keyboard: false,
    });

    $("#membersModal").modal({
        backdrop: "static",
        keyboard: false,
    });

    $("#clearActivityBox").on("click", function () {
        $("#project_comment").val("");
    });

    $(".clear-filter").on("click", function () {
        $("#start_date,#end_date,#logUser").val("");
        $("#logUser").trigger('change');
    });

    /* this code is used on create/activeboard */
    // $("#superProjectDetailEditForm").validate({
    //     // initialize the plugin
    //     rules: {
    //         name: {
    //             required: true,
    //             // regex: "^[a-z A-Z 0-9_-]+$",
    //         },
    //         project_code: {
    //             required: true,
    //             digits: true,
    //         },
    //         payment_type_id: {
    //             // required: true
    //         },
    //         amount: {
    //             // required: true,
    //             number: true,
    //         },
    //         currency: {
    //             // required: true,
    //         },
    //         allocation_id: {
    //             // required: true,
    //         },
    //         team_lead_id: {
    //             // required: true,
    //         },
    //         reviewer_id: {
    //             // required: true,
    //         },
    //         priority_id: {
    //             // required: true,
    //         },
    //         status_id: {
    //             // required: true,
    //         },
    //         technologies_ids: {
    //             // required: true,
    //         },
    //         client_id: {
    //             // required: true,
    //         },
    //     },
    //     messages: {
    //         name: {
    //             required: "Please enter project name.",
    //             regex: "Name should be in alphabet.",
    //         },
    //         payment_type_id: {
    //             required: "Please select payment type.",
    //         },
    //         amount: {
    //             required: "Please enter amount.",
    //             required: "Amount should be in digits",
    //         },
    //         currency: {
    //             required: "Please select allocation type.",
    //         },
    //         allocation_id: {
    //             required: "Please select allocation type.",
    //         },
    //         team_lead_id: {
    //             required: "Please select team lead.",
    //         },
    //         reviewer_id: {
    //             required: "Please select reviewer.",
    //         },
    //         priority_id: {
    //             required: "Please select priority.",
    //         },
    //         status_id: {
    //             required: "Please select status.",
    //         },
    //         technologies_ids: {
    //             required: "Please select technology.",
    //         },
    //         client_id: {
    //             required: "Please select client.",
    //         },
    //     },
    //     errorPlacement: function (error, element) {
    //         $(element).after("<span></span>");
    //         error.appendTo(element.next("span"));
    //     },
    // });

    $("#superAdminProjectActivity").validate({
        // initialize the plugin
        rules: {
            project_comment: {
                required: true,
            },
        },
        messages: {
            project_comment: {
                required: "Please enter project comments.",
            },
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.next("span"));
        },
    });
});

// This is Ajax call for add new members in project from overview screen
$(document).on("click", ".saveMembers", function () {
    if ($("#members_id").val().length > 0) {
        $.ajax({
            url: saveMembersURL,
            type: "POST",
            data: {
                project_id: $("#project_id").val(),
                members_ids: $("#members_id").val(),
            },
            success: function (response) {
                toastr.success(response.message);
                $("#membersModal").hide();
                setTimeout(function () {
                    location.reload();
                }, 3100);
            },
            error: function (response) {
                toastr.error(response.message);
            },
        });
    } else {
        $("#addMembersErr").html("Please Select Team Members.");
    }
});

// For change project priority from overview page
$(document).on("change", "#changePriority", function () {
    $.ajax({
        url: changePriorityURL,
        type: "POST",
        data: {
            project_id: $(this).attr("project-id"),
            priority_id: $(this).val(),
        },
        success: function (response) {
            toastr.success(response.message);
            setTimeout(function () {
                location.reload();
            }, 3100);
        },
        error: function (response) {
            toastr.error(response.message);
        },
    });
});

// Ajax call for save file links
$(document).on("click", ".saveFilesLink", function () {
    var check = validURL($("#filesLink").val());
    if (check) {
        if ($("#fileName").val().length == 0) {
            $("#fileNameErr").html("Please enter File Name.");
        } else {
            $(".saveFilesLink").prop("disabled", true);
            $.ajax({
                url: saveFileLinksURL,
                type: "POST",
                data: {
                    project_id: $("#project_id").val(),
                    name: $("#fileName").val(),
                    link: $("#filesLink").val(),
                },
                success: function (response) {
                    toastr.success(response.message);
                    $("#filesModal").hide();
                    setTimeout(function () {
                        location.reload();
                    }, 3100);
                },
                error: function (response) {
                    toastr.error(response.message);
                },
            });
        }
    } else {
        if ($("#fileName").val().length == 0) {
            $("#fileNameErr").html("Please enter File Name.");
        } else {
            $("#fileNameErr").html("");
        }
        $("#filesLinkErr").html("Please enter valid URL.");
    }
});

$("#filesModal").on("hidden.bs.modal", function () {
    $("#fileName").val("");
    $("#filesLink").val("");
    $(".error").html("");
});

$("#membersModal").on("hidden.bs.modal", function () {
    document.location.reload();
});

// For delete uploaded file links
$(document).on("click", ".deleteFileLink", function () {
    var linkId = $(this).attr("data-file-link-id");
    var projectId = $(this).attr("data-project-id");
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
            console.log(linkId);
            if (e.value === true) {
                $.ajax({
                    url: deleteFileLinksURL,
                    type: "POST",
                    data: {
                        id: linkId,
                        project_id: projectId,
                    },
                    success: function (response) {
                        toastr.success(response.message);
                        setTimeout(function () {
                            location.reload();
                        }, 3100);
                    },
                    error: function (response) {
                        toastr.error(response.message);
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
});

function validURL(str) {
    // Old Method (Commented because of backup):
    // var res = str.match(
    //     /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g
    // );
    // return res !== null;

    // New Method :
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(
        str
    );
}

$(document).on("change", "#all_members", function () {
    if ($(this).val() != "1") {
        $(this).attr("value", "1");
        $("#members_id").val("").trigger("change");
        $("#members_id").prop("disabled", "disabled");

        $("#team_id").val("").trigger("change");
        $("#team_id").prop("disabled", "disabled");
    } else {
        $(this).attr("value", "0");
        $("#members_id").prop("disabled", false);
        $("#team_id").prop("disabled", false);
    }
});

$(document).on("change", "#team_id", function () {
    if ($(this).val()) {
        $("#members_id").val("").trigger("change");
        $("#members_id").prop("disabled", "disabled");

        $("#all_members").attr("value", "0");
    }
});

// For edit work log of task (Comments)
$(document).on("click", "#editTaskWorkLog", function () {
    $.ajax({
        url: editTaskWorkLogURL,
        type: "GET",
        data: {
            id: $(this).attr("data-task-log-id"),
        },
        success: function (response) {
            $("#logId").val(response.id);
            $("#taskId").val(response.task_id);
            $("#taskUserId").val(response.user_id);

            $("#logDate").datepicker({ dateFormat: "dd-mm-yy" });
            $("#logDate").datepicker("setDate", response.modified_log_date);

            // $("#logTime").val(response.log_time);
            var logTime = response.log_time;
            let hour = parseInt(logTime);
            let minutes = (logTime - hour).toFixed(2);
            $("#logHours").val(hour).trigger("change");
            $("#logMinutes").val(`${minutes}`).trigger("change");
            // $("#workDescription").html(response.description);
            CKEDITOR.instances.workDescription.setData(response.description);
        },
        error: function (response) {
            toastr.error(response.message);
        },
    });
});

// Delete work log task
$(document).on("click", "#deleteTaskWorkLog", function () {
    var logId = $(this).attr("data-task-log-id");
    deleteConfirmBox(this, logId);
});

function deleteConfirmBox(e, logId) {
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
                // var logId = $("#deleteTaskWorkLog").attr("data-task-log-id");
                // alert($(e).attr("data-task-log-id"));
                $.ajax({
                    url: deleteTaskWorkLogURL,
                    type: "POST",
                    data: {
                        id: logId,
                    },
                    success: function (response) {
                        toastr.success(response.message);
                        setTimeout(function () {
                            location.reload();
                        }, 3100);
                    },
                    error: function (response) {
                        toastr.error(response.message);
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

// For clear value of form of Work Log
$(document).on("click", "#clearWorkLogDetails", function () {
    $("#logId").val("");
    $("#logDate").val("");
    $("#logHours").val("0").trigger("change");
    $("#logMinutes").val("00").trigger("change");
    CKEDITOR.instances.workDescription.setData("");
});

$(document).on("click", ".tab-number", function () {
    $.ajax({
        url: changeSelectedTabURL,
        type: "POST",
        data: {
            tab_no: $(this).attr("data-tab-number"),
            tab_name: $(this).attr("data-tab-name"),
        },
        success: function (response) {
            // toastr.success(response.message);
        },
        error: function (response) {
            toastr.error(response.message);
        },
    });
});

$(document).on("change", "#logUser, .start-date,.end-date", function () {
    $.ajax({
        url: getLogOfUserURL,
        type: "POST",
        data: {
            user_id: $("#logUser").val(),
            start_date: $(".start-date").val(),
            end_date: $(".end-date").val(),
        },
        success: function (response) {
            // alert("here");
            $("#totalHours").val(response.data.totalHours);

            $("#workLogUI").html(response.data.html);
        },
        error: function (response) {
            toastr.error(response.message);
        },
    });
});

// var len = 4; // for testing purpose I am using 4 you can change it to 18
// $('.sign-bttn').on('click', function() {
//     if($('#name-container-list .name-list').length==len){
//         alert("You can't add more");
//         return false;
//     }
//     $clone = $('#name-container-list .name-list:first').clone();
//     $clone.find('input').val(''); // empty the new clone field
//     $(this).parent('div').before($clone);
// });
