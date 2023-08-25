@if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="{{ $link ?? '#' }}" class="btn dropdown-item edit {{isset($disablePopupClass) ? '' : 'edit-record'}}"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a>
@endif
