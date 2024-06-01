<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Faq
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property int $priority
 * @property int $status
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\Faq::scopeActive()
 *
 * @method static ordered()
 * @see \App\Models\Faq::scopeOrdered()
 *
 */
class Faq extends Model
{
    /**
     * @var string
     */
    protected $table = 'faqs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'description', 'priority', 'status',
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
}
