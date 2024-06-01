<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Iconable;
use App\Services\Modelable\Statusable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Rule
 * @package App\Models
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $title
 * @property string|null $description
 * @property string|null $icon
 * @property int $status
 *
 * * * MAGIC PROPERTIES
 * @property-write bool $hasRule
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\Rule::scopeActive()
 *
 * @method static ordered()
 * @see \App\Models\Rule::scopeOrdered()
 *
 * @method static other()
 * @see \App\Models\Rule::scopeOther()
 *
 */
class Rule extends Model
{
    use Statusable;
    use Iconable;

    /**
     * @var string
     */
    protected $table = 'rules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'title', 'description', 'priority', 'icon', 'status',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'listing_rule');
    }
}
