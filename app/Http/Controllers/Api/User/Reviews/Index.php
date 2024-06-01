<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Reviews;

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
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        $limit = $request->exists('limit')
            ? (int)$request->get('limit')
            : 100;

        $page = $request->exists('page')
            ? (int)$request->get('page')
            : 1;

        if ($oUser->current_role === User::ROLE_HOST) {
            // отзывы на хоста
            return (new UserReviewServiceModel($oUser))->getReviewsByHost($page, $limit);
        }
        if ($oUser->current_role === User::ROLE_GUEST) {
            // отзывы на гостя
            $query = (new UserReviewServiceModel($oUser))->getQueryReviewsByGuest();
            /** @var LengthAwarePaginator $oResult */
            $oResult = $query->paginate($limit);
            $aItems = $oResult->values()->transform(function (Review $item) {
                return (new ReviewTransformer())->transformFromRole($item, User::ROLE_HOST);
            })->toArray();
            return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
        }
        return responseCommon()->apiDataSuccess();
    }
}
