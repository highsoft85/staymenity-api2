<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Device;
use App\Models\Type;
use App\Models\User;
use App\Models\UserSocialAccount;
use League\Fractal\TransformerAbstract;

class DeviceTransformer extends TransformerAbstract
{
    /**
     * @param Device $oItem
     * @return array
     */
    public function transform(Device $oItem)
    {
        return [
            'id' => $oItem->id,
            'token' => $oItem->token,
            'type' => $oItem->type,
        ];
    }
}
