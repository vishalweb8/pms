<script>
    jQuery(document).on("change", `select[name='{{ $parent_ele }}']`, function () {
        let selected_ele = jQuery(this)
        let form_data = {};
        form_data[`${selected_ele.attr('name')}`] = selected_ele.val();
        $.ajax({
            url: `{{ $url }}`,
            type: "POST",
            data: form_data,
            dataType: "json",
            success: function (result) {
                // remove the values of optioins datatable
                let empty_arrays = @json($clear_dropdowns);
                jQuery.each(empty_arrays, function(key, value) {
                    jQuery(`select[name='${value}'] option[value!='']`).remove();
                });

                // clear the child-dropdown, don't touch the first one
                jQuery(`select[name='{{ $child_ele }}'] option[value!='']`).remove();

                //Add the new value into child dropdown
                jQuery.each(result, function(key, value) {
                    jQuery(`select[name='{{ $child_ele }}']`).append(`<option value='${key}'>${value}</option>`)
                });
            },
        });
    });
</script>
