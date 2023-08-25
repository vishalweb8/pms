@if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="javascript:void(0)" data-method="POST" class="btn dropdown-item delete reject_leave" data-bs-toggle="modal"
    data-bs-target="#reject_modal" data-id="{{ $data_id }}"><i
        class="mdi mdi-cancel font-size-16 text-danger me-1"></i> Reject</a>
@endif
