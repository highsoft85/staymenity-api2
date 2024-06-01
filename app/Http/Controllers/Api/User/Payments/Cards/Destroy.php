<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Payments\Cards;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Models\Listing;
use App\Models\PaymentCard;
use App\Models\UserSave;
use App\Services\Environment;
use App\Services\Model\UserPaymentCardServiceModel;
use App\Services\Payment\StripePaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Destroy
 * @package App\Http\Controllers\Api\User\Payments\Cards
 */
class Destroy extends ApiController
{
    /**
     * @param Request $request
     * @param string $id
     * @return array|JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request, string $id)
    {
        $oUser = $this->authUser($request);

        if (config('app.env') === Environment::DOCUMENTATION) {
            return responseCommon()->apiSuccess([]);
        }

        $result = transaction()->commitAction(function () use ($oUser, $id) {
            $method = Crypt::decryptString($id);
            (new UserPaymentCardServiceModel($oUser))->detach($method);
        });
        if (!$result->isSuccess()) {
            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
        }
        return responseCommon()->apiSuccess([]);
    }
//
//    /**
//     * @param Request $request
//     * @param int $id
//     * @return array|JsonResponse
//     * @throws \Exception
//     */
//    public function __invoke(Request $request, int $id)
//    {
//        $oUser = $this->authUser($request);
//
//        $oCard = $oUser->paymentCards()->where('id', $id)->first();
//        if (is_null($oCard)) {
//            return responseCommon()->apiNotFound();
//        }
//        $oCard->delete();
//        return responseCommon()->apiDataSuccess([]);
//    }
}
