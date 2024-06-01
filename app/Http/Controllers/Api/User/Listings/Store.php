<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Listings;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Listings\StoreRequest;
use App\Models\Listing;
use App\Models\Type;
use App\Models\User;
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
        /** @var User $oUser */
        $oUser = $request->user();
        $data = $request->validated();
        $oListing = $this->createListing($data, $oUser);
        return responseCommon()->apiDataSuccess([
            'id' => $oListing->id,
        ]);
    }

    /**
     * @param array $data
     * @param User $oUser
     * @return Listing
     */
    private function createListing(array $data, User $oUser)
    {
        /** @var Type $oType */
        $oType = Type::find($data['type_id']);
        /** @var Listing $oListing */
        $oListing = Listing::create([
            'user_id' => $oUser->id,
            'creator_id' => $oUser->id,
            'title' => 'TMP',
            'type_id' => $oType->id,
            'guests_size' => $data['guests_size'],
            'published_at' => null,
            'status' => Listing::STATUS_NOT_ACTIVE,
        ]);

        // пустой settings чтобы дальше нигде не проверять
        $oSettings = $oListing->settings()->create([]);
        if (!empty($data['type_other']) && $oType->name === Type::NAME_OTHER) {
            $oSettings->update([
                'type' => $data['type_other'],
            ]);
        }
        $oService = (new ListingServiceModel($oListing));
        if (isset($data['place_id'])) {
            $oService->saveLocation($data['place_id']);
        }
        return $oListing;
    }
}
