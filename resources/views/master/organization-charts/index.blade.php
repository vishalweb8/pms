@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('organizationChart') }}
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.index-title-with-button', ['title' => Str::title(Str::plural($module_title)), 'add_url' =>
        Helper::hasAnyPermission(['organization-chart.create']) ? route('organizationChart.create') : '' ])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body bg-body">
                        <div class=" ">
                            <table id="indexDataTable"
                                class="project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Top Level</th>
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
            </div>
        </div>
    </div>
</div>
@include('common.modal')
@endsection

@push('scripts')
<script src="{{ asset('/js/data-tables.js') }}"></script>
<script>
    const getUserDesigTeamUrl = "{{ route('getUserDesigTeam')}}";
    var SometingWentWrong = "{{ __('messages.ERR_SOMETING_WENT_WRONG') }}";
    $(function() {
        datatalbe = $('#indexDataTable').DataTable({
            "oLanguage": {
                "sEmptyTable": "No Records Found"
            },
            processing: true,
            serverSide: true,
            ajax: {
                'url': '{{ route("organizationChart.index") }}'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'table-id'},
                {data: 'id', name: 'id',visible:false},
                {data: 'user.full_name', name: 'user.first_name'},
                {data: 'is_top_level', name: 'is_top_level'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'table-action'}
            ],
            "order": [[ 1, "desc" ]] // Order on init. # is the column, starting at 0
        });
        datatalbe.columns(datatalbe.columns().count()-1).visible(false);
        @if(Helper::hasAnyPermission(['organization-chart.edit','organization-chart.destroy']))
            datatalbe.columns(datatalbe.columns().count()-1).visible(true);
        @endif

        $(document).on('change', '#user_id',function (e) {
            e.preventDefault();
            getUserDesigTeam($(this).val());
            $("#reporting_to option").prop('disabled',false);
            $("#reporting_to option[value='"+$(this).val()+"']").prop('disabled',true);
        });
        $("#dynamicModal").on("shown.bs.modal", function () {
            $('#user_id').trigger('change');            
        });
    });
</script>
@endpush
