<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

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
     * @param ForgotPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $oUser = User::where('email', $request->get('email'))->first();

        if (is_null($oUser)) {
            return responseCommon()->validationMessages(null, [
                'email' => __('auth.reset_password.dont_found'),
            ]);
        }
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );
        if ($response === Password::RESET_LINK_SENT) {
            return responseCommon()->apiSuccess([], __('auth.reset_password.success'));
        }
        return responseCommon()->validationMessages(null, [
            'email' => __('auth.reset_password.failed'),
        ]);
    }
}
