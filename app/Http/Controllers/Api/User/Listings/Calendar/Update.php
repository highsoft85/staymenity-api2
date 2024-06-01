<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Listings\Calendar;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Listings\Calendar\UpdateRequest;
use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Models\User;
use App\Models\UserCalendar;
use App\Services\Calendar\UserCalendarService;
use App\Services\Model\UserCalendarServiceModel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Update extends ApiController
{
    /**
     * @param UpdateRequest $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(UpdateRequest $request, int $id)
    {
        $oUser = $this->authUser($request);
        /** @var Listing|null $oItem */
        $oItem = $oUser->listings()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $oService = (new UserCalendarServiceModel($oUser, $oItem));
        $data = $request->validated();
        if (isset($data['date'])) {
            $date = Carbon::parse($data['date'])->startOfDay();
            $oService->setByDate($data['type'], $date);
        }
        if (isset($data['date_from']) && isset($data['date_to'])) {
            /** @var CarbonPeriod|Carbon[] $period */
            $period = CarbonPeriod::between($data['date_from'], $data['date_to']);
            $oService->setByPeriod($data['type'], $period);
        }
        if (isset($data['action'])) {
            $oService->setByAction($data['action']);
        }

        return responseCommon()->apiSuccess();
    }
}
