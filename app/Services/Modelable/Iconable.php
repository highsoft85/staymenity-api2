<?php

declare(strict_types=1);

namespace App\Services\Modelable;

use App\Models\Amenity;
use App\Models\Location;
use App\Models\Rule;
use Illuminate\Support\Facades\File;

/**
 * Trait Iconable
 * @package App\Services\Modelable
 *
 * @property string|null $iconSvg
 * @property string|null $iconPngLight
 * @property string|null $iconPngDark
 */
trait Iconable
{
    /**
     * @return string|null
     */
    public function getIconSvgAttribute()
    {
        if (is_null($this->icon)) {
            return null;
        }
        $path = null;
        switch (get_class($this)) {
            case Rule::class:
                $path = 'svg/rules/';
                break;
            case Amenity::class:
                $path = 'svg/amenities/';
                break;
            default:
                break;
        }
        if (is_null($path)) {
            return null;
        }
        $file = public_path($path . $this->icon . '.svg');
        if (!File::exists($file)) {
            return null;
        }
        return config('image.url') . '/' . $path . $this->icon . '.svg';
    }

    /**
     * @return string|null
     */
    public function getIconPngLightAttribute()
    {
        return $this->getPngIconByTheme('light');
    }

    /**
     * @return string|null
     */
    public function getIconPngDarkAttribute()
    {
        return $this->getPngIconByTheme('dark');
    }

    /**
     * @param string $theme
     * @return string|null
     */
    private function getPngIconByTheme(string $theme)
    {
        if (is_null($this->icon)) {
            return null;
        }
        $path = null;
        switch (get_class($this)) {
            case Rule::class:
                $path = 'img/rules/' . $theme . '/';
                break;
            case Amenity::class:
                $path = 'img/amenities/' . $theme . '/';
                break;
            default:
                break;
        }
        if (is_null($path)) {
            return null;
        }
        $file = public_path($path . $this->icon . '.png');
        if (!File::exists($file)) {
            return null;
        }
        return config('image.url') . '/' . $path . $this->icon . '.png';
    }
}
