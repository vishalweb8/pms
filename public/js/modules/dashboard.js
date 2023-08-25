var filterData = null;
var projectSearch = null;

$(document).ready(function () {
    var findDataFlag = $("#findDataFlag").val();
    var checkPage = $("#checkPage").val();
    if(typeof(fixed_team) !== 'undefined') {
        filterData = fixed_team;
    }
    /* team filter */
    $(document).on("change", "#team_id,#project-status,#experience", function () {
        
        page = 1;
        $("#allEmployees").empty();
        infinteEmployeeLoadMore(page);
    });

    $(document).on("click", "#sortByExperience", function () {
        
        page = 1;
        $("#allEmployees").empty();
        let sortBy = $(this).data('sort');
        let updateSortBy = (sortBy == 'asc') ? 'desc' : 'asc';
        $(this).data('sort',updateSortBy);
        infinteEmployeeLoadMore(page,null,sortBy);
    });

    $(document).on("change", "#team_emp_exp", function () {

        filterData = this.value;
        page = 1;
        $("#allEmployeesExp").empty();
        infinteEmployeeExpLoadMore(1, filterData);
    });

    /* project filter */
    $(document).on("keyup", "#projectName", function () {
        projectSearch = this.value;
        page = 1;
        $("#allProject").empty();
        infinteProjectLoadMore(page, projectSearch);
    });

    /* bde filter */
    $(document).on("keyup", "#projectNameBDE", function () {
        projectSearch = this.value;
        page = 1;
        $("#allProjectBDE").empty();
        infinteProjectBDELoadMore(page, projectSearch);
    });

     /* team filter */
     $(document).on("keyup", "#teamName", function () {
        projectSearch = this.value;
        page = 1;
        $("#allTeam").empty();
        infinteTeamLoadMore(page, projectSearch);
    });

    $(document).on("keyup", "#userName", function () {
        page = 1;
        $("#checkPage").closest('div').find('tbody').empty();
        loadMoreData(checkPage)
    });

    /* filter */
    var selector = "#team,#daily-task-month,#financialSingleYear,#userType,#employee,#start_date,#end_date,#projects";
    $(document).on("change", selector, function () {
        page = 1;
        $("#checkPage").closest('div').find('tbody').empty();
        $("#filterdWorkLog").empty();
        loadMoreData(checkPage,false)
    });

    if($(document).find('.fixed-header .stick-column').length > 0) {
        $(document).find('.fixed-header').on('scroll', function () {
            callLoadMoreOnScroll(findDataFlag, checkPage);
        });
    }else {
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                callLoadMoreOnScroll(findDataFlag, checkPage);
            }
        });

    }
    $("#start_date")
        .datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
        })
        .on("changeDate", function (selected) {
            var nextDay = new Date(selected.date);
            nextDay.setDate(nextDay.getDate());
            $("#end_date").datepicker("setStartDate", nextDay);
        });
    $("#end_date")
        .datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            minDate:new Date(),
        })
        .on("changeDate", function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $("#start_date").datepicker("setEndDate", minDate);
        });

    $(document).on("click", ".unassigned-project", function () {
        var self = this;
        var projectId = $(this).data('project-id');
        var userId = $(this).data('user-id');
        Swal.fire({
            title: "Are you sure?",
            text: "Are you want to unassigned this project!",
            type: "warning",
            icon: 'warning',
            cancelButtonText: "Cancel",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary',
            showConfirmButton: true,
            confirmButtonText: "Yes",
            confirmButtonClass: 'btn-primary'
        }).then(function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: REMOVE_PROJECT_URL,
                    data: {_token: CSRF_TOKEN,project_id: projectId,user_id: userId},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success === true) {
                            $(self).prev("a").remove();
                            let isEmptyProject = $(self).closest('div').find('a').html();
                            if(typeof isEmptyProject == 'undefined') {
                                console.log(isEmptyProject);
                                $(self).closest('div').append('<span class="badge bg-danger font-size-12">No Project Assigned</span>');
                            }
                            $(self).closest('span').remove();
                            toastr.success(results.message);
                        } else {
                            Swal.fire("Error!", results.message, "error");
                        }
                    }
                });
            } else {
                e.dismiss;
            }
        }, function (dismiss) {
            return false;
        })
    });

});

