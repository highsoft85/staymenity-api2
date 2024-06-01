<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Payments\Cards;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Payments\Cards\StoreRequest;
use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Models\Listing;
use App\Models\UserSave;
use App\Services\Environment;
use App\Services\Model\UserPaymentCardServiceModel;
use App\Services\Payment\StripePaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class Store
 * @package App\Http\Controllers\Api\User\Payments\Cards
 */
class Store extends ApiController
{
    /**
     * @param StoreRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(StoreRequest $request)
    {
        $oUser = $this->authUser($request);

        if (config('app.env') === Environment::DOCUMENTATION) {
            return responseCommon()->apiSuccess([]);
        }

        $data = $request->validated();
        $method = $data['payment_method_id'];

        $result = transaction()->commitAction(function () use ($oUser, $method) {
            (new UserPaymentCardServiceModel($oUser))->create($method);
        });
        if (!$result->isSuccess()) {
            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
        }
        return responseCommon()->apiSuccess([]);
    }
//
//    /**
//     * @param StoreRequest $request
//     * @return array|JsonResponse
//     */
//    public function __invoke(StoreRequest $request)
//    {
//        $oUser = $this->authUser($request);
//        $data = $request->validated();
//
//        $result = transaction()->commitAction(function () use ($oUser, $data) {
//            (new UserPaymentCardServiceModel($oUser))
//                ->saveCard($data['token_id'], $data['card_id'], $data['brand'], $data['last']);
//        });
//        if (!$result->isSuccess()) {
//            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
//        }
//        return responseCommon()->apiSuccess([]);
//    }
}
