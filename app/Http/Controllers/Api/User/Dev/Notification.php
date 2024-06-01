<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Dev;

use App\Http\Controllers\Api\ApiController;
use App\Notifications\User\TestNotification;
use App\Notifications\User\TestPushNotification;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Notification extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        if (envIsDocumentation()) {
            return responseCommon()->apiSuccess();
        }
        $data = $request->all();

        if (isset($data['type']) && $data['type'] === 'push') {
            $oUser->notify(new TestPushNotification());
        } else {
            $oUser->notify(new TestNotification());
        }
        return responseCommon()->apiSuccess();
    }
}
