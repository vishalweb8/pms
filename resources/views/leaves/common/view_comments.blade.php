<div class="modal-header">
    <h5 class="modal-title" id="designationModal">{!! Str::title('comments') !!} for leave: {!! $leave->start_date !!} to {{ $leave->end_date }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body" style="max-height: 400px; overflow-y:auto; ">
    <div class="containter">
        <div class="row">
            <div class="col-md-12">
                @include('leaves.partials.comments', ['without_action_btn', 'leaveComments' =>
                $leave->comments->sortByDesc('created_at'), 'leaveView' => $leave])
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    @if(!isset($close_btn) || $close_btn == true)
    <button type="button" class="btn btn-secondary waves-effect"
        data-bs-dismiss="modal">Close</button>
    @endif
    <?php
        $redirect_url= 'leave-team-view';
        if($leave->request_from == Auth::id()) {
            $redirect_url = 'leave-view';
        }
    ?>
    {!! link_to_route($redirect_url,'View Full Details',[$leave->id], ['class' => 'btn
    btn-primary']) !!}
</div>
