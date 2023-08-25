$(document).ready(function () {

     // get today date
     var dt = new Date();
     // Old :-
     // var month = dt.getMonth() + 1;
     // New Changes : For resolve not display underline badge below dates
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

    $("#saveTimeEntry").on("click", function () {
        $("#pageLoader").show();
        var formData = new FormData($("#importForm")[0]);
        $.ajax({
            url: importURL,
            type: "POST",
            data: formData,
            headers: {
                "X-CSRFToken": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("HERE IN SUCCESS");
                $("#pageLoader").hide();
                window.setTimeout(function(){location.reload()},500)

            },
            error: function (response) {
                toastr.error("Something went wrong !!", "Time Entry's");
            },
        });
    });
});
