<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\Student;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\General;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use IvanoMatteo\LaravelDeviceTracking\Models\Device;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Exception;
use RuntimeException;

class LoginController extends Controller
{
    use General, AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function showLoginForm()
    {
        Cookie::queue(Cookie::forget('_uuid_d'));
        $data['pageTitle'] = __('Login');
        $data['title'] = __('Login');
        return view('auth.login', $data);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $field = 'email';
        if (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (is_numeric($request->input('email'))) {
            $field = 'mobile_number';
        }

        $request->merge([$field => $request->input('email')]);
        $credentials = $request->only($field, 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Student status checks
            if ($user->role == USER_ROLE_STUDENT) {
                if ($user->student->status == STATUS_REJECTED) {
                    Auth::logout();
                    $this->showToastrMessage('error', __('Your account has been blocked!'));
                    return redirect("login");
                }

                if ($user->student->status == STATUS_PENDING) {
                    Auth::logout();
                    $this->showToastrMessage('warning', 'Your account is pending approval. Please wait.');
                    return redirect("login");
                }
            }

            // Instructor status checks
            if ($user->role == USER_ROLE_INSTRUCTOR && 
                $user->instructor->status == STATUS_REJECTED) {
                Auth::logout();
                $this->showToastrMessage('error', __('Your account has been blocked!'));
                return redirect("login");
            }

            // Email verification check
            if (get_option('registration_email_verification') == 1 && !$user->hasVerifiedEmail()) {
                Auth::logout();
                $this->showToastrMessage('error', __('Your email is not verified!'));
                return redirect("login");
            }

            return $user->is_admin() 
                ? redirect(route('admin.dashboard'))
                : redirect(route('main.index'));
        }

        $this->showToastrMessage('error', __('Invalid credentials'));
        return redirect("login");
    }

    /**
     * Google Login
     */
    public function redirectToGoogle(): RedirectResponse
    {
        try {
            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])
                ->redirect();
        } catch (Exception $e) {
            Log::error('Google redirect failed: ' . $e->getMessage());
            $this->showToastrMessage('error', __('Google login is currently unavailable'));
            return redirect()->route('login');
        }
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            
            if (!$socialUser->getEmail()) {
                throw new RuntimeException('Email not provided by Google');
            }

            $user = $this->_registerOrLoginUser($socialUser);
            
