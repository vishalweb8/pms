var datatalbe; // declare the variable globally, use this for set the datatable variable
$(document).ready(function () {
    $("input").attr("autocomplete", "off");
    $("textarea").attr("autocomplete", "false;");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});

//delete event
jQuery(document).on('click', 'a[data-method="DELETE"]', function (e) {
    e.preventDefault();
    let element = jQuery(this);
    deleteConfirmation(element);
})

function deleteConfirmation(element) {
    var option = $(element).attr("data-delete_title");
    var full_msg = $(element).attr("data-msg") || '';
    var msg = "";
    if (option) {
        msg = "You want to delete <b>" + option + "</b>!";
    } else {
        msg = "You won't be able to revert this!";
    }

    if (full_msg) {
        msg = full_msg;
    }

    Swal.fire({
        title: "<b>Are you sure?</b>",
        html: msg,
        type: "warning",
        icon: "warning",
        cancelButtonText: "Cancel",
        showCancelButton: true,
        cancelButtonClass: "btn-secondary",
        showConfirmButton: true,
        confirmButtonText: "Yes, delete it!",
        confirmButtonClass: "btn-primary",
    }).then(
        function (e) {
            if (e.value === true) {
                let delete_url = $(element).attr("href");
                $.ajax({
                    data: {
                        _token: tokenelem,
                    },
                    url: delete_url,
                    type: "DELETE",
                    success: function (response) {
                        if(datatalbe) {
                            datatalbe.draw();
                            toastr.success(response.message);
                        }else {
                            window.location.reload();
                        }
                    },
                    error: function (response) {
                        toastr.error("Something went wrong !!");
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

function associate_errors(form, errors) {
    let form_ele = jQuery(document).find(form);
    console.log(form_ele);

    let isRepeater = form_ele.attr('is-repeater');
    if(isRepeater == 'true') {
        associate_errors_for_repeater_form(form, errors);
        return;
    }
    jQuery('span.error').remove();
    // Remove existing error
    jQuery.each(errors, function (index, error) {
        console.log(index, error);
        let ele = form_ele.find(`[name="${index}"]`);
        console.log(ele);
        if (ele.length == 0) {
            ele = form_ele.find(`[name="${index + "[]"}"]`);
        }
        // Add error class to form-control
        ele.addClass("error");
        // Add error span after field
        ele.parents(".form-floating, .form-group,.file-input-error, .mb-3").append(
            `<span class="error"><label class="error" for="designation">${error}</label></span>`
        );
    })
}

function associate_errors_for_repeater_form(form, errors) {
    let form_ele = jQuery(form);
    jQuery('span.error').remove();
    // Remove existing error
    jQuery.each(errors, function (index, error) {

        index = index.replace(".", "[");
        index = index.replace(".", "][");
        index += ']';
        let ele = form_ele.find(`[name="${index}"]`);
        // Add error class to form-control
        ele.addClass("error");
        // Add error span after field
        ele.parents(".form-floating, .file-input-error, .form-group, .form-check, .mb-3").append(
            `<span class="error"><label class="error" for="designation">${error}</label></span>`
        );
    })
}

//--- For Remove select search box
$('.select2').select2({ minimumResultsForSearch: -1 });
$('.no-search').select2('destroy');
setTimeout(function () {
    $('.no-search').select2({
        minimumResultsForSearch: -1
    });
}, 2000)

$("#confirm-password-addon").on("click", function (e) {
    0 < $(this).siblings("input").length &&
        ("password" == $(this).siblings("input").attr("type")
            ? $(this).siblings("input").attr("type", "input")
            : $(this).siblings("input").attr("type", "password"));
});

window.makeStickyColumns = function () {
    let stick_column_width = 0;
    $(document).find('.fixed-header tbody tr:first-child .stick-column').each( function (index) {
        let element = $(this);
        let column_width = element.outerWidth();
        let className = 'col-no-' + (index+1);
        if(element.hasClass(className)) {
            $(`.${className}`).css('left', stick_column_width);
            stick_column_width = stick_column_width + column_width;
        }
    });
}
