<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Services\Modelable\Statusable;

trait UserIdentityStatusesTrait
{

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statuses = [
        self::STATUS_NOT_VERIFIED => 'Not verified',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_SUCCESS => 'Verified',
        self::STATUS_FAILED => 'Failed',
        self::STATUS_QUEUED => 'Queued',
    ];

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statusIcons = [
        self::STATUS_NOT_VERIFIED => [
            'name' => 'not_verified',
            'class' => 'badge badge-default',
        ],
        self::STATUS_PENDING => [
            'name' => 'pending',
            'class' => 'badge badge-info',
        ],
        self::STATUS_SUCCESS => [
            'name' => 'verified',
            'class' => 'badge badge-success',
        ],
        self::STATUS_FAILED => [
            'name' => 'failed',
            'class' => 'badge badge-danger',
        ],
        self::STATUS_QUEUED => [
            'name' => 'queued',
            'class' => 'badge badge-info',
        ],
    ];
}
