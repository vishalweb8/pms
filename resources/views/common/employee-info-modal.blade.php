<div class="modal fade" tabindex="-1" id="emp-info-modal">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Employee Information </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-table">
                <div class="profile-details">
                    <div class="profile-view">
                        <div class="row">
                            <div class="col-12">
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Name:</div>
                                        <div class="text emp-name">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Phone:</div>
                                        <div class="text emp-phone"><a href="">1234567890</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text text-lowercase emp-email"><a href="mailto:admin@test.com">admin@test.com</a></div>
                                    </li>
                                    <li>
                                        <div class="title">Skype:</div>
                                        <div class="text emp-skype">21-02-1990
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Experience:</div>
                                        <div class="text emp-expr">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Known Technology:</div>
                                        <div class="text emp-known-tech">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
     $(document).ready(function() {
        $("#emp-info-modal").modal({
            backdrop: "static",
            keyboard: false,
        });
        $(document).on('click','.emp-info', function(e){
            $('#pageLoader').show();
            $.ajax({
                url: "{{ route('user.getInfo')}}",
                data: {id: $(this).data('id') },
                success: function (response) {
                    $('#pageLoader').hide();
                    if(response.status) {
                        var skills = '';
                        $.each(response.data.technologies, function (key, val) {
                            skills += '<span class="badge badge-light-info" style="margin-right:10px; margin-bottom: 5px;">'+val+'</span>';
                        });

                        if(skills == '') {
                            skills = '<div style="text-align: center;"><h6 class="text-muted mt-2 ">Data Not Available</h6></div>';
                        }

                        let phone = response.data.phone_number || '';
                        let email = response.data.email;
                        let skype = response.data.official_user.skype_id || '';

                        $(".emp-name").html(response.data.full_name);
                        $(".emp-phone").html("<a href='tel:"+phone+"'>"+phone+"</a>");
                        $(".emp-email").html("<a href='mailto:"+email+"'>"+email+"</a>");
                        $(".emp-skype").html("<a href='skype:"+skype+"'>"+skype+"</a>");
                        $(".emp-expr").html(response.data.expr);
                        $(".emp-known-tech").html(skills);
                        $("#emp-info-modal").modal("show");
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