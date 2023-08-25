@if ($class && $title)
    @php
        $badge_class = "badge-light-";
        if($class) {
            $badge_class .= $class;
        }else {
            $badge_class .= "success";
        }
    @endphp
    <x-common.status-label :class="$badge_class" :title="$title"></x-common.status-label>
@endif
