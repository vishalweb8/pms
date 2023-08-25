@if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="javascript:void(0)" data-method="POST" class="btn dropdown-item delete approve_leave" data-bs-toggle="modal" data-bs-target="#approve_modal" data-id="{{ $data_id }}"><i
        class="mdi mdi-check font-size-16 text-success me-1"></i> Approve</a>
@endif
