@if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="{{ $link ?? '#' }}" class="btn dropdown-item" title="Permission"><i class="mdi mdi-lock font-size-16 text-info me-1"></i> Permission</a>
@endif
