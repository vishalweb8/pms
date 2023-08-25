<div class="modal fade" tabindex="-1" id="known-tech-modal">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="user_name"> {{ $user_name ?? '' }}</span>'s technologies skill </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-table">

            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $("#known-tech-modal").modal({
            backdrop: "static",
            keyboard: false,
        });
        $(document).on('click','.known-tech', function(e){
            $('#pageLoader').show();
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('#pageLoader').hide();
                    if(response.status) {
                        $("#user_name").html(response.data.full_name);
                        var skills = '';
                        $.each(response.data.technologies, function (key, val) {
                            skills += '<span class="badge badge-light-info" style="margin-right:10px; margin-bottom: 5px;">'+val+'</span>';
                        });

                        if(skills == '') {
                            skills = '<div style="text-align: center;"><h6 class="text-muted mt-2 ">Data Not Available</h6></div>';
                        }
                        $("#known-tech-modal .modal-body").html(skills);
                        $("#known-tech-modal").modal("show");
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (response) {
                    $('#pageLoader').hide();
                    toastr.error("Something went wrong !!");
                },
            });
        });
    });
</script>
@endpush
