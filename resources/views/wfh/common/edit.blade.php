@extends($theme)

@section('breadcrumbs')
{{ isset($teamWfh) ? (url()->previous() == route('wfh-all-emp-index') ? Breadcrumbs::render('allemp-wfh-request-edit') : Breadcrumbs::render('team-leave-create')) : Breadcrumbs::render('my-wfh-request-edit') }}
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="m-0 card-title mb-3">Edit Work From Home</h5>
                {!! Form::model($workFromHome,[ 'route' => 'wfh-save']) !!}
                <input type="hidden" name="allempflagWFH" value="{{url()->previous() != route('wfh-all-emp-index') ? 0:1}}">
                @csrf
                @include('wfh.partials.form')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
