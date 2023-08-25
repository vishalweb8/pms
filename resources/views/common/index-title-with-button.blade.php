<div class="page-title-box d-flex align-items-center justify-content-between">
    <h4 class="m-0 page-title">{!! $title ?? '' !!}</h4>
    @if(!empty($add_url))
        <button type="button" class="btn btn-primary waves-effect waves-light add-btn"
            data-url="{{ $add_url ?? '#' }}">Add</button>
    @endif
</div>
