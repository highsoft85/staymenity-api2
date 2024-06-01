<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Services\Modelable\Statusable;

trait UserStatusesTrait
{

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statuses = [
        self::STATUS_NOT_ACTIVE => 'Inactive',
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
}
