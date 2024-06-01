<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Saves;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class IndexStrategy extends Strategy
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
        return $this->route_user_saves_index;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/saves',
            'groupDescription' => '' .
                'Сохраненные списки листингов' .
                '',
            'title' => $this->url($this->route),
            'description' => 'Сохраненные списки пользователя',
            'authenticated' => true,
        ];
    }
}
