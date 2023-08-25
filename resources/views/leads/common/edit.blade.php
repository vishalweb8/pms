@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('edit-lead') }}
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="m-0 card-title mb-3">{{ Str::title($module_title) }}</h5>
                {!! Form::open(['route' => 'lead.save', 'id'=> "leadForm"]) !!}
                @csrf
                @include('leads.partials.form')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
