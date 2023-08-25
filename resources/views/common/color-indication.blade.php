<div class="status-idicator {{ $alignment_class ?? '' }}">
    
    <ul class="list-unstyled p-0 d-sm-flex flex-wrap">
        @if(isset($experience) && $experience)
            <li><span class="d-flex cursor-pointer align-items-center" id="sortByExperience" data-sort="asc" title="Sort by experience"><i class="fa fa-sort fa-lg" aria-hidden="true"></i> &nbsp; Experience </span></span></li>
        @endif
        @if (!empty($options))
            @foreach ($options as $option)
                <li><span class="{{ $option['class'] ?? '' }}"></span>{{ $option['title'] ?? '' }}</li>
            @endforeach
        @endif
    </ul>
</div>
