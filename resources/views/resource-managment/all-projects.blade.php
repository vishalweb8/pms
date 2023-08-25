@extends($theme)
@section('breadcrumbs')
    {{ Breadcrumbs::render('all-projects') }}
@endsection
@section('after-style')
    <style>
        td.billable-resource {
            min-width: 10%;
            max-width: 40%;
        }
        .emp-list-section table tr .avtar-td {
            min-width: 180px;
            width: auto;
        }
        .emp-list-section table tr .billable-resource .avtar-td .all-emp-name-list {
            min-width: 200px;
        }
    </style>
@endsection
@section('content')

@include('resource-managment.resource-mgt-count')
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">All Projects</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card emp-list-section ">
            <div class="card-body">
                <div class="table-responsive">
                    <input type="hidden" value="1" id="findDataFlag">
                    <input type="hidden" value="totalProjectsPage" id="checkPage">
                    <table class="table table-centered  mb-0" id="projectFilterDiv">
                        <thead>
                            <tr>
                                <th class="p-1 bg-white select-project-team">
                                    <input type="text" placeholder="Search Project..." id="projectName" class="form-control border-0" />
                                </th>
                                <th>Billable Resources</th>
                                <th>Non-Billable Resources
                                    @php
                                        $options = [
                                            ['class' => 'all-emp-name-list', 'title' => "Normal User"],
                                            ['class' => 'bg-danger', 'title' => "No Member"],
                                        ];
                                    @endphp
                                    @include('common.color-indication', ['alignment_class' => 'float-end', 'options' => $options])
                                </th>
                            </tr>
                        </thead>
                        <tbody id="allProject"></tbody>
                    </table>
                </div>
                <!-- Data Loader -->
                <div class="auto-load text-center">
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                        y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#000"
                            d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50"
                                to="360 50 50" repeatCount="indefinite" />
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@include('common.known-technology-modal')
@endsection
@push('scripts')
<script src="{{ asset('/js/modules/dashboard.js') }}"></script>
<script type="text/javascript">
    var ENDPOINT = "{{ url('/') }}";
    var page = 1;
    var siteUrl = ENDPOINT + "/admin-projects?page=" + page;
    listAjaxCall(siteUrl, "allProject");
</script>
@endpush
