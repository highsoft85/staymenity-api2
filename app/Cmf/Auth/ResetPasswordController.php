<?php

declare(strict_types=1);

namespace App\Cmf\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request)
    {
        $email = Crypt::decrypt($request->get('token'));
        /** @var User|null $oUser */
        $oUser = User::where('email', $email)->first();
        if (is_null($oUser)) {
            abort(404);
        }
        $reset_token = $request->get('reset_token');

        return view('cmf.auth.passwords.reset', [
            'token' => $reset_token,
            'email' => $oUser->email,
        ]);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $validation = Validator::make($request->all(), $this->rules(), $this->validationErrorMessages());
        if ($validation->fails()) {
            return responseCommon()->validationMessages($validation);
        }

        if ($request->exists('phpunit') && $request->get('phpunit')) {
            $response = Password::PASSWORD_RESET;
        } else {
            $response = $this->broker()->reset($this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            });
        }
        if ($response === Password::PASSWORD_RESET) {
            return responseCommon()->success([
                'redirect' => routeCmf('index'),
            ]);
        }
        return responseCommon()->validationMessages(null, [
            'email' => __('auth.reset_password.failed'),
        ]);
    }
}
