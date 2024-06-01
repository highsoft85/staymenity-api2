<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Guest\Reviews;

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
use App\Services\Model\UserReviewServiceModel;
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
        /** @var User|null $oUser */
        $oUser = User::active()->where('id', $id)->first();
        if (is_null($oUser)) {
            return responseCommon()->apiNotFound();
        }
        if (!$oUser->hasAnyRole([User::ROLE_GUEST])) {
            return responseCommon()->apiNotFound();
        }
        $limit = $request->get('limit') ?? 10;

        $query = (new UserReviewServiceModel($oUser))->getQueryReviewsByGuest();
        /** @var LengthAwarePaginator $oResult */
        $oResult = $query->paginate($limit);
        $aItems = $oResult->values()->transform(function (Review $item) {
            return (new ReviewTransformer())->transformFromRole($item, User::ROLE_HOST);
        })->toArray();
        return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
    }
}
