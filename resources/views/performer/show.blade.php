@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('performer') }}
@endsection
@section('content')
    <div class="row per-row">
        <div class="col-xl-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Top 5 Performers</h4>
            </div>
        </div>
        @include('performer.partials.details',['performers'=>$tops])
    </div>
    <div class="row per-row mt-4">
        <hr class="col-xl-12 mt-2 mb-4 "></hr>
        <div class="col-xl-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between  hr ">
                <h4 class="mb-sm-0 font-size-18">Worst 5 Performers</h4>
            </div>
        </div>
        @include('performer.partials.details',['performers'=>$wrosts])
    </div>
@endsection