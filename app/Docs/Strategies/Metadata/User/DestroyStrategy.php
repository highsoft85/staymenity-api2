<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class DestroyStrategy extends Strategy
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
        return $this->route_user_destroy;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'После успешного ответа удалять из стора его ключ и выходить из системы, можно редиректнуть на главную.',
            'authenticated' => true,
        ];
    }
}
