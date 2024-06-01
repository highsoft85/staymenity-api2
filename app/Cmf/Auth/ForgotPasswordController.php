<?php

declare(strict_types=1);

namespace App\Cmf\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('cmf.auth.passwords.email');
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        /** @var User|null $oUser */
        $oUser = User::where('email', $request->get('email'))->first();

        if (is_null($oUser)) {
            return responseCommon()->validationMessages(null, [
                'email' => __('auth.reset_password.dont_found'),
            ]);
        }
        if (!$oUser->hasAnyRole([User::ROLE_ADMIN])) {
            return responseCommon()->validationMessages(null, [
                'email' => __('auth.reset_password.dont_found'),
            ]);
        }
        session()->put('reset-from', 'cmf');
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );
        if ($response === Password::RESET_LINK_SENT) {
            return responseCommon()->success([], __('auth.reset_password.success'));
        }
        return responseCommon()->validationMessages(null, [
            'email' => __('auth.reset_password.failed'),
        ]);
    }
}
