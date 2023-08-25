@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('organizationChart') }}
@endsection
@section('content')
<link href="{{ asset('css/jquery.orgchart.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex">
            <div class="d-sm-flex col-2 ">
                @include('filters.team_filter')
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-body bg-body">
                <div id="chart-container"></div>
            </div>
        </div>
    </div>

</div>

@endsection
@push('scripts')
<script src="{{ asset('js/jquery.orgchart.js') }}" defer></script>
<script>
    $(function() {
        const orgChartUrl = "{{route('organizationChart.show')}}";
        var orgChart = $('#chart-container').orgchart({
            'data': orgChartUrl,
            'nodeContent': 'title',
            'verticalLevel': 4

        });

        $('#team').on('change', function (argument) {
            var orgChartUrlWithTeam = orgChartUrl +'?team='+$(this).val();
            orgChart.init({ 'data': orgChartUrlWithTeam});
        });
    });
</script>
@endpush
