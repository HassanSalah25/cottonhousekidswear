<?php

namespace App\Http\Controllers\Frontend;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Services\SmsServices;
use App\Models\Cart;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class CustomerLoginController extends Controller
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
    /*protected $redirectTo = '/';*/

    // Show the login form
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }


    // Show the registration form
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $phone = Str::replace(' ', '', $request->phone);
        if($request->email){
            $user = User::where('email', $request->email)->first();
        }
        elseif($request->phone){
            $user = User::where('phone', $phone)->first();
        }
        else{
            $user = null;
        }
        if(!$user || !Hash::check($request->password, $user->password)){
            flash(translate('Invalid login information'))->error();
            return back();
        }
        // banned user
        if ($user->banned) {

            auth()->logout();
            flash(translate('You are banned!'))->error();
            return back();
        }
        if ($user->user_type == 'customer') {

            if ($request->has('temp_user_id') && $request->temp_user_id != null) {
                Cart::where('temp_user_id', $request->temp_user_id)->update(
                    [
                        'user_id' => $user->id,
                        'temp_user_id' => null
                    ]
                );
            }
            if(get_setting('customer_otp_with') != 'disabled'){
                if (get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email') && $user->email_verified_at == null) {

                    $user->notify(new EmailVerificationNotification());
                    return response()->json([
                        'success' => true,
                        'verified'=> false,
                        'email_verified' => false,
                        'message' => translate('Please verify your account')
                    ], 200);

                }
                elseif((get_setting('customer_login_with') == 'phone' ||
                        (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'phone'))
                    && $user->phone_verified_at == null){

                    (new SmsServices)->phoneVerificationSms($user->phone, $user->verification_code);
                    return response()->json([
                        'success' => true,
                        'verified'=> false,
                        'phone_verified' => false,
                        'message' => translate('Please verify your account')
                    ], 200);

                }
            }

            $this->validateLogin($request);

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if (method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
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

        } else {
            flash(translate('Only customers can login here'))->error();
            return back();
        }
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request, $provider)
    {
        session()->put('redirect_to', $request->redirect_to);
        return Socialite::driver($provider)->redirect();
    }


    public function signup(Request $request)
    {
        if(get_setting('customer_login_with') == 'email'){
            $user = User::where('email', $request->email)->first();
        }
        elseif(get_setting('customer_login_with') == 'phone'){
            $user = User::where('phone', $request->phone)->first();
        }
        else{
            $user = User::where('phone', $request->phone)->orWhere('email', $request->email)->first();
        }

          if ($user != null) {
             flash(translate('User already exists.'));
              return back();
          }
        /*  if (!$request->has('phone') || !$request->has('email')) {
               flash(translate('Email & phone is required.'))->error();
              return back();
          }*/

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'verification_code' => rand(100000, 999999)
        ]);
        $user->save();

        if ($request->has('temp_user_id') && $request->temp_user_id != null) {
            Cart::where('temp_user_id', $request->temp_user_id)->update(
                [
                    'user_id' => $user->id,
                    'temp_user_id' => null
                ]
            );
        }

        if(get_setting('customer_otp_with') != 'disabled'){
            if (get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email')) {
                $user->notify(new EmailVerificationNotification());
                return response()->json([
                    'success' => true,
                    'verified'=> false,
                    'message' => translate('A verification code has been sent to your email.')
                ], 200);
            } else {
                (new SmsServices)->phoneVerificationSms($user->phone, $user->verification_code);
                return response()->json([
                    'success' => true,
                    'verified'=> false,
                    'message' => translate('A verification code has been sent to your phone.')
                ], 200);
            }
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        try {
            if ($provider == 'twitter') {
                $user = Socialite::driver('twitter')->user();
            } else {
                $user = Socialite::driver($provider)->stateless()->user();

            }
        } catch (\Exception $e) {
            return redirect(session('redirect_to') . "?social_login=failed");
        }
        $request['email'] =  $user->email;
        $request['password'] =  $user->id;
        // check if they're an existing user
        $existingUser = User::where('provider_id', $user->id)
            ->orWhere('email', $user->email)->first();
        if (!$existingUser) {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->password        = Hash::make($user->id);
            $newUser->email_verified_at = date('Y-m-d H:m:s');
            $newUser->provider_id     = $user->id;
            $newUser->save();
        }

        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        };
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
//        return redirect(session('redirect_to') . "?access_token=" . $tokenResult->accessToken);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return $request->only($this->username(), 'password');
        }
        return ['phone' => $request->get('email'), 'password' => $request->get('password')];
    }

    /**
     * Check user's role and redirect user based on their role
     * @return
     */
    public function authenticated()
    {
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->user_type == 'seller') {
            return redirect()->route('seller.dashboard');
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        flash(translate('Invalid email or password'))->error();
        return back();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if (auth()->user() != null && (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')) {
            $redirect_route = 'login';
        } else {
            $redirect_route = 'home';
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route($redirect_route);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
