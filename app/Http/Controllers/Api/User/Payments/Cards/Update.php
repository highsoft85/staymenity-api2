<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Payments\Cards;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Payments\Cards\StoreRequest;
use App\Http\Requests\Api\User\Payments\Cards\UpdateRequest;
use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Models\Listing;
use App\Models\UserSave;
use App\Services\Environment;
use App\Services\Model\UserPaymentCardServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Update
 * @package App\Http\Controllers\Api\User\Payments\Cards
 */
class Update extends ApiController
{
    /**
     * @param UpdateRequest $request
     * @param string $id
     * @return array|JsonResponse
     */
    public function __invoke(UpdateRequest $request, string $id)
    {
        $oUser = $this->authUser($request);

        if (config('app.env') === Environment::DOCUMENTATION) {
            return responseCommon()->apiSuccess([]);
        }
        $data = $request->validated();
        if (isset($data['main']) && $data['main']) {
            $result = transaction()->commitAction(function () use ($oUser, $id) {
                $method = Crypt::decryptString($id);
                (new UserPaymentCardServiceModel($oUser))->setMain($method);
            });
            if (!$result->isSuccess()) {
                return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
            }
        }
        return responseCommon()->apiSuccess([]);
    }
}
