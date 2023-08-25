<div class="modal-header">
    <h5 class="modal-title" id="holidayModal">Holiday List of {{ now()->year }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        @if(sizeof($holidays))
        @foreach($holidays as $list)
        <div class="col-md-6">
            <div class="mini-stats-wid card mb-2 {{ (isset($list->sort_order) && $list->sort_order == '0') ? 'bg-light' : '' }}">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">{{ \Carbon\Carbon::parse($list->date)->format('d-m-Y')
                                }}</p>
                            <h5 class="mb-0"> {{ $list->name }}</h5>
                        </div>
                        <img src="images/holiday-dash.png" class="w-25" alt="img" />
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-md-12">
            <div class="comment-not-found d-flex justify-content-center align-items-center h-100 flex-column py-5">
                <img src="/images/no-record-found.svg" class="w-25 d-flex " alt="NotFound" />
                <h6 class="text-muted mt-2">No record available</h6>
            </div>
        </div>
        @endif
    </div>
</div>
