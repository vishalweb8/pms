<div class="text-lg-center mt-4 mt-lg-0">
    <div class="row">
        <div class="col-4">
            <div>
                <p class="text-muted text-truncate mb-2">Total Leaves</p>
                <h5 class="mb-0">{{number_format(($leaves) ?  $leaves->total_leave : Config::get('constant.total_leave'),1)}}</h5>
            </div>
        </div>
        <div class="col-4">
            <div>
                <p class="text-muted text-truncate mb-2">Used</p>
                <h5 class="mb-0">{{  number_format(Helper::usedLeaves($user_id)->total_duration ?
                    Helper::usedLeaves($user_id)->total_duration : '0',1) }}</h5>
            </div>
        </div>
        <div class="col-4">
            <div>
                <p class="text-muted text-truncate mb-2">Remaining</p>
                <h5 class="mb-0">{{ number_format(number_format(($leaves) ?  $leaves->total_leave : Config::get('constant.total_leave'),1) - number_format(Helper::usedLeaves($user_id)->total_duration ?
                    Helper::usedLeaves($user_id)->total_duration : '0',1),1) }}</h5>
            </div>
        </div>
    </div>
</div>
