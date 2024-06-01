<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Verifications\Identities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\UserIdentityTransformer;
use App\Models\User;
use App\Models\UserIdentity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Show extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);
        /** @var UserIdentity|null $oIdentity */
        $oIdentity = $oUser->identities()->first();

        if (!is_null($oIdentity)) {
            $aItem = (new UserIdentityTransformer())->transform($oIdentity);
            return responseCommon()->apiDataSuccess($aItem);
        }
        return responseCommon()->apiSuccess();
    }
}
