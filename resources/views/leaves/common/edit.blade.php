@extends($theme)
@section('breadcrumbs')
{{ isset($addTeamLeave) ? (url()->previous() == route('leave-all-employee') ? Breadcrumbs::render('all-leave-edit') : Breadcrumbs::render('my-leave-edit')) : Breadcrumbs::render('my-leave-edit') }}
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="m-0 card-title mb-3">Edit Leave</h5>
                {!! Form::model($leave,[ 'route' => 'leave-save', 'id' => "leaveEdit","onsubmit" => "enableEditSelect()"]) !!}
                <input type="hidden" name="allempflag" value="{{url()->previous() != route('leave-all-employee') ? 0:1}}">
                @csrf
                @include('leaves.partials.form')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
