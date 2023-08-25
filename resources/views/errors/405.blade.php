@extends('layouts.error')
@section('content')
    <section class="error">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="status-code">{{ $exception->getStatusCode() }}</h1>
                            <h4 class="message">{{ $exception->getMessage() }}</h4>
                            <div class="action">
                                <a href="{{ route('home') }}" class="btn btn-primary" >Home</a>
                                <a href="#!" class="btn btn-dark" id="goBack">Go Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')

@endpush
