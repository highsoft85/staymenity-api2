<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Services\Modelable\Statusable;

trait TransferStatusesTrait
{

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statuses = [
        self::STATUS_NOT_ACTIVE => 'Not active',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_DEACTIVATED => 'Deactivated',
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
        self::STATUS_ACTIVE => [
            'name' => 'active',
            'class' => 'badge badge-success',
        ],
        self::STATUS_PENDING => [
            'name' => 'pending',
            'class' => 'badge badge-success',
        ],
        self::STATUS_COMPLETED => [
            'name' => 'completed',
            'class' => 'badge badge-success',
        ],
        self::STATUS_CANCELLED => [
            'name' => 'cancelled',
            'class' => 'badge badge-default',
        ],
        self::STATUS_DEACTIVATED => [
            'name' => 'deactivated',
            'class' => 'badge badge-default',
        ],
    ];
}
