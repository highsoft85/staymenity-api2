<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Listings;

use App\Http\Requests\Api\Listings\StoreRequest;
use App\Http\Requests\Api\Listings\TimesRequest;
use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Models\ListingTime;
use App\Models\User;
use App\Services\Model\ListingTimesService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Times
{
    /**
     * @param TimesRequest $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(TimesRequest $request, int $id)
    {
        /** @var Listing|null $oItem */
        $oItem = Listing::find($id);
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $data = $request->validated();
        $date = Carbon::parse($data['date']);
        $times = (new ListingTimesService())->getTimes($oItem, $date);
        return responseCommon()->apiDataSuccess($times);
    }
}
