<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Social;

use App\Models\Listing;
use App\Models\User;
use App\Services\Image\ImageService;
use App\Services\Image\ImageType;
use App\Services\Model\UserServiceModel;
use App\Services\Socialite\AppleAccountService;
use App\Services\Socialite\FacebookAccountService;
use App\Services\Socialite\GoogleAccountService;
use App\Services\Socialite\MockAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Destroy
{
    /**
     * @param Request $request
     * @param string $provider
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, string $provider)
    {
        /** @var User $oUser */
        $oUser = $request->user();

        $providers = [
            GoogleAccountService::NAME,
            FacebookAccountService::NAME,
            AppleAccountService::NAME,
            MockAccountService::NAME,
        ];
        if (!in_array($provider, $providers)) {
            return responseCommon()->apiNotFound();
        }
        $result = transaction()->commitAction(function () use ($oUser, $provider) {
            (new UserServiceModel($oUser))->deleteSocial($provider);
        });
        if (!$result->isSuccess()) {
            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
        }

        return responseCommon()->apiSuccess();
    }
}
