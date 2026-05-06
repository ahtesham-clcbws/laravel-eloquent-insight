<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerifications;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/administrator';

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
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // Custom OTP Validation for Admins
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user && in_array($user->roles, ['admin', 'superadmin', 'sub_admin', 'administrator']) && !empty($user->mobile)) {
            $mobileNumber = preg_replace('/[^0-9]/', '', $user->mobile);
            
            // Normalize to 10 digits if it has a country code (like 91...)
            if (strlen($mobileNumber) > 10) {
                $mobileNumber = substr($mobileNumber, -10);
            }

            if (strlen($mobileNumber) === 10) {
                $request->validate([
                    'otp' => 'required',
                ], [
                    'otp.required' => 'OTP is required for admin login.'
                ]);
 
                if (!verifyOtp($request->otp, $mobileNumber)) {
                    return back()->withInput()->withErrors(['otp' => 'Invalid OTP.']);
                }
            }
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
