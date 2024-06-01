<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Responses\User\Settings\Notifications;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class UpdateStrategy extends Strategy
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
        return $this->route_user_settings_notifications_update;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            [
                'content' => json_encode([
                    'success' => true,
                ]),
                'status' => 200,
            ], [
                'content' => json_encode([
                    'message' => '422 Unprocessable Entity',
                    'errors' => [
                        'name' => __('validation.required', ['attribute' => 'name']),
                    ],
                    'status_code' => 422,
                ]),
                'status' => 422,
            ], [
                'content' => json_encode([
                    'message' => 'Unauthorized',
                    'status_code' => 401,
                ]),
                'status' => 401,
            ],
        ];
    }
}
