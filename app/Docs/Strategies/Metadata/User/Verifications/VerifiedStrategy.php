<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Verifications;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class VerifiedStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_METADATA;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_verifications_verified;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/verifications',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Отправить запрос на очистку последнего уведомления и обновлять юзера',
            'authenticated' => true,
        ];
    }
}
