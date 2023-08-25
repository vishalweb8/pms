@if($event->total() >= 9)
<div id="pagination">
    {!! $event->appends(request()->input())->links(); !!}
</div>
@elseif($event->total() == 0)
<div class="row">
    <div class="col-md-12">
        <div class="no-event-placeholder">
            <img src="/images/no-event.png" alt="img"/>
            <h6 class="text-center my-3 text-muted">No Events available </h6>
        </div>

    </div>
</div>
@else
<ul class="pagination">
    <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
        <span class="page-link" aria-hidden="true">‹</span>
    </li>
    <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
    <li class="page-item">
        <a class="page-link" href="#" rel="next" aria-label="Next »">›</a>
    </li>
</ul>
@endif
