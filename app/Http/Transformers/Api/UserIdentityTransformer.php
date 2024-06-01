<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\UserIdentity;

class UserIdentityTransformer
{
    /**
     * @param UserIdentity $oItem
     * @return array
     */
    public function transform(UserIdentity $oItem)
    {
        return [
            'id' => $oItem->id,
            'type' => $oItem->type,
            'status' => $oItem->status,
        ];
    }
}
