@extends($theme)
@section('breadcrumbs')
{{ isset($addTeamLeave) ? (url()->previous() == route('leave-all-employee') ? Breadcrumbs::render('all-leave-create') : Breadcrumbs::render('team-leave-create')) : Breadcrumbs::render('my-leave-create') }}
@endsection
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Leave</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'leave-save', 'id'=> "leaveForm" ,"onsubmit" => "enableAddSelect()"]) !!}
                <input type="hidden" name="allempflag" value="{{url()->previous() != route('leave-all-employee') ? 0:1}}">
                @csrf
                @include('leaves.partials.form')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    var getRequestFrom = "{{ route('get-request-from') }}";
</script>
@endpush
