<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Verifications\Identities;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;
use App\Models\UserIdentity;

class StoreStrategy extends Strategy
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
        return $this->route_user_verifications_identities_store;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
//            'type' => [
//                'description' => 'Тип, возможные варианты: `' . UserIdentity::TYPE_PASSPORT . '`, `' . UserIdentity::TYPE_DRIVERS . '`',
//                'required' => true,
//                'value' => UserIdentity::TYPE_PASSPORT,
//                'type' => 'string',
//            ],
//            'image_front' => [
//                'description' => 'Бинарный файл',
//                'required' => true,
//                'value' => storage_path('tests/listings/barbecue.jpg'),
//                'type' => 'file',
//            ],
//            'image_back' => [
//                'description' => 'Бинарный файл, обязательно для `type=drivers`',
//                'required' => false,
//                'value' => storage_path('tests/listings/barbecue.jpg'),
//                'type' => 'file',
//            ],
//            'image_selfie' => [
//                'description' => 'Бинарный файл',
//                'required' => true,
//                'value' => storage_path('tests/listings/barbecue.jpg'),
//                'type' => 'file',
//            ],
        ];
    }
}
