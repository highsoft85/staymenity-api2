<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\User\Verifications\Identities\Step;

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
    public $stage = self::STAGE_URL_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_verifications_identities_update;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'int',
                'description' => 'ID',
                'required' => true,
                'value' => User::first()->identities()->first()->id,
            ],
            'step' => [
                'type' => 'string',
                'description' => 'Шаг',
                'required' => true,
                'value' => UserIdentity::STEP_SELFIE,
            ],
        ];
    }
}
