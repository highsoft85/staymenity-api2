<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Verifications\Identities;

use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Docs\Strategy;

class StoreStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_verifications_identities_store;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'int',
                'description' => 'ID новой верификации',
            ],
        ];
    }
}
