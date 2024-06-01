<?php

declare(strict_types=1);

namespace App\Services\Toastr\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\Toastr\ToastrService;

/**
 * Class Toastr
 * @package App\Services\Toastr\Facades
 *
 * @method static render()
 */
class Toastr extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ToastrService::class;
    }
}
