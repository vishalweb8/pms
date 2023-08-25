@extends($theme)
@section('breadcrumbs')
@if (isset($task))
{{ Breadcrumbs::render('edit-payment-history', $project->name, $project->id) }}
@else
{{ Breadcrumbs::render('add-payment-history', $project->name, $project->id) }}
@endif
@endsection
@section('content')

<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="m-0 page-title">Add Payment History</h4>
    <a href="{{ route('super-admin-project-activity',$project->id) }}" class="btn btn-secondary mx-2 w-md">Back</a>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card h-100">
            <div class="card-body">
                @if($paymentHistory)
                {!! Form::model($paymentHistory,['route' => ['paymentHistory.store',[$project->id]], 'id' => 'paymentHistoryForm','enctype' => 'multipart/form-data']) !!}
                @else
                {!! Form::open(['route' => ['paymentHistory.store',$project->id], 'id' => 'paymentHistoryForm', 'class' =>
                'add-form','enctype' => 'multipart/form-data']) !!}
                @endif
                @csrf
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
