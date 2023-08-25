$(document).ready(function() {

    setTimeout(initPersonalForm, 100);
    // $("#members_mentor").attr("disabled",'disabled');


    $(document).on('click','.div-close-btn',function(){
        var id = $(this).siblings('.hid_user_id').attr('value');
        var url = $(this).data('url').replace(':id',id);
        if(id != '')
        {
            $.ajax({
                url: url,
                method: "DELETE",
                headers: {
                    "X-CSRFToken": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                processData: false,
                contentType: false,
                success: function (response) {
                    is_async_step = true;
                    //trigger navigation event
                    $("#user_wizard").steps("next");
                    toastr.success(response.message);
                    return true;
                },
                error: function (response) {
                    console.log(response.responseJSON.errors);
                    associate_errors_for_repeater_form(
                        $("#addFamilyDetail"),
                        response.responseJSON.errors
                    );
                    if (response.status != 422) {
                        toastr.error(SometingWentWrong);
                    }
                    return false; //this will prevent to go to next step
                },
            });
        }


    })

    /* user module steps and validations */
    var is_async_step = false;
    $("#user_wizard").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slide",
        labels: {
            finish: "Submit",
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            if (is_async_step) {
                is_async_step = false;
                //Allow next step
                return true;
            }
            if (newIndex < currentIndex) {
                return true;
            }
            if (currentIndex == 0) {
                personalDetailForm.validate().settings.ignore = ":disabled,:hidden";
                if (personalDetailForm.valid() == true) {
                    // getTeamLeaderMembers();
                    var formData = new FormData($("#addPersonalDetail")[0]);
                    console.log(formData.values());
                    // Display the key/value pairs
                    for(var pair of formData.entries()) {
                        console.log(pair[0]+ ', '+ pair[1]);
                    }
                    $.ajax({
                        url: storePersonalDetail,
                        method: "POST",
                        headers: {
                            "X-CSRFToken": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            console.log("ihihihihihi");
                            is_async_step = true;
                            $("#addPersonalDetail .user_id, #addOfficialDetail .user_id,#addEducationDetail .user_id,#addExperienceDetail .user_id,#addBankDetail .user_id,#addFamilyDetail .user_id").val(response.data.users.id);
                            $('#emp_code_new').val(response.data.empCode);
                            $('#company_email_id').val(response.data.users.email);
                            //trigger navigation event
                            toastr.success(response.message);
                            $("#user_wizard").steps("next");
                            return true;
                        },
                        error: function (response) {
                            associate_errors(
                                personalDetailForm,
                                response.responseJSON.errors
                            );
                            if (response.status != 422) {
                                toastr.error(SometingWentWrong);
                            }
                            return false; //this will prevent to go to next step
                        },
                    });
                    return false;
                } else {
                    return personalDetailForm.valid();
                }
            }

            if (currentIndex == 1) {
                officialDetailForm.validate().settings.ignore =
                    ":disabled,:hidden";
                if (officialDetailForm.valid() == true) {
                    var formData = new FormData($("#addOfficialDetail")[0]);
                    $.ajax({
                        url: storeOfficialDetail,
                        method: "POST",
                        headers: {
                            "X-CSRFToken": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            is_async_step = true;
                            //trigger navigation event
                            $("#user_wizard").steps("next");
                            return true;
                        },
                        error: function (response) {
                            associate_errors(
                                officialDetailForm,
                                response.responseJSON.errors
                            );
                            if (response.status != 422) {
                                toastr.error(SometingWentWrong);
                            }
                            return false; //this will prevent to go to next step
                        },
                    });
                    return false;
                } else {
                    return officialDetailForm.valid();
                }
            }

            if (currentIndex == 2) {
                if(isLoadedRepeaterDiv == false){
                    getRepeaterDiv();
                }
                bankDetailForm.validate().settings.ignore = ":disabled,:hidden";
                var formData = new FormData($("#addBankDetail")[0]);

                // Old Code : Before facing bank details ajax call not working issue (This code is not commented at that time)
                // if (
                //     $(
                //         "#per_bank_name,per_bank_ifsc_code,per_account_number,salary_bank_name,salary_bank_ifsc_code,salary_account_number,pan_number,aadharcard_name,aadharcard_number"
                //     ).val() == ""
                // ) {
                //     return true;
                // }

                if (bankDetailForm.valid() == true) {
                    $.ajax({
                        url: storeBankDetail,
                        method: "POST",
                        headers: {
                            "X-CSRFToken": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            is_async_step = true;
                            //trigger navigation event
                            $("#user_wizard").steps("next");
                            return true;
                        },
                        error: function (response) {
                            associate_errors(
                                bankDetailForm,
                                response.responseJSON.errors
                            );
                            if (response.status != 422) {
                                toastr.error(SometingWentWrong);
                            }
                            return false; //this will prevent to go to next step
                        },
                    });
                } else {
                    return bankDetailForm.valid();
                }
                return false;
            }
            if (currentIndex == 3) {
                var length = $("#education_group_div").children().length;
                $('#edu_child_length').val(length);
                var isEmpty = true;
                for (var i = 0; i < length; i++) {
                    var qualification =
                        document.forms["addEducationDetail"][
                            "education_group[" + i + "][qualification]"
                        ];
                    var universityBoard =
                        document.forms["addEducationDetail"][
                            "education_group[" + i + "][university_board]"
                        ];
                    var grade =
                        document.forms["addEducationDetail"][
                            "education_group[" + i + "][grade]"
                        ];
                    var passingYear =
                        document.forms["addEducationDetail"][
                            "education_group[" + i + "][passing_year]"
                        ];
                    if (
                        qualification.value == "" &&
                        universityBoard.value == "" &&
                        grade.value == "" &&
                        passingYear.value == ""
                    ) {
                        isEmpty = true;
                    } else {
                        isEmpty = false;
                        break;
                    }
                }
                if (isEmpty == true) {
                    return true;
                }
                // educationDetailForm.validate().settings.ignore = ":disabled,:hidden";

                // if (educationDetailForm.valid() == true) {
                var formData = new FormData($("#addEducationDetail")[0]);

                $.ajax({
                    url: storeEduDetail,
                    method: "POST",
                    headers: {
                        "X-CSRFToken": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        is_async_step = true;
                        //trigger navigation event
                        $("#user_wizard").steps("next");
                        return true;
                    },
                    error: function (response) {
                        associate_errors(
                            educationDetailForm,
                            response.responseJSON.errors
                        );
                        if (response.status != 422) {
                            toastr.error(SometingWentWrong);
                        }
                        return false; //this will prevent to go to next step
                    },
                });
                return false;
                // } else {
                //     return educationDetailForm.valid();
                // }
            }
            if (currentIndex == 4) {
                var length = $("#experience_group_div").children().length;
                $('#expr_child_length').val(length);
                var isEmpty = true;
                for (var i = 0; i < length; i++) {
                    var previous_company =
                        document.forms["addExperienceDetail"][
                            "experience_group[" + i + "][previous_company]"
                        ];
                    var joined_date =
                        document.forms["addExperienceDetail"][
                            "experience_group[" + i + "][joined_date]"
                        ];
                    var released_date =
                        document.forms["addExperienceDetail"][
                            "experience_group[" + i + "][released_date]"
                        ];
                    var designation =
                        document.forms["addExperienceDetail"][
                            "experience_group[" + i + "][designation_id]"
                        ];
                    if (
                        previous_company.value == "" &&
                        joined_date.value == "" &&
                        released_date.value == "" &&
                        designation.value == ""
                    ) {
                        isEmpty = true;
                    } else {
                        isEmpty = false;
                        break;
                    }
                }
                if (isEmpty == true) {
                    return true;
                }
                // alert()
                experienceDetailForm.validate().settings.ignore =
                    ":disabled,:hidden";

                if (experienceDetailForm.valid() == true) {
                    var formData = new FormData($("#addExperienceDetail")[0]);

                    $.ajax({
                        url: storeExperienceDetail,
                        method: "POST",
                        headers: {
                            "X-CSRFToken": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            is_async_step = true;
                            //trigger navigation event
                            $("#user_wizard").steps("next");
                            return true;
                        },
                        error: function (response) {
                            associate_errors(
                                experienceDetailForm,
                                response.responseJSON.errors
                            );
                            if (response.status != 422) {
                                toastr.error(SometingWentWrong);
                            }
                            return false; //this will prevent to go to next step
                        },
                    });
                    return false;
                } else {
                    return experienceDetailForm.valid();
                }
            }
            if (currentIndex == 5) {
                var length = $("#family_group_div").children().length;
                $('#family_child_length').val(length);
                var isEmpty = true;
                for (var i = 0; i < length; i++) {
                    var name =
                        document.forms["addFamilyDetail"][
                            "family_group[" + i + "][name]"
                        ];
                    var relation =
                        document.forms["addFamilyDetail"][
                            "family_group[" + i + "][relation]"
                        ];
                    var occupation =
                        document.forms["addFamilyDetail"][
                            "family_group[" + i + "][occupation]"
                        ];
                    var contact_number =
                        document.forms["addFamilyDetail"][
                            "family_group[" + i + "][contact_number]"
                        ];
                    if (
                        name.value == "" &&
                        relation.value == "" &&
                        occupation.value == "" &&
                        contact_number.value == ""
                    ) {
                        isEmpty = true;
                    } else {
                        isEmpty = false;
                        break;
                    }
                }
                if (isEmpty == true) {
                    return true;
                }
                familyDetailForm.validate().settings.ignore =
                    ":disabled,:hidden";

                if (familyDetailForm.valid() == true) {
                    var formData = new FormData($("#addFamilyDetail")[0]);

                    $.ajax({
                        url: storeFamilyDetail,
                        method: "POST",
                        headers: {
                            "X-CSRFToken": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            is_async_step = true;
                            //trigger navigation event
                            $("#user_wizard").steps("next");
                            toastr.success(response.message);
                            return true;
                        },
                        error: function (response) {
                            console.log(response.responseJSON.errors);
                            associate_errors_for_repeater_form(
                                $("#addFamilyDetail"),
                                response.responseJSON.errors
                            );
                            if (response.status != 422) {
                                toastr.error(SometingWentWrong);
                            }
                            return false; //this will prevent to go to next step
                        },
                    });
                    return false;
                } else {
                    return familyDetailForm.valid();
                }
            }
        },
        onFinishing: function (event, currentIndex)
        {
            var length = $("#family_group_div").children().length;
                var isEmpty = true;
                for (var i = 0; i < length; i++) {
                    var name =
                        document.forms["addFamilyDetail"][
                            "family_group[" + i + "][name]"
                        ];
                    var relation =
                        document.forms["addFamilyDetail"][
                            "family_group[" + i + "][relation]"
                        ];
                    var occupation =
                        document.forms["addFamilyDetail"][
                            "family_group[" + i + "][occupation]"
                        ];
                    var contact_number =
                        document.forms["addFamilyDetail"][
                            "family_group[" + i + "][contact_number]"
                        ];
                    if (
                        name.value == "" &&
                        relation.value == "" &&
                        occupation.value == "" &&
                        contact_number.value == ""
                    ) {
                        isEmpty = true;
                    } else {
                        isEmpty = false;
                        break;
                    }
                }
                if (isEmpty == true) {
                    return true;
                }
                familyDetailForm.validate().settings.ignore =
                    ":disabled,:hidden";

                if (familyDetailForm.valid() == true) {
                    var formData = new FormData($("#addFamilyDetail")[0]);

                    $.ajax({
                        url: storeFamilyDetail,
                        method: "POST",
                        headers: {
                            "X-CSRFToken": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            is_async_step = true;
                            //trigger navigation event
                            $("#user_wizard").steps("next");
                            toastr.success(response.message);
                             window.location = userIndex;
                            return true;
                        },
                        error: function (response) {
                            console.log(response.responseJSON.errors);
                            associate_errors_for_repeater_form(
                                $("#addFamilyDetail"),
                                response.responseJSON.errors
                            );
                            if (response.status != 422) {
                                toastr.error(SometingWentWrong);
                            }
                            return false; //this will prevent to go to next step
                        },
                    });
                    return false;
                } else {
                    return familyDetailForm.valid();
                }
        },
        onFinished: function (event, currentIndex)
        {
                toastr.success('Employee Successfully Updated!');
                 window.location = userIndex;

        },
        onInit: function (event, currentIndex) {
            $('.form-select2').select2();
        }

    });

    /* personal detail form validation */
    var personalDetailForm = $("#addPersonalDetail");

    personalDetailForm.validate({
        errorPlacement: function (error, element) {
            let div = element.parent().children().last();
            $(div).after("<span class='error-msg w-100'></span>");
            error.appendTo(div.next("span"));
        },
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 30,
                regex: "^[a-zA-Z ]+$"

            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 30,
                regex: "^[a-zA-Z ]+$"
            },
            user_name: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            // personal_email: {
            //     required: true,
            //     email: true,
            // },
            // gender: {
            //     required: true,
            // },
            password: {
                minlength: 8,
            },
            // birth_date: {
            //     required: true,
            // },
            designation_id: {
                required: true,
            },
            role_id: {
                required: true,
            },
            // blood_group: {
            //     required: true,
            // },
            // marital_status: {
            //     required: true,
            // },
            // phone_number: {
            //     required: true,
            //     digits: true,
            // },
            // emergency_number: {
            //     required: true,
            //     digits: true,
            // },
            // temp_address1: {
            //     required: true,
            // },
            // temp_contry: {
            //     required: true,
            // },
            // temp_state: {
            //     required: true,
            // },
            // temp_city: {
            //     required: true,
            // },
            // address1: {
            //     required: true,
            // },
            // contry: {
            //     required: true,
            // },
            // state: {
            //     required: true,
            // },
            // city: {
            //     required: true,
            // },
        },
    });

    /* officials detail form validation */
    var officialDetailForm = $("#addOfficialDetail");
    officialDetailForm.validate({
        errorPlacement: function (error, element) {
            let div = element.parent().children().last();
            $(div).after("<span class='error-msg w-100'></span>");
            error.appendTo(div.next("span"));
        },
        rules: {
            // emp_code: {
            //     required: true,
            // },
            // experience: {
            //     required: true,
            //     number: true,
            // },
            // joining_date: {
            //     required: true,
            // },
            // confirmation_date: {
            //     required: true,
            // },
            team_id: {
                required: true,
            },
            // team_leader_id: {
            //     required: true,
            // },
            // offered_ctc: {
            //     required: true,
            // },
            // current_ctc: {
            //     required: true,
            // },
            // department_id: {
            //     required: true,
            // },
            // skype_id: {
            //     required: true,
            //     email: true,
            // },
            // company_gmail_id: {
            //     required: true,
            //     email: true,
            // },
            // company_gitlab_id: {
            //     email: true,
            // },
            // company_github_id: {
            //     email: true,
            // },
            // 'technologies_ids[]': {
            //     required: true,
            // },
            // 'reporting_ids[]': {
            //     required: true,
            // },
        },
    });

      /* user bank detail form validation */
    var bankDetailForm = $("#addBankDetail");
    bankDetailForm.validate({
        errorPlacement: function (error, element) {
            let div = element.parent().children().last();
            $(div).after("<span class='error-msg w-100'></span>");
            error.appendTo(div.next("span"));
        },
        rules: {
            personal_bank_name: {
                minlength: 3,
                maxlength: 30,
            },
            personal_bank_ifsc_code: {
                minlength: 5,
                maxlength: 30,
            },
            salary_bank_name: {
                minlength: 3,
                maxlength: 30,
            },
            salary_bank_ifsc_code: {
                minlength: 5,
                maxlength: 30,
            },
            personal_account_number: {
                digits: true,
            },
            salary_account_number: {
                digits: true,
            },
            aadharcard_number: {
                digits: true,
                maxlength: 12,
            },
        },
    });

    /* user experience detail form */
    var experienceDetailForm = $("#addExperienceDetail");

    /* user family detail form */
    var familyDetailForm = $("#addFamilyDetail");

    /* temporary address */
    $(document).on("change","#temp_country", function () {
        var idCountry = this.value;
        $("#temp_state").html("");
        $.ajax({
            url: getState,
            type: "POST",
            data: {
                country_id: idCountry,
                _token: tokenelem,
            },
            dataType: "json",
            for: "fetchState",
            success: function (result) {
                $("#temp_state").html('<option value="">Select State</option>');
                $.each(result, function (key, value) {
                    $("#temp_state").append(
                        '<option value="' +
                        key +
                            '">' +
                            value +
                            "</option>"
                    );
                });
                $("#temp_city").html('<option value="">Select City</option>');
            },
        });
    });
    $(document).on("change","#temp_state", function () {
        var idState = this.value;
        $("#temp_city").html("");
        $.ajax({
            url: getCity,
            type: "POST",
            data: {
                state_id: idState,
                _token: tokenelem,
            },
            dataType: "json",
            for: "fetchCity",
            success: function (res) {
                $("#temp_city").html('<option value="">Select City</option>');
                $.each(res, function (key, value) {
                    $("#temp_city").append(
                        '<option value="' +
                            key +
                            '">' +
                            value +
                            "</option>"
                    );
                });
            },
        });
    });

    /* permanent address */
    $(document).on("change","#country", function () {
        var idCountry = this.value;
        fetchState(idCountry);
    });

    $(document).on("change","#state", function (e) {
        var idState = this.value;
        fetchCity(idState);
    });

    $(document).on("change","#team_id", function (e) {
        getTeamLeaderMembers();
    });
});

