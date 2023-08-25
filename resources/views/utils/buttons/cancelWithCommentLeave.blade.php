{{-- @if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="javascript:void(0)"
                        class="btn btn-primary waves-effect waves-light cancel-btn approve_leave"
                        data-action="reject_leave" data-id="{{ $link }}"
                        onclick="commentPopup($(this))">Cancel</a>
                        @endif --}}


                        @if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="javascript:void(0)" class="btn dropdown-item cancel_leave"  data-action="cancel_leave" data-id="{{ $link }}"><i
        class="mdi mdi-close font-size-16 text-danger me-1"></i> Cancel</a>
@endif