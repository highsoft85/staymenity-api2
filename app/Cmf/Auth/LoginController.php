<?php

declare(strict_types=1);

namespace App\Cmf\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use App\Services\Toastr\Toastr;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Определение если Админский вход
     *
     * @var bool
     */
    protected $isAdmin = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')
            ->except('logout')
            ->except('logoutAdmin');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response|void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            $this->username() => 'required|email|string',
            'password' => 'required|string',
        ]);
        if ($validation->fails()) {
            return responseCommon()->validationMessages($validation);
        }
        /** @var User|null $oUser */
        $oUser = User::where('email', $request->get('email'))->first();
        if (is_null($oUser)) {
            return responseCommon()->validationMessages(null, [
                'email' => __('auth.failed'),
            ]);
        }
        if (!$oUser->hasAnyRole([User::ROLE_ADMIN, User::ROLE_MANAGER])) {
            return responseCommon()->validationMessages(null, [
                'email' => __('auth.failed'),
            ]);
        }
        if (!$oUser->isActive()) {
            return responseCommon()->validationMessages(null, [
                'email' => __('auth.failed'),
            ]);
        }
        $this->isAdmin = $request->has('adminToken');
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
            return;
        }


        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return responseCommon()->validationMessages(null, [
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|array
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }
        return responseCommon()->validationMessages(null, [
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return mixed
     */
    protected function authenticated(Request $request, User $user)
    {
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'redirect' => $request->get('backTo') ?? redirect()->back()->getTargetUrl(),
        ]);
        //(new UserServiceModel($user))->loginEvent();
        return $response;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showAdminLoginForm()
    {
        return view('cmf.auth.login');
    }

    public function showActivateForm()
    {
        return view('cmf.auth.login');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sanctum()
    {
        return view('cmf.auth.sanctum');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function logoutAdmin(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: responseCommon()->success([
            'redirect' => url('/'),
        ]);
    }
}
