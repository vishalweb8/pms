@include('common.head_portion')

<!---Old Design--here----->
<div class="d-none container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!---Old Design-Finish-here----->


<!---New Design--here----->

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
                                        <!-- <div dir="ltr">
                                            <div class="owl-carousel owl-theme auth-review-carousel" id="auth-review-carousel">
                                                <div class="item">
                                                    <img class="d-block w-75 m-auto" src="{{ asset('/images/login/Project-Management.svg') }}" alt="slider-1" />
                                                    <div class="py-3">
                                                        <h3 class="font-size-20">Manage your project and task</h3>
                                                        <p class="font-size-14 mb-4">
                                                            Lorem Ipsum is simply dummy text of the printing and typesetting
                                                            industry.
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="item">
                                                    <img class="d-block w-75 m-auto" src="{{ asset('/images/login/Leave.svg') }}" alt="slider-1" />
                                                    <div class="py-3">
                                                        <h3 class="font-size-20">Manage your leave</h3>
                                                        <p class="font-size-14 mb-4">
                                                            Lorem Ipsum is simply dummy text of the printing and typesetting
                                                            industry.
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="item">
                                                    <img class="d-block w-75 m-auto" src="{{ asset('/images/login/Time-log.svg') }}" alt="slider-1" />
                                                    <div class="py-3">
                                                        <h3 class="font-size-20">Manage your time entry</h3>
                                                        <p class="font-size-14 mb-4">
                                                            Lorem Ipsum is simply dummy text of the printing and typesetting
                                                            industry.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->

        <div class="col-md-6 col-lg-4">
            <div class="auth-full-page-content h-100vh p-md-5 p-4">
                <div class="w-100">
                    <div class="d-flex flex-column h-100">
                        <div class="my-auto">
                            <div class="mb-4 mb-md-5">
                                <a href="#" class="d-block text-center auth-logo">
                                    <img src="{{ asset('/images/inexture-logo-dark.svg') }}" alt="" class="w-50">
                                </a>
                                <h5 class="text-muted text-center mt-3">Reset Your Password</h5>
                            </div>

                            <div class="mt-4">
                                {{-- <div class="alert alert-success text-center mb-4" role="alert">
                                    Enter your Email and instructions will be sent to you!
                                </div> --}}

                                @if(isset($errors) && !empty($errors->first()))
                                <div class="alert alert-danger" role="alert">
                                    {{$errors->first()}}
                                </div>

                                @endif

                                <form class="form-horizontal" method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="form-group">
                                        <label class="col-form-label" for="floatingnameInput">{{ __('Email') }}</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingemailInput email" placeholder="Enter Email address" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-form-label">{{ __('Password') }}</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                            <button class="btn btn-light" type="button" id="password-addon">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password-confirm" class="col-form-label">{{ __('Confirm Password') }}</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                                            <button class="btn btn-light" type="button" id="confirm-password-addon">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                                            {{ __('Submit') }}</button>
                                    </div>
                                </form>


                                <div class="mt-5 text-center">
                                    <p>Back to login? <a href="{{ route('login') }}" class="fw-medium text-primary"> Login </a> </p>
                                </div>
                            </div>
                        </div>


                    </div>


                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- end container-fluid -->

@include('common.script_portion')
