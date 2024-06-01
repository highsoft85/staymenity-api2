<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Payments;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\PaymentTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserSave;
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

        $limit = $request->get('limit') ?? 100;
//
//        if ($oUser->current_role === User::ROLE_HOST) {
//            /** @var LengthAwarePaginator $oResult */
//            $oResult = $oUser->paymentsToMe()->active()->ordered()->paginate($limit);
//            $aItems = $oResult->values()->transform(function (Payment $item) {
//                return (new PaymentTransformer())->transformForHost($item);
//            })->toArray();
//            return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
//        }
        if ($oUser->current_role === User::ROLE_GUEST) {
            /** @var LengthAwarePaginator $oResult */
            $oResult = $oUser->paymentsFromMe()->active()->ordered()->paginate($limit);
            $aItems = $oResult->values()->transform(function (Payment $item) {
                return (new PaymentTransformer())->transformForGuest($item);
            })->toArray();
            return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
        }
        return responseCommon()->apiDataSuccessWithPagination([], null);
    }
}
