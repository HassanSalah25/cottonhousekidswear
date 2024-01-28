<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\UserCollection;
use App\Http\Services\SmsServices;
use App\Mail\EmailManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Validation\ValidationException;
use Mail;
Use Str;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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

      /*  if ($user != null) {
           flash(translate('User already exists.'));
            return back();
        }
        if (!$request->has('phone') || !$request->has('email')) {
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
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
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
        throw ValidationException::withMessages([
            $request->name => [trans('auth.failed')],
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->boolean('remember')
        );
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
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
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return $request->only('email', 'password');
        }
        return ['phone' => $request->get('email'), 'password' => $request->get('password')];
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
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

    public function loginForm(Request $request)
    {
        return view('frontend.auth.login');
    }

    public function signupForm(Request $request)
    {
        return view('frontend.auth.register');
    }

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

                }elseif((get_setting('customer_login_with') == 'phone' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'phone')) && $user->phone_verified_at == null){

                    (new SmsServices)->phoneVerificationSms($user->phone, $user->verification_code);
                    return response()->json([
                        'success' => true,
                        'verified'=> false,
                        'phone_verified' => false,
                        'message' => translate('Please verify your account')
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

        } else {

            flash(translate('Only customers can login here'))->error();
            return back();
        }
    }

    public function verify(Request $request)
    {
        $phone = Str::replace(' ', '', $request->phone);
        if(get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email')){
            $user = User::where('email', $request->email)->first();
        }
        elseif(get_setting('customer_login_with') == 'phone' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'phone')){
            $user = User::where('phone', $phone)->first();
        }
        else{
            $user = null;
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => translate('No user found with this email address.')
            ], 200);
        }
        if ($user->verification_code != $request->code) {
            return response()->json([
                'success' => false,
                'message' => translate('Code does not match.')
            ], 200);
        } else {

            if(get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email')){
                $user->email_verified_at = date('Y-m-d H:m:s');
            }else{
                $user->phone_verified_at = date('Y-m-d H:m:s');
            }

            $user->save();
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccess($tokenResult, $user);
        }
    }

    public function resend_code(Request $request)
    {
        $phone = Str::replace(' ', '', $request->phone);
        if(get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email')){
            $user = User::where('email', $request->email)->first();
        }
        elseif(get_setting('customer_login_with') == 'phone' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'phone')){
            $user = User::where('phone', $phone)->first();
        }
        else{
            $user = null;
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => translate('No user found with this email address.')
            ], 200);
        }

        $user->verification_code = rand(100000, 999999);
        $user->save();

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

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /*public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();
        return response()->json([
            'message' => translate('Successfully logged out')
        ]);
    }*/

    protected function loginSuccess($tokenResult, $user)
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();
        return response()->json([
            'success' => true,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'verified' => true,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'balance' => $user->balance,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => api_asset($user->avatar),
            ],
            'message' => translate('Successfully logged in'),
            'followed_shops' => $user->followed_shops->pluck('id')->toArray()
        ]);
    }

    public function tempIdCartUpdate(Request $request)
    {
        if ($request->temp_user_id != null) {
            Cart::where('temp_user_id', $request->temp_user_id)->update([
                'user_id' => auth()->guard('api')->user()->id,
                'temp_user_id' => null,
            ]);
        }
        return response()->json([
            'result' => true,
            'message' => translate('Cart updated'),
        ]);
    }
}
