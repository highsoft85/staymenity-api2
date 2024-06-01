<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Listings\Reviews;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\PaymentTransformer;
use App\Http\Transformers\Api\ReviewTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Models\UserSave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends ApiController
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        /** @var Listing|null $oItem */
        $oItem = Listing::active()->where('id', $id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        $limit = $request->get('limit') ?? 10;
        /** @var LengthAwarePaginator $oResult */
        $oResult = $oItem->reviewsActiveOrdered()->paginate($limit);
        $aItems = $oResult->values()->transform(function (Review $item) {
            return (new ReviewTransformer())->transform($item);
        })->toArray();
        return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
    }
}
