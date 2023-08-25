@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('setting') }}
@endsection
<style>
.dropdown-item:hover {
    background: transparent !important;
}
.dropdown-item:focus {
    background: transparent !important;
}
.action-item {
    display: inline-flex !important;
}
a {
    padding-right:0px !important;
}
</style>
@section('content')
<div class="row">
    <div class="col-lg-12">
        {{-- @include('common.index-title-with-button', ['title' => Str::title(Str::plural($module_title)), 'add_url' =>
        Helper::hasAnyPermission(['policy.create']) ? route('setting.create') : '' ]) --}}
        <div class="card">
            <div class="card-body bg-body">
                <table id="indexDataTable setting-table" class="project-list-table table nowrap table-borderless w-100 align-middle custom-table">
                    <thead>
                        <tr>
                            <th>Label</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @foreach ($settings as $key => $item)
                        <tbody>
                            <tr>
                                <td>{{$item->field_label}}</td>
                                <td>{{$item->value}}</td>
                                <td class="action-item">
                                    <a href={{ route('setting.edit',$item->id) }} class="btn dropdown-item edit edit-record"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i></a>
                                    {{-- <a href={{ route('setting.delete',$item->id) }} data-method="DELETE" class="btn dropdown-item delete"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i></a></td> --}}
                            </tr>
                        </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@include('common.modal')
@endsection
@push('scripts')
<script>
    datatalbe=undefined;
</script>
{{-- <script>
    jQuery(function() {
            datatalbe = jQuery('#indexDataTable').DataTable({
                "oLanguage": {
                    "sEmptyTable": "No Records Found"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '{{ route("policy.index") }}',
                    'type': "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'table-id'},
                    {data: 'id', name: 'id',visible:false},
                    {data: 'title', name: 'title'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'table-action'}
                ],
                "order": [[ 1, "desc" ]] // Order on init. # is the column, starting at 0
            });
            datatalbe.columns(datatalbe.columns().count()-1).visible(false);
            @if(Helper::hasAnyPermission(['policy.edit','policy.destroy']))
                datatalbe.columns(datatalbe.columns().count()-1).visible(true);
            @endif
        });
</script> --}}
@endpush
