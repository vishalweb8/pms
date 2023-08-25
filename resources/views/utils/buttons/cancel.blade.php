@if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="{{ $link ?? '#' }}" data-method="POST" class="btn dropdown-item cancel"><i
        class="mdi mdi-close font-size-16 text-danger me-1"></i> Cancel</a>
@endif
