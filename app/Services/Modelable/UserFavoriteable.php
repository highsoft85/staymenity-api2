<?php

declare(strict_types=1);

namespace App\Services\Modelable;

use App\Models\Listing;
use App\Models\User;
use App\Models\UserFavorite;
use App\Models\UserSave;

/**
 * Trait Favoriteable
 * @package App\Services\Modelable
 *
 * @property mixed $favorites
 * @property mixed $favoriteUsers
 * @property mixed $favoriteGroups
 */
trait UserFavoriteable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteUsers()
    {
        return $this
            ->belongsToMany(User::class, 'user_favorites', 'user_id', UserFavorite::MORPH_ID)
            ->where(UserFavorite::MORPH_TYPE, User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteListings()
    {
        return $this
            ->belongsToMany(Listing::class, 'user_favorites', 'user_id', UserFavorite::MORPH_ID)
            ->where(UserFavorite::MORPH_TYPE, Listing::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(UserFavorite::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favoriteables()
    {
        return $this->morphMany(UserFavorite::class, 'favoriteable');
    }

    /**
     * @param User|Listing $oItem
     * @throws \Exception
     */
    public function favoriteRemove($oItem)
    {
        if ($this->favoriteHas($oItem)) {
            $this
                ->favorites()
                ->where(UserFavorite::MORPH_ID, $oItem->id)
                ->where(UserFavorite::MORPH_TYPE, get_class($oItem))
                ->delete();
        }
    }

    /**
     * @param User|Listing $oItem
     */
    public function favoriteToggle($oItem)
    {
        if (!$this->favoriteHas($oItem)) {
            $this->favorites()->create([
                UserFavorite::MORPH_ID => $oItem->id,
                UserFavorite::MORPH_TYPE => get_class($oItem),
            ]);
        } else {
            $this
                ->favorites()
                ->where(UserFavorite::MORPH_ID, $oItem->id)
                ->where(UserFavorite::MORPH_TYPE, get_class($oItem))
                ->delete();
        }
    }

    /**
     * @param User|Listing $oItem
     */
    public function favoriteAdd($oItem)
    {
        if (!$this->favoriteHas($oItem)) {
            $this->favorites()->create([
                UserFavorite::MORPH_ID => $oItem->id,
                UserFavorite::MORPH_TYPE => get_class($oItem),
            ]);
        }
    }

    /**
     * @param User|Listing $oItem
     * @param UserSave $oUserSave
     */
    public function favoriteAddToSave($oItem, UserSave $oUserSave)
    {
        if (!$this->favoriteHasOnSave($oItem, $oUserSave)) {
            $this->favorites()->create([
                'user_save_id' => $oUserSave->id,
                UserFavorite::MORPH_ID => $oItem->id,
                UserFavorite::MORPH_TYPE => get_class($oItem),
            ]);
        }
    }

    /**
     * @param User|Listing|null $oItem
     * @return bool
     */
    public function favoriteHas($oItem)
    {
        $oHas = $this
            ->favorites()
            ->where(UserFavorite::MORPH_ID, $oItem->id)
            ->where(UserFavorite::MORPH_TYPE, get_class($oItem))
            ->first();
        return !is_null($oHas);
    }

    /**
     * @param User|Listing|null $oItem
     * @param UserSave $oUserSave
     * @return bool
     */
    public function favoriteHasOnSave($oItem, UserSave $oUserSave)
    {
        $oHas = $this
            ->favorites()
            ->where('user_save_id', $oUserSave->id)
            ->where(UserFavorite::MORPH_ID, $oItem->id)
            ->where(UserFavorite::MORPH_TYPE, get_class($oItem))
            ->first();
        return !is_null($oHas);
    }

    /**
     * @param User|Listing $oItem
     * @param UserSave $oUserSave
     * @throws \Exception
     */
    public function favoriteRemoveFromSave($oItem, UserSave $oUserSave)
    {
        if ($this->favoriteHasOnSave($oItem, $oUserSave)) {
            $this
                ->favorites()
                ->where('user_save_id', $oUserSave->id)
                ->where(UserFavorite::MORPH_ID, $oItem->id)
                ->where(UserFavorite::MORPH_TYPE, get_class($oItem))
                ->delete();
        }
    }
}
