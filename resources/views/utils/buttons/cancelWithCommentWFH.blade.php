@if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="javascript:void(0)" class="btn dropdown-item cancel_wfh"  data-action="cancel_wfh" data-id="{{ $link }}"><i
        class="mdi mdi-close font-size-16 text-danger me-1"></i> Cancel</a>
@endif