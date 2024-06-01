<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Settings\Notifications;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Settings\Notifications\UpdateRequest;
use Illuminate\Http\JsonResponse;

class Update extends ApiController
{
    /**
     * @param UpdateRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(UpdateRequest $request)
    {
        $oUser = $this->authUser($request);
        $data = $request->all();
        $setting = $data['name'];
        $enable = $data['enable'];

        if (is_null($oUser->settings)) {
            $oUser->settings()->create();
        }
        $oUser->settings()->update([
            'notification_' . $setting => $enable,
        ]);
        return responseCommon()->apiSuccess();
    }
}