function addNoProjectLabel() {

}


function callLoadMoreOnScroll(findDataFlag, checkPage) {
    console.log('callloadmoreonscroll', findDataFlag);
    if (findDataFlag == 1) {
        page++;
        // loadMoreData(checkPage);
    }
}

function loadMoreData(checkPage,loadMore=true) {
    if (checkPage == "employeePage") {
        infinteEmployeeLoadMore(page, filterData);
    }
    if (checkPage == "employeeExpPage") {
        infinteEmployeeExpLoadMore(page, filterData);
    }
    if (checkPage == "totalTeamsPage") {
        infinteTeamLoadMore(page, projectSearch);
    }
    if (checkPage == "allBDEsPage") {
        infinteProjectBDELoadMore(page, projectSearch);
    }
    if (checkPage == "totalProjectsPage") {
        infinteProjectLoadMore(page, projectSearch);
    }
    if (checkPage == "dailyWorkLog") {
        infinteDailyWorkLog(page,null,loadMore);
    }
    if (checkPage == "dailyTimeEntry") {
        infinteTimeEntry(page,loadMore);
    }
}
// For fix the two popover show issue in the employee worklog screen
$(document).on('click', '[data-bs-toggle="popover"]', function (e) {
    e.stopPropagation(); // Stop propagation
    $(document).find('.popover').remove(); // Remove the popover div manually
    $(this).popover('show'); // Show the latest popover
})
function infinteEmployeeLoadMore(page, filter = null, sortBy = null) {
    let teamID = $("#team_id").val();
    let status = $("#project-status").val();
    let experience = $("#experience").val();
    let siteUrl = ENDPOINT + "/all-resource-list?page=" + page + "&team_id=" + teamID+ "&status="+status + "&experience="+experience+'&sortBy='+sortBy;
    listAjaxCall(siteUrl, "allEmployees", filter);
}
function infinteEmployeeExpLoadMore(page, filter) {
    let siteUrl = ENDPOINT + "/admin-employee-experience?page=" + page;
    if (filter != null) {
        siteUrl = ENDPOINT + "/admin-employee-experience?page=" + page + "&team_id=" + filter;
    }
    listAjaxCall(siteUrl, "allEmployeesExp", filter);
}

function infinteProjectLoadMore(page, filter = null) {
    let siteUrl = ENDPOINT + "/admin-projects?page=" + page;
    if (filter != null) {
        siteUrl = ENDPOINT + "/admin-projects?page=" + page + "&project=" + filter;
    }
    listAjaxCall(siteUrl, "allProject");
}

function infinteProjectBDELoadMore(page, filter = null) {
    let siteUrl = ENDPOINT + "/admin-projects-bde?page=" + page;
    if (filter != null) {
        siteUrl = ENDPOINT + "/admin-projects-bde?page=" + page + "&project=" + filter;
    }
    listAjaxCall(siteUrl, "allProjectBDE", filter);
}

function infinteDailyWorkLog(page, filter = null,loadMore = false) {
    let taskMonth = $("#daily-task-month").val();
    let taskYear = $("#financialSingleYear").val();
    let userType = $("#userType").val();
    let userName = $("#userName").val();
    let startDate = $("#start_date").val();
    let endDate = $("#end_date").val();
    let employee = $('#employee').val();
    let projects = $('#projects').val();
    
    let activeTab = $('.nav-pills a.active').attr("data-tab-number");
    console.log(activeTab);
    if(activeTab == 2) {
        let teamID = $("#allempwork #team").val();
        let addOnFilter = '&teamID=' + teamID+ '&startDate='+startDate + '&endDate='+endDate + '&employee='+employee+"&projects="+projects;
        
        var siteUrl = ENDPOINT + "/admin-filtered-worklog?page=" + page + addOnFilter;
        listAjaxCall(siteUrl, "filterdWorkLog",null,loadMore);
    } else {
        let teamID = $("#dailywork #team").val();
        let addOnFilter = '&user='+userName+'&teamID=' + teamID + '&taskMonth=' +taskMonth + '&taskYear='+taskYear + '&userType='+userType + '&employee='+employee;
        var siteUrl = ENDPOINT + "/admin-monthly-worklog?page=" + page + addOnFilter;

        listAjaxCall(siteUrl, "allDailyWorkLog",null,loadMore);
    }

}

