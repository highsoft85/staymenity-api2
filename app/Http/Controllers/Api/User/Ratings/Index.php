<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Ratings;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\PaymentTransformer;
use App\Http\Transformers\Api\RatingTransformer;
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
use Rennokki\Rating\Models\RaterModel;

class Index extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

//        $oItems = $oUser->reviews()->get();
//        $aItems = $oItems->values()->transform(function (RaterModel $item) {
//            return (new RatingTransformer())->transformFromModel($item);
//        })->toArray();
        return responseCommon()->apiDataSuccess([]);
    }
}
