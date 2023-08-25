@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('my-leave-compensation-edit') }}
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="m-0 card-title mb-3">Edit Leave Compensation</h5>
                {!! Form::model($leave,[ 'route' => 'leave-compensation-save', 'id' => "leaveCompensationFormEdit","onsubmit" => "enableEditSelect()"]) !!}
                @csrf
                @include('leaves.partials.compensation-form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
