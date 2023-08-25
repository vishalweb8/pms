<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    public function login(Request $request)
    {

        // Mail::to($request->email)->send(new ForgotPassword);
        $response = $this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
        if(Auth::attempt([ $fieldType => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            // update the last login at time with the user table
            Auth::user()->update(['last_login_at' => now()]);

            /* 21/12/2021 :: old method */
                // return redirect()->intended('/home');

            /* 21/12/2021 :: new change because resolved issue of recivied 404 page after successfully logged in */
                return redirect()->route('user-dashboard');
        }  else {
            $this->incrementLoginAttempts($request);
            return redirect()->route('login')->withErrors('Invalid Credentials.');
            // return response()->json([
            //     'error' => 'This account is not activated.'
            // ], 401);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

}