            if (!$user) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is pending approval'
                ]);
            }

            return redirect()->route('main.index');

        } catch (InvalidStateException $e) {
            Log::error('Google callback invalid state: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired. Please try again.'
            ]);
        } catch (Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage());
            $this->showToastrMessage('error', __('Google login failed. Please try another method.'));
            return redirect()->route('login');
        }
    }

    /**
     * Facebook Login
     */
    public function redirectToFacebook(): RedirectResponse
    {
        try {
            return Socialite::driver('facebook')
                ->setScopes(['public_profile', 'email'])
                ->redirect();
        } catch (Exception $e) {
            Log::error('Facebook redirect failed: ' . $e->getMessage());
            $this->showToastrMessage('error', __('Facebook login is currently unavailable. Please try again later.'));
            return redirect()->route('login');
        }
    }

    public function handleFacebookCallback(): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
            
            // Debugging - log the received user data
            Log::debug('Facebook user data:', (array)$socialUser);

            if (!$socialUser->getEmail()) {
                // Try alternative method to get email
                try {
                    $socialUser = Socialite::driver('facebook')
                        ->userFromToken($socialUser->token);
                    
                    if (!$socialUser->getEmail()) {
                        throw new Exception('Email permission not granted');
                    }
                } catch (Exception $tokenException) {
                    Log::error('Failed to get email from token: ' . $tokenException->getMessage());
                    throw new Exception('Could not retrieve email address from Facebook');
                }
            }

            $user = $this->_registerOrLoginUser($socialUser);
            
            if (!$user) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is pending approval'
                ]);
            }

            return redirect()->route('main.index');

        } catch (Exception $e) {
            Log::error('Facebook login failed: ' . $e->getMessage());
            $this->showToastrMessage('error', __('Facebook login failed. Please try another method.'));
            return redirect()->route('login');
        }
    }

    /**
     * Twitter Login
     */
    public function redirectToTwitter(): RedirectResponse
    {
        try {
            return Socialite::driver('twitter')->redirect();
        } catch (Exception $e) {
            Log::error('Twitter redirect failed: ' . $e->getMessage());
            $this->showToastrMessage('error', __('Twitter login is currently unavailable'));
            return redirect()->route('login');
        }
    }

    public function handleTwitterCallback(): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver('twitter')->user();
            
            if (!$socialUser->getEmail()) {
                throw new RuntimeException('Email not provided by Twitter');
            }

            $user = $this->_registerOrLoginUser($socialUser);
            
            if (!$user) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is pending approval'
                ]);
            }

            return redirect()->route('main.index');

        } catch (Exception $e) {
            Log::error('Twitter login failed: ' . $e->getMessage());
            $this->showToastrMessage('error', __('Twitter login failed. Please try another method.'));
            return redirect()->route('login');
        }
    }

    /**
     * Social User Registration/Login
     */
    protected function _registerOrLoginUser($socialUser): ?User
    {
        try {
            if (!$socialUser->getEmail()) {
                throw new RuntimeException('Email not provided by social provider');
            }

            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                $user = new User();
                $user->name = $socialUser->getName() ?? $socialUser->getNickname() ?? 'User';
                $user->email = $socialUser->getEmail();
                $user->provider_id = $socialUser->getId();
                $user->avatar = $socialUser->getAvatar();
                $user->role = USER_ROLE_STUDENT;
                $user->email_verified_at = now();
                
                if (!$user->save()) {
                    throw new RuntimeException('Failed to save user');
                }

                $nameParts = explode(' ', $user->name, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                $student = new Student();
                $student->user_id = $user->id;
                $student->first_name = $firstName;
                $student->last_name = $lastName;
                $student->status = get_option('private_mode') ? STATUS_PENDING : STATUS_ACCEPTED;
                
                if (!$student->save()) {
                    $user->delete();
                    throw new RuntimeException('Failed to save student profile');
                }
            }

            $student = $user->student;

            if ($student->status != STATUS_PENDING) {
                Auth::login($user, true);
                return $user;
            }

            $this->showToastrMessage('warning', 'Your account is pending approval');
            return null;

        } catch (Exception $e) {
            Log::error('Social login failed: ' . $e->getMessage());
            throw $e;
        }
    }

    // ... [rest of your methods remain unchanged]

    public function forgetPassword()
    {
        $data = [
            'title' => __("Forgot Password"),
            'pageTitle' => __("Forgot Password")
        ];
        return view('auth.forgot', $data);
    }

    public function forgetPasswordEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            $this->showToastrMessage('error', __('Email not found'));
            return redirect()->back();
        }

        $verification_code = rand(10000, 99999);
        $user->forgot_token = $verification_code;
        $user->save();

        try {
            Mail::to($user->email)->send(new ForgotPasswordMail($user, $verification_code));
        } catch (\Exception $e) {
            Log::error('Password reset email failed: ' . $e->getMessage());
            $this->showToastrMessage('error', 'Failed to send email. Please try again.');
            return redirect()->back();
        }

        Session::put('email', $user->email);
        Session::put('verification_code', $verification_code);

        $this->showToastrMessage('success', __('Verification code sent to your email'));
        return redirect()->route('reset-password');
    }

    public function resetPassword()
    {
        if (!Session::has('email')) {
            return redirect()->route('forget.password');
        }

        $data = [
            'title' => __("Reset Password"),
            'pageTitle' => __("Reset Password")
        ];
        return view('auth.reset-password', $data);
    }

    public function resetPasswordCheck(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric',
            'password' => 'required|min:6|confirmed',
        ]);

        $email = Session::get('email');
        $verification_code = Session::get('verification_code');

        if ($request->verification_code != $verification_code) {
            $this->showToastrMessage('error', __('Invalid verification code'));
            return redirect()->back();
        }

        $user = User::whereEmail($email)
            ->whereForgotToken($verification_code)
            ->first();

        if (!$user) {
            $this->showToastrMessage('error', __('Invalid verification code'));
            return redirect()->back();
        }

        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->forgot_token = null;
        $user->save();

        Session::forget(['email', 'verification_code']);
        $this->showToastrMessage('success', __('Password reset successfully'));
        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        if (get_option('device_control')) {
            $device_uuid = $request->cookie('_uuid_d');
            Cookie::queue(Cookie::forget('_uuid_d'));
            Device::join('device_user', 'devices.id', '=', 'device_user.device_id')
                ->where('devices.device_uuid', $device_uuid)
                ->update(['deleted_at' => now()]);
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}













