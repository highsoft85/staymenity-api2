<?php

declare(strict_types=1);

namespace App\Models;

use App\Cmf\Core\MainController;
use App\Services\Modelable\Statusable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Option
 * @package App\Models
 *
 * @property int $id
 * @property string $purpose
 * @property string $title
 * @property string $description
 * @property string $placeholder
 * @property string $name
 * @property string $unit
 * @property int $type
 * @property string $tooltip
 * @property int $priority
 * @property int $status
 *
 *
 * @property SystemOptionValue|null $systemValue
 */
class Option extends Model
{
    use Statusable;

    /**
     * @var string
     */
    protected $table = 'options';

    /**
     * @var array
     */
    protected $fillable = [
        'purpose', 'name', 'title', 'description', 'placeholder', 'unit', 'type', 'tooltip',
        'priority', 'status',
    ];

    const PURPOSE_DEFAULT = 'default';
    const PURPOSE_SYSTEM = 'system';
    const PURPOSE_LISTING = 'listing';

    const NAME_PRIVACY = 'privacy';
    const NAME_TERMS = 'terms';
    const NAME_CONTACTS = 'contacts';

    const NAME_SOCIAL_FACEBOOK = 'social_facebook';
    const NAME_SOCIAL_TWITTER = 'social_twitter';
    const NAME_SOCIAL_INSTAGRAM = 'social_instagram';

    const TYPE_TEXT = MainController::DATA_TYPE_TEXT;
    const TYPE_MARKDOWN = MainController::DATA_TYPE_MARKDOWN;

    /**
     *
     */
    const STATUS_NOT_ACTIVE = 0;

    /**
     *
     */
    const STATUS_ACTIVE = 1;

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
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
        return $query->orderBy('priority', 'desc');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePurposeSystem(Builder $query): Builder
    {
        return $query->where('purpose', self::PURPOSE_SYSTEM);
    }

    /**
     * @return string
     */
    public function getSearchNameAttribute()
    {
        return $this->title;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function systemValue()
    {
        return $this->hasOne(SystemOptionValue::class, 'option_id');
    }
}
