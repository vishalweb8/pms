{{-- @extends('layouts.login') --}}
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
                                    <h5 class="text-muted text-center mt-3">Sign in to continue to Inexture.</h5>
                                </div>

                                <div class="mt-4">

                                    @if(isset($errors) && !empty($errors->first()))
                                    <div class="alert alert-danger" role="alert">
                                        {{$errors->first()}}
                                    </div>

                                    @endif

                                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="col-form-label" for="floatingnameInput">{{ __('Email Or Username') }}</label>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="floatingemailInput email" placeholder="Enter Email address or username" name="email" value="{{ old('email') }}" required  autofocus>

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                {{-- <strong>{{ $message }}</strong> --}}
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="floatingemailInput">{{ __('Password') }}</label>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="floatingnameInput password" placeholder="Enter Password"  name="password" required autocomplete="current-password">
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

                                        <div class="mb-3 d-flex justify-content-between">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                            @if (Route::has('password.request'))
                                            <a class="btn btn-link pe-0" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                            @endif
                                        </div>


                                        <div class="mt-3 d-grid">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit">
                                                {{ __('Login') }}</button>
                                        </div>
                                    </form>

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
