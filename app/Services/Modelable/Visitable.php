<?php

declare(strict_types=1);

namespace App\Services\Modelable;

use App\Models\Amenity;
use App\Models\Location;
use App\Models\Rule;
use App\Models\Visit;
use Illuminate\Support\Facades\File;

trait Visitable
{
    /**
     * @return mixed
     */
    public function visits()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }
}
