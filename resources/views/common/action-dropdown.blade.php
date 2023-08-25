
@if(!empty($options) && Helper::hasAnyPermission($options->whereNotNull('permission')->pluck('permission')->toArray()))
<div class="dropdown {{ $dropdown_class ?? '' }}">
    <a href="#" class=" card-drop" data-bs-toggle="dropdown" aria-expanded="false"> <i class="mdi mdi-dots-vertical font-size-18"></i> </a>
    <ul class="dropdown-menu dropdown-menu-end">
        @foreach ($options as $item)
            <li>
                @include('utils.buttons.'.$item['btn_file_name'], $item)
            </li>
        @endforeach
    </ul>
</div>
@endif
