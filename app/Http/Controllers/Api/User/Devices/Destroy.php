<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Devices;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Devices\DestroyRequest;
use App\Http\Requests\Api\User\Devices\StoreRequest;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Device;
use App\Services\Calendar\UserCalendarService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Destroy extends ApiController
{
    /**
     * @param DestroyRequest $request
     * @return array
     * @throws \Exception
     */
    public function __invoke(DestroyRequest $request)
    {
        $oUser = $this->authUser($request);
        $data = $request->validated();

        $oDevice = $oUser->devices()
            ->where('token', $data['token'])
            ->where('type', $data['type'])
            ->first();

        if (!is_null($oDevice)) {
            $oDevice->delete();
        }

        return responseCommon()->apiSuccess([], 'Device successfully deleted.');
    }
}