function fetchState(idCountry){
    $("#state").html("");
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: getState,
            type: "POST",
            data: {
                country_id: idCountry,
                _token: tokenelem,
            },
            dataType: "json",
            for: "fetchState",
            success: function (result) {
                resolve(result);
                $("#state").html('<option value="">Select State</option>');
                $.each(result, function (key, value) {
                    console.log(value);
                    $("#state").append(
                        '<option value="' + key + '">' + value + "</option>"
                    );
                });
                $("#city").html('<option value="">Select City</option>');
            },
            error: function (error) {
                reject(error);
            },
        });
    });
}

function fetchCity(idState) {
    $("#city").html("");
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: getCity,
            type: "POST",
            data: {
                state_id: idState,
                _token: tokenelem,
            },
            dataType: "json",
            for: "fetchCity",
            success: function (res) {
                resolve(res);
                $("#city").html('<option value="">Select City</option>');
                $.each(res, function (key, value) {
                    $("#city").append(
                        '<option value="' + key + '">' + value + "</option>"
                    );
                });
            },
            error: function (error) {
                reject(error);
            },
        });
    });
}

function fillAddress(elem) {
    if (elem.is(":checked")) {
        $("#address1").val($("#temp_address1").val());
        $("#address2").val($("#temp_address2").val());
        $("#zipcode").val($("#temp_zipcode").val());
        var country = $("#temp_country").find(":selected").val();
        var state = $("#temp_state").find(":selected").val();
        var city = $("#temp_city").find(":selected").val();
        $("#country").val(country).select2();

        fetchState(country).then((data) => {
            $("#state option[value=" + state + "]").attr("selected", "selected");

            fetchCity(state).then((data) => {
                $("#city option[value=" + city + "]").attr("selected", "selected");
            }).catch((error) => {
                console.log(error);
            });

        }).catch((error) => {
            console.log(error);
        });
    } else {
        $("#address1").val("");
        $("#address2").val("");
        $("#zipcode").val("");
        $("#country").val("").select2();
        $("#state").val("").select2();
        $("#city").val("").select2();
    }
}

