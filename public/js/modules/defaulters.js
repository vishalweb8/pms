var filterData = {date:'today'};
$(document).ready(function () {
    if($("#team").attr('disabled') !== 'disabled') {
        $("#team").val(null).select2({
            minimumResultsForSearch: -1,
        }).trigger('change');
    }else {
        filterData['team'] = $("#team").val();
    }

    /* team filter */
    $(document).on("change", "#team", function () {
        filterData['team'] = this.value;
        $(".resourceEntries").empty();
        moduleToExecute(filterData);
        // infinteDefaulterLoadMore(filterData);
    });

    /* date filter */
    $(document).on("change", "#date_fitler", function () {
        filterData['date'] = this.value;
        $(".resourceEntries").empty();
        moduleToExecute(filterData);
        // infinteDefaulterLoadMore(filterData);
    });

    moduleToExecute(filterData);
    // if ($('#defaulterEntries').length > 0) {
    //     infinteDefaulterLoadMore(filterData);
    // }
    // if ($('#freeResourceEntries').length > 0) {
    //     infinteFreeResourceLoadMore(filterData);
    // }
});

function moduleToExecute(filterData) {
    if ($('#defaulterEntries').length > 0) {
        infinteDefaulterLoadMore(filterData);
    } else if ($('#freeResourceEntries').length > 0) {
        infinteFreeResourceLoadMore(filterData);
    } else if ($('#workLogDefaulterEntries').length > 0) {
        filterData.workLogDefaulter = 1;
        infinteWorkLogDefaulterLoadMore(filterData);
    }

}

function infinteDefaulterLoadMore(filter) {
    filter = $.param(filter);
    let siteUrl = ENDPOINT + "/works/get-defaulters?" + filter;
    listAjaxCall(siteUrl, "defaulterEntries");
}

function infinteFreeResourceLoadMore(filter) {
    filter = $.param(filter);
    let siteUrl = ENDPOINT + "/works/get-free-resources?" + filter;
    listAjaxCall(siteUrl, "freeResourceEntries");

}

function infinteWorkLogDefaulterLoadMore(filter) {
    filter = $.param(filter);
    let siteUrl = ENDPOINT + "/works/get-defaulters?" + filter;
    listAjaxCall(siteUrl, "workLogDefaulterEntries");
}

function listAjaxCall(url, listName) {
    $.ajax({
        url: url,
        datatype: "html",
        type: "get",
        beforeSend: function () {
            $(".auto-load").show();
        },
    })
    .done(function (response) {
        $("#" + listName).empty();
        if (response.length == 0) {
            $(".auto-load").html(`<div class="bg-light-primary p-3 text-primary"><i class="bx bx-confused font-size-22"></i><h6 class="m-0">We don't have any data to display</h6></div>`);
            return;
        }
        $(".auto-load").hide();
        $("#" + listName).append(response);
        removeBlankRow();
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
        console.log("Server error occured");
    });
}


function removeBlankRow() {
    $('tbody tr').each(function( index ) {
        let count = $(this).find('.sod-eod.all-emp-name-list:not(.d-none)').length;
        if(count == 0) {
            console.log($(this));
            $(this).remove();
        }
    })
}
