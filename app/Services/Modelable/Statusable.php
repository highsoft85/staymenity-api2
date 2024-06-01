<?php

declare(strict_types=1);

namespace App\Services\Modelable;

/**
 * Trait Statusable
 * @package App\Services\Modelable
 *
 * @property string $statusText
 * @property string $statusIcon
 * @property string $statusName
 */
trait Statusable
{
    /**
     * @return array
     */
    public function statuses(): array
    {
        return property_exists($this, 'statuses') ? $this->statuses : [
            0 => 'Inactive',
            1 => 'Active',
        ];
    }

    /**
     * @return array
     */
    public function statusIcons(): array
    {
        return property_exists($this, 'statusIcons') ? $this->statusIcons : [
            0 => [
                'class' => 'badge badge-default',
            ],
            1 => [
                'class' => 'badge badge-success',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function staticStatuses(): array
    {
        return (new self())->statuses();
    }

    /**
     * @return array
     */
    public static function staticStatusIcons(): array
    {
        return (new self())->statusIcons();
    }

    /**
     * Get statuses array
     *
     * @return mixed
     */
    public function getStatuses()
    {
        return $this->statuses();
    }

    /**
     * Accessor for get text status
     *
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return $this->statuses()[$this->status];
    }

    /**
     * Accessor for get text status
     *
     * @return array
     */
    public function getStatusIconAttribute(): array
    {
        $this->statusIcons()[$this->status]['title'] = $this->statuses()[$this->status];
        return $this->statusIcons()[$this->status];
    }

    /**
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        return $this->statusIcons()[$this->status]['name'];
    }
}
