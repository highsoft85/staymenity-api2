<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Services\Modelable\Statusable;

/**
 * Trait ReservationStatusesTrait
 * @package App\Models\Traits
 *
 * @property string $statusName
 */
trait ReservationStatusesTrait
{

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statuses = [
        self::STATUS_NOT_ACTIVE => 'Not active',
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_DECLINED => 'Declined',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statusIcons = [
        self::STATUS_NOT_ACTIVE => [
            'name' => 'not_active',
            'class' => 'badge badge-default',
        ],
        self::STATUS_DRAFT => [
            'name' => 'draft',
            'class' => 'badge badge-default',
        ],
        self::STATUS_PENDING => [
            'name' => 'pending',
            'class' => 'badge badge-info',
        ],
        self::STATUS_ACCEPTED => [
            'name' => 'accepted',
            'class' => 'badge badge-success',
        ],
        self::STATUS_DECLINED => [
            'name' => 'declined',
            'class' => 'badge badge-default',
        ],
        self::STATUS_CANCELLED => [
            'name' => 'cancelled',
            'class' => 'badge badge-default',
        ],
    ];
}
