<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Statusable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $type
 * @property int $sociable_id
 * @property string $isociable_type
 * @property string $options
 * @property string $value
 * @property string $description
 * @property int $is_main
 * @property int $priority
 * @property int $status
 *
 * * * METHODS
 * @method static active()
 * @method static ordered()
 */
class Social extends Model
{
    use Statusable;

    /**
     *
     */
    const STATUS_NOT_ACTIVE = 0;

    /**
     *
     */
    const STATUS_ACTIVE = 1;

    const TYPE_VK = 'vkontakte';
    const TYPE_WHATSAPP = 'whatsapp';
    const TYPE_YOUTUBE = 'youtube';
    const TYPE_TELEGRAM = 'telegram';
    const TYPE_ODNOKLASSNIKI = 'odnoklassniki';
    const TYPE_FACEBOOK = 'facebook';
    const TYPE_TWITTER = 'twitter';
    const TYPE_INSTAGRAM = 'instagram';
    const TYPE_VIBER = 'viber';
    const TYPE_GMAIL = 'gmail';
    const TYPE_SITE = 'site';
    const TYPE_LINKEDIN = 'linkedin';
    const TYPE_TIKTOK = 'tiktok';

    /**
     * @var string
     */
    protected $table = 'socials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'sociable_id', 'sociable_type', 'options', 'value', 'description', 'is_main', 'priority', 'status',
    ];

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statuses = [
        self::STATUS_NOT_ACTIVE => 'Not active',
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
     * @var array
     */
    public $types = [
        self::TYPE_VK => 'VK',
        self::TYPE_INSTAGRAM => 'Instagram',
        self::TYPE_WHATSAPP => 'WhatsApp',
        self::TYPE_YOUTUBE => 'YouTube',
        self::TYPE_TELEGRAM => 'Telegram',
        self::TYPE_ODNOKLASSNIKI => 'OK',
        self::TYPE_FACEBOOK => 'Facebook',
        self::TYPE_TWITTER => 'Twitter',
        self::TYPE_VIBER => 'Viber',
        self::TYPE_GMAIL => 'Gmail',
        self::TYPE_SITE => 'Site',
        self::TYPE_LINKEDIN => 'LinkedIn',
        self::TYPE_TIKTOK => 'TikTok',
    ];

    public static function types()
    {
        return (new self())->types;
    }

    public static function typeIcons()
    {
        return (new self())->typeIcons;
    }

    /**
     * The status attributes for model
     *
     * @var array
     */
    public $typeIcons = [
        self::TYPE_VK => [
            'class' => 'fa fa-vk',
            'placeholder' => 'https://vk.com/',
            'url' => 'https://vk.com/',
        ],
        self::TYPE_INSTAGRAM => [
            'class' => 'fa fa-instagram',
            'placeholder' => 'https://www.instagram.com/',
            'url' => 'https://www.instagram.com/',
        ],
        self::TYPE_WHATSAPP => [
            'class' => 'fa fa-whatsapp',
            'placeholder' => '+1 (xxx) xxx-xx-xx',
            'options' => [
                'type' => 'phone',
            ],
        ],
        self::TYPE_YOUTUBE => [
            'class' => 'fa fa-youtube-play',
            'placeholder' => 'https://youtube.com/',
            'url' => 'https://youtube.com/',
        ],
        self::TYPE_GMAIL => [
            'class' => 'fa fa-google-plus',
            'placeholder' => '@gmail.com',
        ],
        self::TYPE_TELEGRAM => [
            'class' => 'fa fa-telegram',
            'placeholder' => '+1 (xxx) xxx-xx-xx',
            'options' => [
                'type' => 'phone',
            ],
        ],
        self::TYPE_ODNOKLASSNIKI => [
            'class' => 'fa fa-odnoklassniki',
            'placeholder' => 'https://odnoklassniki.com/',
        ],
        self::TYPE_FACEBOOK => [
            'class' => 'fa fa-facebook',
            'placeholder' => 'https://facebook.com/',
            'url' => 'https://facebook.com/',
        ],
        self::TYPE_TWITTER => [
            'class' => 'fa fa-twitter',
            'placeholder' => '@',
            'options' => [
                'type' => 'prefix',
                'prefix' => '@'
            ],
            'url' => 'https://twitter.com/'
        ],
        self::TYPE_VIBER => [
            'class' => 'fa fa-viber',
            'placeholder' => '+1 (xxx) xxx-xx-xx',
            'options' => [
                'type' => 'phone',
            ],
        ],
        self::TYPE_SITE => [
            'class' => 'fa fa-home',
            'placeholder' => 'https://',
        ],
        self::TYPE_LINKEDIN => [
            'class' => 'fa fa-linkedin',
            'placeholder' => 'https://',
            'url' => 'https://linkedin.com/',
        ],
        self::TYPE_TIKTOK => [
            'class' => 'fa fa-tiktok',
            'placeholder' => 'https://',
            'url' => 'https://www.tiktok.com/',
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
}
