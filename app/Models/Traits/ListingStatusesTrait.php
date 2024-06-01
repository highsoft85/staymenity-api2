<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Services\Modelable\Statusable;

trait ListingStatusesTrait
{

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statuses = [
        self::STATUS_NOT_ACTIVE => 'Not active',
        self::STATUS_ACTIVE => 'Active',
    ];

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statusIcons = [
        self::STATUS_NOT_ACTIVE => [
            'class' => 'badge badge-default',
        ],
        self::STATUS_ACTIVE => [
            'class' => 'badge badge-success',
        ],
    ];

    /**
     * @return array
     */
    public function currentStatuses()
    {
        return [
            self::STATUS_NAME_FREE => 'Free',
            self::STATUS_NAME_BANNED => 'Blocked By System',
            self::STATUS_NAME_BOOKED => 'Booked',
            self::STATUS_NAME_UNAVAILABLE => 'Unavailable',
            self::STATUS_NAME_ON_PENDING => 'On Pending',
            self::STATUS_NAME_ON_REVIEW => 'On Review',
            self::STATUS_NAME_DRAFT => 'Draft',
            self::STATUS_NAME_UNLISTED => 'Unlisted',
        ];
    }
}