function getRepeaterDiv()
{
    $(".repeater").repeater({
        show: function () {
            $(this).slideDown();
            $(".select2-container").remove();
            $("select").select2();
            $(".select2-container").css("width", "100%");
        },
        hide: function (e) {
            // confirm("Are you sure you want to delete this element?") && $(this).slideUp(e);
            $(this).slideUp(e);
        },
        ready: function (e) {},
        isFirstItemUndeletable: true
    });

    window.isLoadedRepeaterDiv = true;
}

function displayPreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(".profile-user-img").attr("src", e.target.result);
            $(".photo-remove").show();
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function profileDisplayPreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(".preview-img").attr("src", e.target.result);
            $(".saveButtonHide").show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeEmployeePreview(){
    Swal.fire({
        title: "Are you sure?",
        text: "Are you sure want to remove image!",
        type: "warning",
        icon: 'warning',
        cancelButtonText: "Cancel",
        showCancelButton: true,
        cancelButtonClass: 'btn-secondary',
        showCloseButton: true,
        showConfirmButton: true,
        confirmButtonText: "Yes, delete it!",
        confirmButtonClass: 'btn-primary'
    }).then(function (e) {
        if (e.value === true) {
            var formData = new FormData($("#addPersonalDetail")[0]);
            $.ajax({
                type: 'POST',
                url: '/user/remove-image',
                headers: {
                    "X-CSRFToken": $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    toastr.success(response.message);
                    $(".profile-user-img").attr("src","/images/no-preview.png");
                    $("input[name='file_url']").val('');
                    $(".choosen-file").html("Drop or choose files");
                    $(".photo-remove").hide();
                    // window.setTimeout(function(){location.reload()},500)
                }
            });
        }else{
            e.dismiss;
        }
    },function (dismiss) {
        return false;
    });
}

