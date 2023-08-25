<script>
    $(document).ready(function(){

        $("#Addevent, #Editevent, #EventDescription").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#Addevent").on('hidden.bs.modal', function(e) {

            var ct = new Date($.now());
            var hours = ct.getHours();
            var minutes = ct.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0'+minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;

            $("input[name='event_name']").val('');
            $("input[name='event_date']").val('');
            $("input[name='start_time']").val(strTime);
            $("input[name='end_time']").val(strTime);
            $("textarea[name='description']").val('');
            $("#eventNameMsg").empty();
            $("#eventDateMsg").empty();
            $("#eventTimeMsg").empty();
            $("#descriptionMsg").empty();
            $("#fileUrlMsg").empty();

            $("input[name='file_url']").val('');
            $(".fileUrlMsg").empty();
            $(".fileUrlErrMsg").empty();

            $('.choosen-file').html('');
            $(".choosen-file").html('Drop or choose files');
        });

        $("#Addevent").on('show.bs.modal', function(){
           // $(".choosen-file").show();
        });

        $("#Editevent").on('show.bs.modal', function(){
           // $(".choosen-file").show();
        });

        $("#Editevent").on('hidden.bs.modal', function() {
            $("input[name='file_url']").val('');
            $(".fileUrlErrMsg").empty();
            $('.choosen-file').html('');
            $(".choosen-file").html('Drop or choose files');
        });

        $("input").attr("autocomplete", "off");

        $("#timepicker5").timepicker({
            icons:{
                up:"mdi mdi-chevron-up",
                down:"mdi mdi-chevron-down"
            },
            appendWidgetTo:"#timepicker-input-group5",
        });

        $("#timepicker6").timepicker({
            icons:{
                up:"mdi mdi-chevron-up",
                down:"mdi mdi-chevron-down"
            },
            appendWidgetTo:"#timepicker-input-group6"
        });

        $("#timepicker7").timepicker({
            icons:{
                up:"mdi mdi-chevron-up",
                down:"mdi mdi-chevron-down"
            },
            appendWidgetTo:"#timepicker-input-group7"
        });

        $('#description').val('');

        //show dropzone
        $(document).on('click', '.eventFileShow',function(e){
            $(".file-input-wrapper").show();
        });

        //file validation for not be greater than 10MB
        $('.fileUrl').on('change', function() {
            var numb = $(this)[0].files[0].size / 1024 / 1024;
            numb = numb.toFixed(10);
            if (numb > 10) {
                $(".fileUrlMsg").html("The file url must not be greater than 10MB");
                $(".fileUrlErrMsg").html("The file url must not be greater than 10MB");
                $("input[name='file_url']").val('');
                $(".choosen-file h6").empty();
            }else{
                $(".fileUrlMsg").empty();
                $(".fileUrlErrMsg").empty();
            }
        });

        /* Add Event Ajax Form */
        $("#event_form").submit(function(e){
            e.preventDefault();
            }).validate({
                rules: {
                    event_name: { required: true },
                    event_date: { required: true},
                    start_time: { required: true},
                    end_time: { required: true},
                    description: { required: true },
                },
                messages: {
                    event_name: { required: "Please enter event name" },
                    event_date: { required: "Event date is required"},
                    start_time: { required: "Event start time is required"},
                    end_time: { required: "Event end time is required"},
                    description: { required: "Event description is required"},
                },
                submitHandler: function (form){
                    var formData = new FormData($("#event_form")[0]);
                    $('#pageLoader').show();
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/event/create') }}",
                        headers: {
                            "X-CSRFToken": $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        cache:false,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $('#pageLoader').hide();
                            $("#Addevent").modal('hide');
                            // toastr.success(data.success);
                            window.setTimeout(function(){location.reload()},100);
                        },
                        error: function(response){
                            $('#pageLoader').hide();
                            $('#eventEndTimeMsg').text(response.responseJSON.errors.end_time);
                        }
                    });
                }
        });

        /* Edit Ajax Form */
        $("#edit_event").submit(function(e){
            e.preventDefault();
            }).validate({
                rules: {
                    event_name: { required: true },
                    event_date: { required: true},
                    start_time: { required: true},
                    end_time: { required: true},
                    description: { required: true },
                },
                messages: {
                    event_name: { required: "Please enter event name" },
                    event_date: { required: "Event date is required"},
                    start_time: { required: "Event start time is required"},
                    end_time: { required: "Event end time is required"},
                    description: { required: "Event description is required"},
                },
                submitHandler: function (form){
                    var formData = new FormData($('#edit_event')[0]);
                    $('#pageLoader').show();
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/event/update') }}",
                        headers: {
                            "X-CSRFToken": $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        cache:false,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $('#pageLoader').hide();
                            $('#Editevent').modal('hide');
                            toastr.success(data.success);
                            window.setTimeout(function(){location.reload()},500)
                        },
                        error: function(response){
                            $('#pageLoader').hide();
                            $('#eventEndTimeErrMsg').text(response.responseJSON.errors.end_time);
                        }
                    });
                }
        });

        $("#filter").on('click', function(){
            getData();
        });

        $('#search').on('keyup', function(){
            getData();
        });

        $('#eventStatus').change(function(){
            getData();
        });

        $("#start").change(function(){
            getData();
        })

        $("#end").change(function(){
            getData();
        })

        $(document).on('click','.pagination a.page-link', function(e){
            e.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');

            var page = $(this).attr('href');
            console.log(page);

            getData(page);
        });
    });

    function getData(page) {
        var data = {};
        if(!page){
            data['start'] = $("#start").val();
            data['end'] = $("#end").val();
            data['search'] = $("#search").val();
            data['status'] = $("#eventStatus").val();
        }

        $.ajax({
            url: (!page) ? '{{url('/event')}}' : page,
            type: 'GET',
            data: data,
            success: function(response){
                $("#mainCard").empty();
                $("#mainCard").append(response.html);
                $("#pagination").empty();
                $("#pagination").append(response.pagination);
            }
        });
    }

    function resetData(){
        var start = $("#start").val("");
        var end = $("#end").val("");
        var search = $("#search").val("");
        var status = $("#eventStatus").val("select").trigger("change");
        var reset = $("#reset").val("");

        getData();
    }

    //Set attribute value for event
    function setAttrValue(data){
        var eventId = $(data).attr('data-id');
        $("#eventId").attr('value', eventId);
        var getUrl = "{{url('/event/edit')}}";
        $.ajax({
            url: getUrl,
            type: "GET",
            dataType: 'json',
            data:{
                id: eventId
            },
            success: function(response){
                console.log(response);
                $("#eventBody #edit-eventName").val(response.data.event_name);
                $("#eventBody #edit-eventDate").val(response.data.event_date);
                $("#eventBody #timepicker").val(response.data.start_time);
                $("#eventBody #timepicker7").val(response.data.end_time);
                $("#eventBody #edit-description").val(response.data.description);
                $("#eventBody .eventFile").html(response.data.file_url);
                if(response.data.file_url){
                    $("#eventBody #view_event").attr('href', response.file);
                    $("#eventBody #event-File-Show").show();
                    $(".file-input-wrapper").hide();
                }else{
                    $("#eventBody #event-File-Show").hide();
                }
            }
        });
    }

    //delete event
    function deleteConfirm(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "Are you sure want to delete event!",
            type: "warning",
            icon: 'warning',
            cancelButtonText: "Cancel",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary',
            showConfirmButton: true,
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: 'btn-primary'
        }).then(function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{url('/event/delete')}}/" + id,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success === true) {
                            Swal.fire("Done!", results.message, "success").then(function(){
                                toastr.success(results.message);
                                window.setTimeout(function(){location.reload()},500)
                            });
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
    }

    // set description
    function setDescription(id) {
        var eventId = $(id).attr('data-id');
        $("#eventId").attr('value', eventId);
        var getUrl = "{{url('/event/description')}}";
        $.ajax({
            url: getUrl,
            type: "GET",
            dataType: 'json',
            data:{
                id: eventId
            },
            success: function(response){
                $("#eventDescription #Edescription").html(response.data.description);
                $("#eventTitle").html(response.data.event_name);
            }
        });
    }
</script>
