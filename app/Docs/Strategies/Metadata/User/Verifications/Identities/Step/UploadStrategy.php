<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Verifications\Identities\Step;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class UploadStrategy extends Strategy
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
        return $this->route_user_verifications_identities_step_upload;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/verifications/identities',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => '',
            'authenticated' => true,
        ];
    }
}
