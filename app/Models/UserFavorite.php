<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Class UserFavorite
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_save_id
 * @property int $favoriteable_id
 * @property string $favoriteable_type
 * @property User|Listing|null $favoriteable
 *
 * @method static userFavorited($oUser)
 * @method static listingFavorited($oUser)
 */
class UserFavorite extends Model
{
    /**
     *
     */
    const MORPH = 'favoriteable';
    const MORPH_ID = 'favoriteable_id';
    const MORPH_TYPE = 'favoriteable_type';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_favorites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_save_id', self::MORPH_ID, self::MORPH_TYPE,
    ];

    /**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favoriteable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userSave()
    {
        return $this->belongsTo(UserSave::class, 'user_save_id');
    }

    /**
     * @param Builder $query
     * @param User $oUser
     * @return Builder
     */
    public function scopeUserFavorited(Builder $query, User $oUser)
    {
        return $query->where(self::MORPH_TYPE, User::class)->where(self::MORPH_ID, $oUser->id);
    }

    /**
     * @param Builder $query
     * @param Listing $oListing
     * @return Builder
     */
    public function scopeListingFavorited(Builder $query, Listing $oListing)
    {
        return $query->where(self::MORPH_TYPE, Listing::class)->where(self::MORPH_ID, $oListing->id);
    }
}