$(document).ready(function(){
    $('#profile-upload-form').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: '/user/profile/profile-upload',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                // this.reset();
                toastr.success(response.success);
                $(".saveButtonHide").hide();
              }else if(response.errors){
                toastr.error(response.errors);
                $(".saveButtonHide").hide();
              }
            },
            error: function(response) {
                toastr.error(response.responseJSON.errors);
                $(".saveButtonHide").hide();
            },
        });
    });

    $("#changePasswordModalId").modal({
        backdrop: "static",
        keyboard: false,
    });

    $('#changePasswordModalId').on('hide.bs.modal', function () {
        location.reload();

        // Old Code :-
        // $("#curr-password").val('');
        // $("#new-password").val('');
        // $("#confirm-password").val('');
        // $("#curr-password").next("span").remove();
        // $("#new-password").next("span").remove();
        // $("#confirm-password").next("span").remove();
    });

    $('#change-password-form').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $('#pageLoader').show();
        $.ajax({
            type:'POST',
            url: changePasswordUrl,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
              if (response.success) {
                $('#pageLoader').hide();
                toastr.success(response.success);
                $(".saveButtonHide").hide();
                jQuery('#changePasswordModalId').modal('hide');
              }
            },
            error: function (response) {
                $('#pageLoader').hide();
                associate_errors(
                    $("#change-password-form"),
                    response.responseJSON.errors
                );
                if (response.status != 422) {
                    toastr.error(SometingWentWrong);
                }
            }
        });
    });
});

