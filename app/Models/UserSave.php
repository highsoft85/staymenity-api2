<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class UserSaves
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $title
 * @property int $status
 *
 *
 * @property Listing[] $listings
 */
class UserSave extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_saves';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'title', 'status',
    ];

    /**
     * Статусы активности
     */
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @param string $value
     */
    public function setTitleAttribute(string $value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['name'] = Str::slug($value);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('created_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function listings()
    {
        return $this
            ->belongsToMany(Listing::class, 'user_favorites', 'user_save_id', UserFavorite::MORPH_ID)
            ->where(UserFavorite::MORPH_TYPE, Listing::class);
    }
}
