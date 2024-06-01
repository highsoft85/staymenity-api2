<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Devices;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Devices\StoreRequest;
use App\Http\Transformers\Api\DeviceTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Device;
use App\Services\Calendar\UserCalendarService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Index extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);
        $data = $request->all();

        $query = $oDevice = $oUser->devices();

        if (isset($data['type'])) {
            $query->where('type', $data['type']);
        }

        $oItems = $query->get();

        $aItems = $oItems->transform(function (Device $item) {
            return (new DeviceTransformer())->transform($item);
        })->toArray();
        return responseCommon()->apiDataSuccess($aItems);
    }
}