//delete event
jQuery(document).on('click', '.status-update', function (e) {
    e.preventDefault();
    let element = jQuery(this);

    console.log(element);

    statusConfirmation(element);
});

function statusConfirmation(element) {
    Swal.fire({
        title: "<b>Are you sure?</b>",
        type: "warning",
        html: "You wont be able to revert this!",
        icon: "warning",
        cancelButtonText: "Cancel",
        showCancelButton: true,
        cancelButtonClass: "btn-secondary",
        showConfirmButton: true,
        confirmButtonText: "Yes, change it!",
        confirmButtonClass: "btn-primary",
    }).then(
        function (e) {
            if (e.value === true) {
                let statusUpdate = $(element).data('value');
                $.ajax({
                    data: {
                        _token: tokenelem,
                    },
                    url: statusUpdate,
                    type: "POST",
                    success: function (response) {
                        datatalbe.draw();
                        toastr.success(response.message);
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

function initPersonalForm() {

    $("select").select2({
        minimumResultsForSearch: 1,
    });

    // if(($('.user_id_edit').val() == undefined)){
    //     //select india country by default
    //     $("#temp_country,#country").trigger("change");
    //     setTimeout(() => {
    //         $("#temp_state, #state").val(1339).trigger("change");
    //     }, 300);
    //     setTimeout(() => {
    //         $("#temp_city, #city").val(131575).trigger("change");
    //     }, 600);
    // }

    let eighteenYearsAgo = new Date();
    eighteenYearsAgo = eighteenYearsAgo.setFullYear(eighteenYearsAgo.getFullYear()-18);
    var endBirthdate = $.datepicker.formatDate("dd-mm-yy", new Date(eighteenYearsAgo));
    $("#birthdate").datepicker({
        format: "dd-mm-yyyy",
        endDate: endBirthdate,
        autoclose: true,
    });

     $("#wedding_date_input").datepicker({
        format: "dd-mm-yyyy",
        endDate: new Date(),
        autoclose: true,
    });

// official details date
    $("#joining_date").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
    });
    // .on("changeDate", function (selected) {
    //     var nextDay = new Date(selected.date);
    //     nextDay.setDate(nextDay.getDate() + 1);
    //     $("#confirmation_date").datepicker("setStartDate", nextDay);
    // });

    $("#confirmation_date").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
    });

    $("#resigned_date").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
    });
    // .on("changeDate", function (selected) {
    //     var minDate = new Date(selected.date.valueOf());
    //     $("#joining_date").datepicker("setEndDate", minDate);
    // });

    $("#task_entry_date").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
    });

    $("#joined_date").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var nextDay = new Date(selected.date);
            nextDay.setDate(nextDay.getDate() + 1);
        $('#released_date').datepicker('setStartDate', nextDay);
    });

    $("#released_date").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
    }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#joined_date').datepicker('setEndDate', minDate);
    });
    if ($("#maritalStatus").val() == "married"){
         $("#wedding_date_input").removeAttr("disabled");
         $("#wedding_date_input").removeAttr("readonly");
    }

    $(document).on("change","#maritalStatus", function () {
        if(this.value != 'married'){
            $("#wedding_date_input").val('');
            $("#wedding_date_input").attr("disabled", "disabled");
            $("#wedding_date_input").attr("readonly", "readonly");
        } else {
            $("#wedding_date_input").removeAttr("disabled");
            $("#wedding_date_input").removeAttr("readonly");
        }
    });

    $(".password-icon-class").on("click", function (e) {
        0 < $(this).siblings("input").length &&
            ("password" == $(this).siblings("input").attr("type")
                ? $(this).siblings("input").attr("type", "input")
                : $(this).siblings("input").attr("type", "password"));
    });
}

