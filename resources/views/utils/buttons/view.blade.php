@if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="{{ $link ?? '#' }}" data-method="GET" class="btn dropdown-item"><i
        class="mdi mdi-eye font-size-16 text-info me-1"></i> View</a>
@endif
