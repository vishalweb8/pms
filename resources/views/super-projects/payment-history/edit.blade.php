@extends($theme)
@section('breadcrumbs')
{{ Breadcrumbs::render('edit-payment-history', $project->name, $project->id) }}
@endsection
@section('content')

<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="m-0 page-title">Edit Payment History</h4>
    <a href="{{ route('super-admin-project-activity',$project->id) }}" class="btn btn-secondary mx-2 w-md">Back</a>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card h-100">
            <div class="card-body">
                {!! Form::model($paymentHistory,['route' => ['paymentHistory.update',[$project->id,$paymentHistory->id]], 'id' => 'paymentHistoryForm','enctype' => 'multipart/form-data']) !!}
                @csrf
                @method('put')
                @include('super-projects.payment-history.partials.form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/modules/projects.js') }}"></script>
@endpush

@endsection
