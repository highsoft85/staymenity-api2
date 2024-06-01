<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Devices;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Devices\StoreRequest;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Device;
use App\Services\Calendar\UserCalendarService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Store extends ApiController
{
    /**
     * @param StoreRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(StoreRequest $request)
    {
        $oUser = $this->authUser($request);
        $data = $request->validated();

        $oDevice = $oUser->devices()
            ->where('token', $data['token'])
            ->where('type', $data['type'])
            ->first();

        // если новое устройство
        if (is_null($oDevice)) {
            // то удалить остальные устройства и добавить
            $oUser
                ->devices()
                ->where('type', $data['type'])
                ->delete();

            $oUser->devices()->create([
                'token' => $data['token'],
                'type' => $data['type'],
            ]);
        }
        // если было устройство, то ничего не делать

        return responseCommon()->apiSuccess([], 'Device successfully added.');
    }
}
