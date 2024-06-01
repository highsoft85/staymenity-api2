<?php

declare(strict_types=1);

namespace App\Services\Modelable;

use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Locationable
 * @package App\Services\Modelable
 *
 * @property Location|null $location
 *
 * * * METHODS
 * @method static LocationInDistance()
 * @see \App\Services\Modelable\Locationable::scopeLocationInDistance()
 */
trait Locationable
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function location()
    {
        return $this->morphOne(Location::class, 'locationable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function locations()
    {
        return $this->morphMany(Location::class, 'locationable');
    }

    /**
     * @param Builder $query
     * @param array $point
     * @param int $max_distance
     * @return Builder
     */
    public function scopeLocationInDistance(Builder $query, array $point, int $max_distance = Location::DEFAULT_DISTANCE)
    {
        return $query->whereHas('location', function (Builder $q) use ($point, $max_distance) {
            $q->inDistance($point, $max_distance);
        });
    }
}
