<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth\Socialite;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\Auth\Socialite\SocialiteRequest;
use App\Models\User;
use App\Services\Environment;
use App\Services\Socialite\FacebookAccountService;
use App\Services\Socialite\GoogleAccountService;
use App\Services\Socialite\MockAccountService;
use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;

class Google extends AuthController
{
    /**
     * @param Request $request
     * @param MockAccountService $service
     * @return array|JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request, MockAccountService $service)
    {
        if (config('app.env') === Environment::DOCUMENTATION) {
            return responseCommon()->apiDataSuccess([
                'token' => '{token}',
            ], __('auth.login_success'));
        }
        slackInfo(GoogleAccountService::NAME);
        slackInfo($request->all());
        $oUser = null;
        if ($request->exists('user_id')) {
            $oUser = User::find($request->get('user_id'));
        }
        if ($request->exists('id_token')) {
            $service = new MockAccountService();
            $token = $request->get('id_token');
            try {
                $data = $this->getResponse($token);
                slackInfo($data);
                $oUser = $service->createOrGetUser([
                    'id' => $data['sub'],
                    'email' => $data['email'],
                    'name' => $data['name'],
                    'avatar' => $data['picture'],
                ], $oUser, GoogleAccountService::NAME);
                if (is_null($oUser)) {
                    if (!is_null($service->errorMessage)) {
                        return responseCommon()->apiError([], $service->errorMessage, 401);
                    }
                    return responseCommon()->apiUnauthorized();
                }
                slackInfo(['user_id' => $oUser->id]);
                $service->setRoleAfterAuth($oUser);
                return responseCommon()->apiDataSuccess([
                    'token' => $this->getToken($oUser),
                ], __('auth.login_success'));
            } catch (\Exception $e) {
                slackInfo($e->getMessage());
                return responseCommon()->apiErrorBadRequest([], $e->getMessage());
            }
        }
        $service = new GoogleAccountService();
        $token = $request->get('access_token');
        $oUser = $service->createOrGetUser(Socialite::with(GoogleAccountService::NAME)->userFromToken($token), $oUser);
        if (is_null($oUser)) {
            if (!is_null($service->errorMessage)) {
                return responseCommon()->apiError([], $service->errorMessage, 422);
            }
            return responseCommon()->apiUnauthorized();
        }
        slackInfo(['user_id' => $oUser->id]);
        $service->setRoleAfterAuth($oUser);
        return responseCommon()->apiDataSuccess([
            'token' => $this->getToken($oUser),
        ], __('auth.login_success'));
    }

    /**
     * @param string $token
     * @return mixed
     */
    private function getResponse(string $token)
    {
        $res = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $token,
        ]);
        return json_decode($res->body(), true);
    }
//
//    /**
//     * @param SocialiteRequest $request
//     * @param GoogleAccountService $service
//     * @return array|JsonResponse
//     */
//    public function skip__invoke(SocialiteRequest $request, GoogleAccountService $service)
//    {
//        if (config('app.env') === Environment::DOCUMENTATION) {
//            return responseCommon()->apiDataSuccess([
//                'token' => '{token}',
//            ], __('auth.login_success'));
//        }
//        slackInfo($request->all());
//        $oUser = null;
//        if ($request->exists('user_id')) {
//            $oUser = User::find($request->get('user_id'));
//        }
//        $token = $request->get('access_token');
//        $oUser = $service->createOrGetUser(Socialite::with(GoogleAccountService::NAME)->userFromToken($token), $oUser);
//        if (is_null($oUser)) {
//            return responseCommon()->apiUnauthorized();
//        }
//        $service->setRoleAfterAuth($oUser);
//        return responseCommon()->apiDataSuccess([
//            'token' => $this->getToken($oUser),
//        ], __('auth.login_success'));
//    }
}