function getTeamLeaderMembers() {
    $("#tl_mentors").html("");
    $("#members_mentor").html("");
    // $(".tl-section").addClass('hide');
    // $("#tl_members .select2-container").remove();
    var role = $("#role_id option:selected").text();

    // if(role != 'Team Leader') {
    //     $(".tl-member-section").addClass('hide');
    //     $(".tl-section").removeClass('hide');
    //     return;
    // }
    // $(".tl-member-section").removeClass('hide');
    var team_id = $("#team_id").val();

    if(team_id > 0) {
        $.ajax({
            url: teamLeaderMembers,
            data: {
                user_id : $("#addPersonalDetail .user_id").val(),
                team_id : team_id,
            },
            success: function(response) {
                if (response.status) {
                    $.each(response.data.members, function (index, member) {
                        $("#tl_mentors").append(
                            '<option value="' + member.id + '">' + member.full_name + "</option>"
                        );
                        $("#members_mentor").append(
                            '<option value="' + member.id + '">' + member.full_name + "</option>"
                        );
                    });
                    $("#members_mentor").val(response.data.selected_members).select2();
                    $("#tl_mentors").val(response.data.selected_mentors).select2();
                }
            },
            error: function(response) {
                console.log(response);
                toastr.error(response.responseJSON.errors);
            }
        });
    }
}
