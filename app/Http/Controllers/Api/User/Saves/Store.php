<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Saves;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Saves\StoreRequest;
use App\Models\Listing;
use App\Models\Type;
use App\Models\User;
use App\Models\UserSave;
use App\Services\Model\ListingServiceModel;
use Illuminate\Http\JsonResponse;

class Store extends ApiController
{
    /**
     * @param StoreRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(StoreRequest $request)
    {
        $oUser = $this->authUser($request);

        $data = $request->validated();
        $oUserSave = $this->create($data, $oUser);
        return responseCommon()->apiDataSuccess([
            'id' => $oUserSave->id,
        ]);
    }

    /**
     * @param array $data
     * @param User $oUser
     * @return UserSave
     */
    private function create(array $data, User $oUser)
    {
        /** @var UserSave $oUserSave */
        $oUserSave = $oUser->saves()->create([
            'title' => $data['title'],
        ]);
        return $oUserSave;
    }
}
