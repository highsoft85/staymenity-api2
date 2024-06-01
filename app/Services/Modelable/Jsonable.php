<?php

declare(strict_types=1);

namespace App\Services\Modelable;

use App\Models\Amenity;
use App\Models\Location;
use App\Models\Rule;
use Illuminate\Support\Facades\File;

/**
 * Trait Jsonable
 * @package App\Services\Modelable
 *
 */
trait Jsonable
{
    /**
     * @return array
     */
    public function getJsonToArray(string $key)
    {
        $data = !is_null($this->{$key}) ? $this->{$key} : '{}';
        return json_decode($data, true);
    }
}
