<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Services\Modelable\Statusable;

trait PaymentStatusesTrait
{

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statuses = [
        self::STATUS_NOT_ACTIVE => 'Not active',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_WAIT_FOR_CAPTURE => 'Waiting for capture',
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
        self::STATUS_CANCELLED => [
            'name' => 'cancelled',
            'class' => 'badge badge-default',
        ],
        self::STATUS_WAIT_FOR_CAPTURE => [
            'name' => 'wait_for_capture',
            'class' => 'badge badge-info',
        ],
        self::STATUS_DEACTIVATED => [
            'name' => 'deactivated',
            'class' => 'badge badge-default',
        ],
    ];
}
