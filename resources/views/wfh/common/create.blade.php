@extends($theme)
@section('breadcrumbs')
{{ isset($teamWfh) ? (url()->previous() == route('wfh-all-emp-index') ? Breadcrumbs::render('allemp-wfh-request-create') : Breadcrumbs::render('team-leave-create')) : Breadcrumbs::render('my-wfh-request-create') }}

@endsection
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add {!! Str::title($module_title ?? '') !!}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'wfh-save', 'id'=> "wfhForm"]) !!}
                <input type="hidden" name="allempflagWFH" value="{{url()->previous() != route('wfh-all-emp-index') ? 0:1}}">
                    @csrf
                    @include('wfh.partials.form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
