<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Statusable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Feedback
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $message
 * @property int $status
 */
class Feedback extends Model
{
    use Statusable;

    /**
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'message', 'status',
    ];

    /**
     * Статусы активности
     */
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

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
        return $query->orderBy('created_at', 'desc');
    }
}
