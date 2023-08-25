@extends($theme)
@section('breadcrumbs')
    {{ Breadcrumbs::render('event') }}
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-end">
            <h4 class="mb-sm-0 font-size-18 ms-0 me-auto">{{ Str::title($module_title) }}</h4>
            @if(Helper::hasAnyPermission(['event.create']))
                <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#Addevent">Add Event</button>
            @endif
        </div>
        <div class="page-title-box d-sm-flex align-items-center justify-content-end event-filter">
            <div class="search-box chat-search-box me-sm-2 mb-2">
                <div class="position-relative">
                    <input type="text" class="form-control" value="{{ request('search') }}" onkeyup="getData()" id="search" name="search" placeholder="Search...">
                    <i class="bx bx-search-alt search-icon text-muted"></i>
                </div>
            </div>
            <div class="page-title-right mb-2 d-flex">
                <div class="position-relative" id="datepicker1">
                    <input type="text" id="start" placeholder="From Date" name="start" class="form-control"
                    value="{{ request('start') }}"
                    change="getData()"
                    data-date-format="dd-mm-yyyy"
                    data-date-container="#datepicker1"
                    data-provide="datepicker"
                    data-date-autoclose="true"
                    autocomplete="off">
                </div>
                <div class="position-relative" id="datepicker1">
                    <input type="text" id="end" placeholder="To Date" name="end" class="form-control"
                    value="{{ request('end') }}"
                    change="getData()"
                    data-date-format="dd-mm-yyyy"
                    data-date-container="#datepicker1"
                    data-provide="datepicker"
                    data-date-autoclose="true"
                    autocomplete="off">
                </div>
            </div>
            <select class="form-select mb-2 select2 no-search" name="eventstatus" change="getData()" id="eventStatus">
                <option value="select">Select Event status</option>
                <option value="all" @if(request()->status == 'all') selected @endif>All</option>
                <option value="upcoming" @if(request()->status == 'upcoming') selected @endif> Upcoming</option>
                <option value="completed" @if(request()->status == 'completed') selected @endif>Completed</option>
            </select>
            <button type="reset" name="reset" id="reset" onClick="resetData()" value="0" class="btn btn-secondary btn-md ms-2 mb-2">Reset</button>
        </div>
        @if(count($events) > 0)
            <div class="row event-card" id="mainCard">
                @foreach($events as $list)
                <div class="col-xxl-4 col-md-6 col-12 mb-3" id="appendData">
                    <div class="card bg-white bg-soft h-100">
                        <div class="row h-100">
                            <div class="col-4 h-100">
                                <div class="text-white py-1 text-center h-100 bg-primary card mb-0 d-flex flex-column justify-content-center w-100 align-items-center">
                                    <div>
                                        <h2 class="text-white fw-bolder">{{ date('d', strtotime(str_replace('/','-', $list->event_date))) }}</h2>
                                        <p class="mb-2">{{ date('M-Y', strtotime(str_replace('/','-', $list->event_date))) }}</p>
                                        <p  class="mb-0">{{ date('h:i A', strtotime(str_replace('/','-', $list->start_time))) }} to {{ date('h:i A', strtotime(str_replace('/','-', $list->end_time))) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 ps-0">
                                <div class="text-primary p-3 h-100 d-flex flex-column justify-content-between ps-0">
                                    <div>
                                        <div class="d-flex justify-content-between w-100 align-items-start">
                                            <h6 class="card-title text-primary me-1 text-truncate">{{ $list->event_name }}</h6>
                                            <?php $dt = Carbon\Carbon::now()->format('d-m-Y'); ?>
                                            @if(Carbon\Carbon::parse($list->event_date)->gte(Carbon\Carbon::now()))
                                                <h6 class="badge badge-light-warning font-size-11">Upcoming</h6>
                                            @else
                                                <h6 class="badge badge-light-success font-size-11">Completed</h6>
                                            @endif
                                        </div>
                                        <div>
                                            <?php $description = '<a href="javascript: void(0);" class="d-block" data-id="'.$list->id.'" onclick="setDescription(this);" data-bs-toggle="modal"  data-bs-target="#EventDescription" title="EventDescription"> Read More...</a>'; ?>
                                            <p class="text-muted mb-0">{!! Illuminate\Support\Str::limit($list->description, 30, $description) !!}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-end">
                                        @if(!empty($list) && !empty($list->file_url) && file_exists(public_path('storage/upload/event/'.$list->file_url)))
                                            <a href="{{ asset('storage/upload/event/'.$list->file_url) }}" class="btn font-size-13 text-primary px-2 py-1 waves-effect waves-light" target="_blank" title="View Event">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        @endif
                                        @if(Helper::hasAnyPermission(['event.edit']))
                                            <button type="button" class="btn font-size-13 text-success px-2 py-1 mx-2 waves-effect waves-light" data-id="{{ $list->id }}"  onClick="setAttrValue(this);" data-bs-toggle="modal" data-bs-target="#Editevent" title="Edit Event"><i class="fas fa-pencil-alt"></i></button>
                                        @endif
                                        @if(Helper::hasAnyPermission(['event.destroy']))
                                            <button type="button" class="btn font-size-13 text-danger px-2 py-1 waves-effect waves-light" onclick="deleteConfirm({{$list->id}})"><i class="fa fa-trash" aria-hidden="true" title="Delete Event"></i></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="pagination">
                        {!! $events->appends(request()->input())->links(); !!}
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12"><h5 class="text-center">No Events available </h5></div>
            </div>
        @endif
    </div>
</div>
@include('master.event.create')
@include('master.event.edit')
@include('master.event.partials.description-modal')
@endsection
@push('scripts')
<script src="{{ asset('/js/data-tables.js') }}"></script>
@include('master.event.partials.script')
@endpush
