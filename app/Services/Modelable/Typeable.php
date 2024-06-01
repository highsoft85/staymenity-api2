<?php

declare(strict_types=1);

namespace App\Services\Modelable;

trait Typeable
{
    /**
     * @return array
     */
    public function types()
    {
        return property_exists($this, 'types') ? $this->types : [
            0 => 'Не активно',
            1 => 'Активно',
        ];
    }

    /**
     * @return array
     */
    public function typeIcons(): array
    {
        return property_exists($this, 'typeIcons') ? $this->typeIcons : [
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
    public static function staticTypes(): array
    {
        return (new self())->types();
    }

    /**
     * Get statuses array
     *
     * @return mixed
     */
    public function getTypes()
    {
        return $this->types();
    }

    /**
     * Accessor for get text status
     *
     * @return string
     */
    public function getTypeTextAttribute(): string
    {
        return $this->types()[$this->type];
    }

    /**
     * Accessor for get text status
     *
     * @return array
     */
    public function getTypeIconAttribute(): array
    {
        $this->typeIcons()[$this->type]['title'] = $this->types()[$this->type];
        return $this->typeIcons()[$this->type];
    }
}
