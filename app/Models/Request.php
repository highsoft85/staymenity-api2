<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Jsonable;
use App\Services\Modelable\Statusable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Request
 * @package App\Models
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $email
 * @property string|null $message
 * @property string|null $external
 * @property int $status
 *
 *
 * @property Carbon|null $created_at
 * @property array $externalArray
 */
class Request extends Model
{
    use Statusable;
    use Jsonable;

    /**
     * @var string
     */
    protected $table = 'requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'email', 'message', 'external', 'status',
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_UNREAD = 2;

    const TYPE_HOST = 'host';

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
    public function scopeActive(Builder $query)
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
        return $query->orderBy('created_at');
    }

    /**
     * @return array
     */
    public function getExternalArrayAttribute()
    {
        $data = !is_null($this->external) ? $this->external : '{}';
        return json_decode($data, true);
    }

    /**
     * @param array $value
     */
    public function setExternalAttribute(array $value)
    {
        $this->attributes['external'] = json_encode($value);
    }
}
