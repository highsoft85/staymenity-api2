<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Statusable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $type
 * @property int $imageable_id
 * @property string $imageable_type
 * @property string $options
 * @property string $filename
 * @property array|null $info
 * @property string $source
 * @property int $number
 * @property int $is_main
 * @property int $priority
 * @property int $status
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\Image::scopeActive()
 *
 * @method static ordered()
 * @see \App\Models\Image::scopeOrdered()
 *
 * @method static main()
 * @see \App\Models\Image::scopeMain()
 *
 * @method static notMain()
 * @see \App\Models\Image::scopeNotMain()
 */
class Image extends Model
{
    use Statusable;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'imageable_id', 'imageable_type', 'options', 'filename', 'info', 'source', 'is_main', 'number',
        'priority',
        'status',
    ];

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statuses = [
        self::STATUS_NOT_ACTIVE => 'Not Active',
        self::STATUS_ACTIVE => 'Active',
    ];

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statusIcons = [
        self::STATUS_NOT_ACTIVE => [
            'class' => 'badge badge-default',
        ],
        self::STATUS_ACTIVE => [
            'class' => 'badge badge-success',
        ],
    ];

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
        return $query->orderBy('priority');
    }

    /**
     * @param array $value
     */
    public function setInfoAttribute(array $value)
    {
        $this->attributes['info'] = json_encode($value);
    }

    /**
     * @param null|string $value
     * @return array
     */
    public function getInfoAttribute(?string $value)
    {
        if (is_null($value)) {
            return [];
        }
        return json_decode($value, true);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeMain(Builder $query): Builder
    {
        return $query->where('is_main', 1);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotMain(Builder $query): Builder
    {
        return $query->where('is_main', 0);
    }
}
