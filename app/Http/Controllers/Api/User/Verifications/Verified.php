<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Verifications;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\UserIdentityTransformer;
use App\Models\User;
use App\Models\UserIdentity;
use App\Notifications\User\Identity\UserIdentityVerificationStatusNotification;
use App\Services\Firebase\FirebaseCounterNotificationTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Verified extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        if (!envIsDocumentation()) {
            (new FirebaseCounterNotificationTypeService())
                ->database()
                ->setUser($oUser)
                ->clearChannel();
        }

        return responseCommon()->apiSuccess();
    }
}
