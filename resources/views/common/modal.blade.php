<div id="dynamicModal" class="modal fade" tabindex="-1" aria-labelledby="dynamicModal" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered {{ $modal_size ?? '' }}">
        <div class="modal-content">
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@push('scripts')

<script type="text/javascript">
    $(document).ready(function(){
        $('#dynamicModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('#dynamicModal').on('shown.bs.modal', function () {
        $(this).find('.select2').select2({
            dropdownParent: $('#dynamicModal')
        });
        $(this).find('.select2-multiple').select2({
            dropdownParent: $('#dynamicModal')
        });
        $(".repeater").repeater({
            defaultValues: { "textarea-input": "foo", "text-input": "bar", "select-input": "B", "checkbox-input": ["A", "B"], "radio-input": "B" },
            show: function () {
                $(this).slideDown();

                $('select.form-select2').each(function (index) {
                    $(this).prop('id', $(this).attr('name')).select2( { width: "100%",  dropdownParent: $('#dynamicModal')});
                });

                if($(this).find('select.select2').length > 0) {
                    $(this).find('.select2').removeClass('select2-hidden-accessible');
                    $(this).find('.select2-container').remove();
                    $('.select2').each(function (index) {
                        $(this).prop('id', $(this).attr('name')).select2( { width: "100%",  dropdownParent: $('#dynamicModal')});
                    });
                }
            },
            hide: function (e) {
                // confirm("Are you sure you want to delete this element?") && $(this).slideUp(e);
                $(this).slideUp(e);
            },
            isFirstItemUndeletable: true
        });
    });

    // For detact the click of the edit button and show the data into popup modal
    jQuery(document).on('click', '.add-btn',function (e) {
        e.preventDefault();
        let url = jQuery(this).data('url');
        getCreateHTML(url); // Get the HTML from the url link
    });
    // For show the comments on the popup screen
    jQuery(document).on('click', '.show-comments',function (e) {
        e.preventDefault();
        let url = jQuery(this).data('url');
        getCreateHTML(url); // Get the HTML from the url link
    });
    // For detact the click of the edit button and show the data into popup modal
    jQuery(document).on('click', '.dataTable .edit-record, .edit-record',function (e) {
        e.preventDefault();
        let url = jQuery(this).attr('href');
        getEditHTML(url); // Get the HTML from the url link
    });

    /* For get the edit page html and replace it with the modal-content section data of the
    dynamicModal  */
    function getCreateHTML(create_url) {
        $.ajax({
            url: create_url,
            type: 'GET',
            success : function(response){
                jQuery('#dynamicModal').find('.modal-content').empty().append(response)
                jQuery('#dynamicModal').modal('show');
            },
            error: function(response) {
                toastr.error("Something went wrong !!");
            }
        });
    }

    /* For get the edit page html and replace it with the modal-content section data of the
    dynamicModal  */
    function getEditHTML(edit_url) {
        //Hide Dropzone
        $.ajax({
            url: edit_url,
            type: 'GET',
            success : function(response){
                jQuery('#dynamicModal').find('.modal-content').empty().append(response)
                jQuery('#dynamicModal').modal('show');
                $(".file-input-wrapper").hide();
            },
            error: function(response) {
                toastr.error("Something went wrong !!");
            }
        });
    }

    jQuery(document).on('click', '.showDropzone',function(e){
        $(".file-input-wrapper").show();
        $(".showDropzone").hide();
    });

    jQuery(document).on('submit', '#dynamicModal form',function (e) {
        e.preventDefault();
        let form = jQuery(this);
        let form_url = form.attr('action');
        let form_options = {};
        let inputs = "";
        form_options['form_id'] = form.attr('id');
        if(form.find('input[type="file"]').length > 0) {
            // Serialize the entire form:
            let temp = $(`#${form.attr('id')}`)[0];
            inputs = new FormData(temp);
            form_options['with_file_input'] = true;
        }else {
            inputs = form.serializeArray();
        }

        if(form.hasClass('add-form')) {
            storeData(form_url, inputs, form_options);
        }else {
            updateData(form_url, inputs, form_options);
        }
    });

    function storeData(form_url, inputs, options) {
        let file_related_options = {};
        if(options.hasOwnProperty('with_file_input')) {
            file_related_options['contentType'] = false;
            file_related_options['processData'] = false;
        }
        $('#pageLoader').show();
        $.ajax({
            data: inputs,
            url: form_url,
            type: 'POST',
            ...file_related_options,
            success : function(response){
                $('#pageLoader').hide();
                toastr.success(response.message);
                jQuery("#dynamicModal").modal('hide');
                if(datatalbe != undefined){
                    toastr.success(response.message);
                    datatalbe.draw();
                }else if(AllEmpPage == true) {
                    $("#allEmployees").empty();
                    infinteEmployeeLoadMore(1);
                }else {
                    if(response.status == "success")
                        window.location.reload();
                }
            },
            error: function(response) {
                $('#pageLoader').hide();
                associate_errors(`#${options.form_id}`, response.responseJSON.errors);
                if(response.status != 422){
                    toastr.error("Something went wrong !!");
                }
            }
        });
    }

    function updateData(form_url, inputs, options) {
        let file_related_options = {};
        if(options.hasOwnProperty('with_file_input')) {
            file_related_options['contentType'] = false;
            file_related_options['processData'] = false;
            file_related_options['type'] = 'POST';
        }else{
            file_related_options['type'] = 'PATCH';
        }
        $('#pageLoader').show();
        $.ajax({
            data: inputs,
            url: form_url,
            ...file_related_options,
            success : function(response){
                $('#pageLoader').hide();
                jQuery("#dynamicModal").modal('hide');
                if(datatalbe != undefined){
                    toastr.success(response.message);
                    datatalbe.draw();
                }else {
                    toastr.success(response.message);
                    if(response.status == "success")
                        window.location.reload();
                }
            },
            error: function(response) {
                console.log(`${options.form_id}`);
                $('#pageLoader').hide();
                associate_errors(`#${options.form_id}`, response.responseJSON.errors);
                if(response.status != 422){
                    toastr.error("Something went wrong !!");
                }
            }
        });
    }

    function makeid(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() *
    charactersLength));
    }
    return result;
    }
</script>
@endpush
