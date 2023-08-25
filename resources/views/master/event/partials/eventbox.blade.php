<div class="col-xxl-4 col-md-6 col-12 mb-3" id="appendData">
    <div class="card bg-white bg-soft h-100">
        <div class="row h-100">
            <div class="col-4 h-100">
                <div
                    class="text-white py-1 text-center h-100 bg-primary card mb-0 d-flex flex-column justify-content-center w-100 align-items-center">
                    <div>
                        <h2 class="text-white fw-bolder">{{ date('d', strtotime($list->event_date)) }}</h2>
                        <p class="mb-2">{{ date('M-Y', strtotime(str_replace('/','-', $list->event_date))) }}</p>
                        <p class="mb-0">{{ date('h:i A', strtotime(str_replace('/','-', $list->start_time))) }} to
                            {{ date('h:i A', strtotime(str_replace('/','-', $list->end_time))) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-8 ps-0">
                <div class="text-primary p-3 h-100 d-flex flex-column justify-content-between ps-0">
                    <div>
                        <div class="d-flex justify-content-between w-100 align-items-start">
                            <h6 class="card-title text-primary me-1 text-truncate">{{ $list->event_name }}</h6>
                            @if(Carbon\Carbon::parse($list->event_date)->gte(Carbon\Carbon::now()))
                            <h6 class="badge badge-light-warning font-size-11">Upcoming</h6>
                            @else
                            <h6 class="badge badge-light-success font-size-11">Completed</h6>
                            @endif
                        </div>
                        <div>
                            <?php $description = '<a href="javascript: void(0);" data-id="' . $list->id . '" onclick="setDescription(this);" data-bs-toggle="modal"  data-bs-target="#EventDescription" title="EventDescription"> Read More...</a>'; ?>
                            <p class="text-muted mb-0">{!! Illuminate\Support\Str::limit($list->description, 30,
                                $description) !!}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-end">
                        @if(!empty($list) && !empty($list->file_url) &&
                        file_exists(public_path('storage/upload/event/'.$list->file_url)))
                        <a href="{{ asset('storage/upload/event/'.$list->file_url) }}"
                            class="btn font-size-13 text-primary px-2 py-1 waves-effect waves-light" target="_blank"
                            title="View Event">
                            <i class="far fa-eye"></i>
                        </a>
                        @endif
                        @if(Helper::hasAnyPermission(['event.edit']))
                        <button type="button"
                            class="btn font-size-13 text-success px-2 py-1 mx-2 waves-effect waves-light"
                            data-id="{{ $list->id }}" onClick="setAttrValue(this);" data-bs-toggle="modal"
                            data-bs-target="#Editevent" title="Edit Event"><i class="fas fa-pencil-alt"></i></button>
                        @endif
                        @if(Helper::hasAnyPermission(['event.destroy']))
                        <button type="button" class="btn font-size-13 text-danger px-2 py-1 waves-effect waves-light"
                            onclick="deleteConfirm({{$list->id}})"><i class="fa fa-trash" aria-hidden="true"
                                title="Delete Event"></i></button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>