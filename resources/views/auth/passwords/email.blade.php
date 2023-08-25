@include('common.head_portion')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-6 col-lg-8">
            <div class="d-none d-sm-none d-md-block auth-full-bg h-100vh">
                <div class="h-100">
                    <div class="bg-overlay"></div>
                    <div class="d-flex h-100 flex-column justify-content-center">

                        <div class="">
                            <div class="row justify-content-center">
                                <div class="col-lg-9">
                                    <div class="text-center">
                                    <img class="d-block w-75 m-auto" src="{{ asset('/images/login/Project-Management.svg') }}" alt="slider-1" />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="auth-full-page-content h-100vh p-md-5 p-4">
                <div class="w-100">
                    <div class="d-flex flex-column h-100">
                        <div class="my-auto">
                            <div class="mb-4 mb-md-5">
                                <a href="{{ route('login') }}" class="d-block text-center auth-logo">
                                    <img src="{{ asset('/images/inexture-logo-dark.svg') }}" alt="" class="w-50">
                                </a>
                                <h5 class="text-muted text-center mt-3">{{ __('Reset Password') }}</h5>
                            </div>

                            <div class="mt-4">

                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                        <div class="col-md-12">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-3 d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end container-fluid -->
@include('common.script_portion')
