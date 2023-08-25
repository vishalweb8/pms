@if(empty($permission) || (!empty($permission) && Helper::hasAnyPermission($permission)))
<a href="{{ $link ?? '#' }}" data-method="DELETE"
@if (!empty($delete_message))
    data-msg="{{ $delete_message }}"
@elseif (!empty($title))
    data-delete_title="{{ $title }}"
@endif
 class="btn dropdown-item delete"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a>
@endif
