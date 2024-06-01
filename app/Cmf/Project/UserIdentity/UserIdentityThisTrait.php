<?php

declare(strict_types=1);

namespace App\Cmf\Project\UserIdentity;

use App\Models\SystemOptionValue;
use App\Models\UserIdentity;
use Illuminate\Http\Request;

trait UserIdentityThisTrait
{
    /**
     * @param Request $request
     * @param object|SystemOptionValue $oItem
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function thisUpdate(Request $request, SystemOptionValue $oItem)
    {
        $oItem->update([
            'value' => $request->get('value'),
        ]);
        return responseCommon()->success([], 'The record was updated.');
    }

    /**
     * @param UserIdentity $oItem
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function thisDestroy(UserIdentity $oItem)
    {
        $oItem->user->update([
            'identity_verified_at' => null,
        ]);
        $oItem->delete();
        return responseCommon()->success([], 'The record was deleted.');
    }
}
