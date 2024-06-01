<?php

declare(strict_types=1);

namespace App\Services\Modelable;

use App\Models\Social;
use Illuminate\Database\Eloquent\Model;

trait Socialable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function socials()
    {
        return $this->morphMany(Social::class, 'socialable');
    }

    /**
     * @param string $name
     * @return Model|\Illuminate\Database\Eloquent\Relations\MorphMany|object|null
     */
    public function getSocial(string $name)
    {
        $this->load('socials');

        return $this->socials()->where('type', $name)->first();
    }

    /**
     * @param string $name
     * @param string $value
     * @return string|Model|\Illuminate\Database\Eloquent\Relations\MorphMany|object|null
     */
    public function getSocialValue(string $name, string $value)
    {
        if (!isset($this->socials)) {
            $this->load('socials');
        }

        $oSocial = $this->socials->where('type', $name)->first();
        return !is_null($oSocial) ? $oSocial->{$value} ?? '' : '';
    }
}
