<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\User\Payments\Cards;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;
use App\Models\UserSave;

class DestroyStrategy extends Strategy
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
        return $this->route_user_payments_cards_destroy;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'string',
                'description' => 'Зашифрованный ID payment_method_id',
                'required' => true,
                'value' => 'eyJpdiI6Ik9pc1JiUzFHTzdlTkJhQ0xncnQydkE9PSIsInZhbHVlIjoiVGZEM1dmR2pmSDNrMHJOaXBBR3c5dHZpYnh2a01QTjhOQTI0aUhNWGtxTT0iLCJtYWMiOiJjZmEzNWI1NTNmMWYwNzY1ZTRkNThlOGUwZTI4NDM3ZGNiNzJjNjVjZDA2N2M1ZmIxMzIyMzYyMTdmMGMyY2QxIn0=',
            ],
        ];
    }
}
