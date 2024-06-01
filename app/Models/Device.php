<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Device
 * @package App\Models
 *
 * @property int $id
 * @property string $type
 * @property string $token
 */
class Device extends Model
{
    /**
     * @var string
     */
    protected $table = 'devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'token',
    ];

    const TYPE_IOS = 'ios';
    const TYPE_WEB = 'web';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeIos(Builder $query)
    {
        return $query->where('type', self::TYPE_IOS);
    }
}
