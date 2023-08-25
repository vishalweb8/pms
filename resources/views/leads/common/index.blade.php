@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('my-leads') }}
@endsection
@section('content')
<div class="row mb-3">
    @foreach($leadOverview as $key => $list)
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body cardFilter">
                <div class="d-flex">
                    <div class="flex-grow-1" data-lead="{{ $list->name }} Leads">
                        <p class="text-muted fw-medium">{{ $list->name }} Leads</p>
                        <h4 class="mb-0">{{ $list->lead_count }}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-purchase-tag-alt font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between all-employee-leave-index">
            <h4 class="mb-sm-0 font-size-18">{{ Str::title($module_title) }}</h4>
            <div class="d-sm-flex  align-items-center filter-with-search">

                <div class="d-sm-flex">
                    {!! Form::select('date_fitler', $dateFilter, null, [ 'class' => 'form-control select2 no-search', 'id' => 'date_fitler']) !!}
                </div>

                <div class="d-sm-flex">
                    {!! Form::select('lead_status_filter', $leadStatusFilter, null, [ 'class' => 'form-control select2 no-search', 'id' => 'lead_status_filter']) !!}
                </div>
                <a href="{{ route('lead.add') }}"
                    class="text-truncate ms-3 btn btn-primary waves-effect waves-light" type="button">Add Lead</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body bg-body">
                <div class=" ">
                    <table id="indexDataTable"
                        class="project-list-table table nowrap table-borderless w-100 align-middle custom-table dataTable no-footer dtr-inline">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <th>#</th>
                                <th>Lead Title</th>
                                <th>Full Name</th>
                                <th>Source</th>
                                <th>Lead Owner</th>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection
@push('scripts')
<script src="{{ asset('/js/data-tables.js') }}"></script>
<script type="text/javascript">
    var SometingWentWrong = "{{ __('messages.ERR_SOMETING_WENT_WRONG') }}";
</script>
<script>
    jQuery(function() {
    datatalbe = jQuery('#indexDataTable').DataTable({
        "oLanguage": {
            "sEmptyTable": "No Records Found"
        },
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            'url': '{{ route("lead.list") }}',
            'type': "GET",
            data: function(e) {
                e.fyear = $('#date_fitler').val();
                e.lead_status_id = $('#lead_status_filter').val();
                e.lead_owner_id = "{{Auth::user()->id}}";
            }
        },
        columns: [
            {
                data: 'created_at',
                name: 'created_at',
                searchable: false,
                visible:false
            },
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'table-id'
            },
            {
                data: 'lead_title',
                name: 'lead_title',
                orderable: true,
                searchable: false,
            },
            {
                data: 'full_name',
                name: 'first_name',
                orderable: true,
                searchable: false,
            },
            {
                data: 'lead_source_id',
                name: 'lead_source_id',
                searchable: false,
                orderable: true,
            },
            {
                data: 'user.first_name',
                name: 'user.first_name',
                orderable: false,
                searchable: false,
            },
            {
                data: 'created_at',
                name: 'created_at',
                orderable: false,
                searchable: false,
            },
            {
                data: 'lead_status_id',
                name: 'lead_status_id',
                orderable: true,
                searchable: false,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'table-action'
            }
        ],
        "order": [
            // [0, "desc"],
            // [1, "desc"]
        ] // Order on init. # is the column, starting at 0
    });
    datatalbe.columns(datatalbe.columns().count() - 1).visible(false);
    @if(Helper::hasAnyPermission(['lead.edit', 'lead.destroy', 'lead.view']))
    datatalbe.columns(datatalbe.columns().count() - 1).visible(true);
    @endif
});

$('#date_fitler,  #lead_status_filter').change(function() {
    $('#indexDataTable').DataTable().draw(true);
});
</script>
@endpush
