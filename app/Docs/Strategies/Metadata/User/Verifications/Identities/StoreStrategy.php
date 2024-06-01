<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Verifications\Identities;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class StoreStrategy extends Strategy
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
        return $this->route_user_verifications_identities_store;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'user/verifications/identities',
            'groupDescription' => view('docs.metadata.user.verifications.identities', [])->render(),
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.user.verifications.identities.store', [])->render(),
            'authenticated' => true,
        ];
    }
}
