<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Verifications\Identities\Step;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;
use App\Models\UserIdentity;

class UploadStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

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
            'image' => [
                'description' => 'Бинарный файл',
                'required' => true,
                'value' => storage_path('tests/listings/barbecue.jpg'),
                'type' => 'file',
            ],
        ];
    }
}
