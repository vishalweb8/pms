@php
use Carbon\Carbon;
@endphp
<div class="sod-eod-show-outer">
    @if(($dailyTaskForUser && $isLeave === "false") || ($isLeave === "true" &&  $dailyTaskForUser))
    <!-- entry show view -->
    {{-- @if($dailyTaskForUser->verified_by_TL == 0) --}}
    <div class="d-flex justify-content-end">
        <a class="p-0 d-flex align-items-center btn waves-effect waves-light text-primary"
            href={{route('work-edit',$dailyTaskForUser->id) }}>Edit <i
                class="mdi mdi-pencil-box-outline font-size-24 ms-2"></i></a>
    </div>
    {{-- @endif --}}
    @if($dailyTaskForUser && $isLeave === "false")
    <div class="sod-eod-list">
        <h6 class="bg-light-primary p-2 text-primary fw-bold mt-2 position-sticky top-0">SOD Details</h6>
        <div class="px-2">
            {!! $dailyTaskForUser->sod_description !!}
        </div>

        
    </div>
    @else
    <img src="/images/leave-approve.png" class="w-25" alt="image" />
        @if(Carbon::now()->format('Y-m-d') == $date)
        <h6 class="mt-3">You are on Leave today, but you worked.</h6>
        @else
        <h6 class="mt-3">You are on Leave this day, but you worked.</h6>
        @endif
        @if($dailyTaskForUser->sod_description != null)
        <div class="sod-eod-list">
            @if($dailyTaskForUser->sod_description != null)
            <h6 class="bg-light-primary p-2 text-primary fw-bold mt-2 position-sticky top-0">SOD Details</h6>
            <div class="px-2">
                {!! $dailyTaskForUser->sod_description !!}
            </div>
            @endif
            
        </div>
        @endif
    @endif
    @else
    @if($isLeave === "true")
    <!-- on-leave-view -->
    <div class="empty-entry-outer d-flex flex-column align-items-center justify-content-center">
        @if((!isset($dailyTaskForUser)) || ($dailyTaskForUser->sod_description == null))
        <img src="/images/leave-approve.png" class="w-25" alt="image" />
        <h6 class="mt-3">You are on Leave this day, Add your SOD entry here if you worked this day.</h6>
        @else
        <img src="/images/leave-approve.png" class="w-25" alt="image" />
        @if(Carbon::now()->format('Y-m-d') == $date)
        <h6 class="mt-3">You are on Leave today, but you worked.</h6>
        @else
        <h6 class="mt-3">You are on Leave this day, but you worked.</h6>
        @endif
        @if($dailyTaskForUser->sod_description != null)
        <div class="sod-eod-list">
            @if($dailyTaskForUser->sod_description != null)
            <h6 class="bg-light-primary p-2 text-primary fw-bold mt-2 position-sticky top-0">SOD Details</h6>
            <div class="px-2">
                {!! $dailyTaskForUser->sod_description !!}
            </div>
            @endif
            
        </div>
        @endif
        @endif
        @if(Helper::hasAnyPermission(['daily-tasks.create']) && !$dailyTaskForUser)
            @if(Carbon::now()->format('Y-m-d') != $date)
            <a class="mt-5 btn btn-primary waves-effect waves-light" href="{{ route('work-create',$date) }}">Add SOD</a>
            @else
            <a class="mt-5 btn btn-primary waves-effect waves-light" href="{{route('work-create')}}">Add SOD</a>
            @endif
        @else
        {{-- <div class="sod-eod-list">
            <h6 class="bg-light-primary p-2 text-primary fw-bold mt-2 position-sticky top-0">SOD Details</h6>
            <div class="px-2">
                {!! $dailyTaskForUser->sod_description !!}
            </div>
            
        </div> --}}
        @endif
    </div>
    @else
    <!-- no-entry-view -->
    <div class="empty-entry-outer d-flex flex-column align-items-center justify-content-center ">
        <img src="/images/no-entry.png" class="w-25" alt="image" />
        @if($isWeekend === "true")
            <h6 class="mt-2" id="AddTaskEntry">It is a offday, Add your SOD entry if you worked this day.</h6>
        @else
            <h6 class="mt-2" id="AddTaskEntry">Please add your SOD entry</h6>
        @endif
        @if(Helper::hasAnyPermission(['daily-tasks.create']) && !$dailyTaskForUser)
        @if(Carbon::now()->format('Y-m-d') != $date)
        <a class="mt-5 btn btn-primary waves-effect waves-light" href="{{ route('work-create',$date) }}">Add SOD</a>
        @else
        <a class="mt-5 btn btn-primary waves-effect waves-light" href="{{route('work-create')}}">Add SOD</a>
        @endif
        @endif
    </div>
    @endif
    @endif
</div>
