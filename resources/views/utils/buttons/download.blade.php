@if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="{{ $link ?? '#' }}" class="btn dropdown-item edit edit-record"><i class="mdi mdi-download font-size-16 text-info me-1"></i> Download</a>
@endif
