@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('create-lead') }}
@endsection
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Lead</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'lead.save', 'id'=> "leadForm"]) !!}
                @csrf
                @include('leads.partials.form')

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
