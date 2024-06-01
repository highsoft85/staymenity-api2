<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Statusable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Types
 * @package App\Models
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string|null $name_hostfully
 * @property string $title
 * @property string|null $description
 * @property int $status
 *
 * * * METHODS
 * @method static other()
 * @see \App\Models\Type::scopeOther()
 *
 * @method static active()
 * @see \App\Models\Type::scopeActive()
 *
 * @method static ordered()
 * @see \App\Models\Type::scopeOrdered()
 *
 */
class Type extends Model
{
    use Statusable;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'name_hostfully', 'title', 'description', 'priority', 'status',
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const TYPE_LISTING = 'listing';

    /**
     * сущность с name=other означает, что название своё
     */
    const NAME_OTHER = 'other';

    /**
     * @param string $value
     */
    public function setTitleAttribute(string $value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['name'] = Str::slug($value);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('priority', 'desc');
    }

    /**
     * @return bool
     */
    public function isOther()
    {
        return $this->name === self::NAME_OTHER;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOther(Builder $query): Builder
    {
        return $query->where('name', self::NAME_OTHER);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotOther(Builder $query): Builder
    {
        return $query->where('name', '<>', self::NAME_OTHER);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function listings()
    {
        return $this->hasMany(Listing::class, 'type_id');
    }
}
