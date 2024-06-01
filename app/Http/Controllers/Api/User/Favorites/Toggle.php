<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Favorites;

use App\Cmf\Project\Listing\ListingController;
use App\Exceptions\ResourceExceptionValidation;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Favorites\ToggleRequest;
use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Models\User;
use App\Models\UserSave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Toggle extends ApiController
{
    /**
     * @param ToggleRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(ToggleRequest $request)
    {
        $oUser = $this->authUser($request);

        $data = $request->validated();

        $oItem = $this->getItem($data['item_type'], (int)$data['item_id']);
        if (is_null($oItem)) {
            return responseCommon()->apiNotFound();
        }
        if (!$oUser->favoriteHas($oItem)) {
            if (!isset($data['user_save_id'])) {
                throw new ResourceExceptionValidation(__('validation.required', ['attribute' => 'user_save_id']));
            }
            /** @var UserSave|null $oUserSave */
            $oUserSave = $oUser->saves()->where('id', $data['user_save_id'])->first();
            if (is_null($oUserSave)) {
                return responseCommon()->apiNotFound();
            }
            $oUser->favoriteAddToSave($oItem, $oUserSave);
        } else {
            $oUser->favoriteRemove($oItem);
        }
        return responseCommon()->apiSuccess();
    }

    /**
     * @param string $type
     * @param int $id
     * @return Listing|null
     */
    private function getItem(string $type, int $id)
    {
        $oItem = null;
        switch ($type) {
            case ListingController::NAME:
                $oItem = Listing::active()->where('id', $id)->first();
                break;
        }
        return $oItem;
    }
}