function infinteTimeEntry(page,loadMore=false) {
    let teamID = $("#team").val();
    let month = $("#daily-task-month").val();
    let year = $("#financialSingleYear").val();
    let userType = $("#userType").val();
    let userName = $("#userName").val();
    let startDate = $("#start_date").val();
    let endDate = $("#end_date").val();
    let employee = $('#employee').val();



    let addOnFilter = '&user=' +userName + '&teamID=' + teamID + '&month=' +month + '&year='+year + '&userType='+userType + '&startDate='+startDate + '&endDate='+endDate + '&employee='+employee;
    let siteUrl = ENDPOINT + "/admin-monthly-time-entry?page=" + page + addOnFilter;
    listAjaxCall(siteUrl, "allDailyTimeEntry",null,loadMore);
}

function infinteTeamLoadMore(page, filter = null) {
    let siteUrl = ENDPOINT + "/admin-all-team?page=" + page;
    if (filter != null) {
        siteUrl = ENDPOINT + "/admin-all-team?page=" + page + "&team=" + filter;
    }
    listAjaxCall(siteUrl, "allTeam", filter);
}

function listAjaxCall(url, listName, filter = null,loadMore=true) {

    let urlParams = getUrlVars(url);
    let isSticksColumn = false;
    $.ajax({
        url: url,
        datatype: "html",
        type: "get",
        beforeSend: function () {
            $(".auto-load").show();
        },
    }).done(function (response) {
        if(typeof(response) === 'object') {
            if(loadMore == false && (listName == 'allDailyWorkLog' || listName == 'filterdWorkLog')){
                $("#" + listName).html(response.returnHTML);
            } else {
                $("#" + listName).append(response.returnHTML);
            }

            $('.date-cell').remove();
            $('#dailyWorkLogTable thead tr').append(response.rangeHTML);
            $('#dailyWorkLogTable thead tr:first-child th').each(function (index, element) {
                // console.log(element);
                if($(element).hasClass('stick-column')) {
                    isSticksColumn = true;
                    $(`#dailyWorkLogTable tbody tr td:nth-child(${index+1})`).addClass(`stick-column col-no-${index+1}`);
                }
            });

            if(isSticksColumn) {
                makeStickyColumns();
            }


            if (response.returnHTML.length == 0) {
                $("#findDataFlag").val(0);
                $(".auto-load").html(`<div class="bg-light-primary p-3 text-primary"><i class="bx bx-confused font-size-22"></i><h6 class="m-0">We don't have more data to display</h6></div>`);
                return;
            }
            $(".auto-load").hide();
            // var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            // var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            //     return new bootstrap.Popover(popoverTriggerEl,{container: 'body', html: true, customClass: 'worklog-tool-tip'})
            // })
        }else  {
            if (response.length == 0) {
                $("#findDataFlag").val(0);
                $(".auto-load").html(`<div class="bg-light-primary p-3 text-primary"><i class="bx bx-confused font-size-22"></i><h6 class="m-0">We don't have more data to display</h6></div>`);
                return;
            }
            $(".auto-load").hide();
            if(urlParams['page'] && urlParams['page'] == 1 && filter != null){
                $("#" + listName).empty();
            }
            $("#" + listName).append(response);
        }

    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
        console.log(jqXHR);
        console.log("Server error occured");
    });
}
function getUrlVars(url)
{
    var vars = [], hash;
    var hashes = url.slice(url.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
