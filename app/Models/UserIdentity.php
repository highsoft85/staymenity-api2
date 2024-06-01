<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\UserIdentityStatusesTrait;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;
use App\Services\Image\Path\ImagePathService;
use App\Services\Modelable\Imageable;
use App\Services\Modelable\Jsonable;
use App\Services\Modelable\Statusable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class UserIdentity
 * @package App\Models
 *
 * @property int $id
 * @property string $type
 * @property int $user_id
 * @property string $autohost_reservation_id
 * @property string|null $errors
 *
 * @property string|null $image_front_response
 * @property string|null $image_back_response
 * @property string|null $image_selfie_response
 *
 * @property int $image_front_status
 * @property int $image_back_status
 * @property int $image_selfie_status
 *
 * @property int $status
 * @property Carbon $created_at
 *
 *
 * @property User|null $user
 * @property User|null $userTrashed
 *
 * @property array $errorsArray
 * @property array|null $errorsObject
 *
 * @property string|null $frontImageBase64
 * @property string|null $backImageBase64
 * @property string|null $selfieImageBase64
 *
 * @property string|null $frontImageOriginal
 * @property string|null $backImageOriginal
 * @property string|null $selfieImageOriginal
 *
 * @property array $imageFrontResponseArray
 * @see UserIdentity::getImageFrontResponseArrayAttribute()
 *
 * @property array $imageBackResponseArray
 * @see UserIdentity::getImageBackResponseArrayAttribute()
 *
 * @property array $imageSelfieResponseArray
 * @see UserIdentity::getImageSelfieResponseArrayAttribute()
 *
 */
class UserIdentity extends Model
{
    use Imageable;
    use Statusable;
    use UserIdentityStatusesTrait;
    use Jsonable;

    const TYPE_PASSPORT = 'passport';
    const TYPE_DRIVERS = 'drivers';
    const TYPE_ID = 'id';
    const TYPE_DEFAULT = 'default';

    const STEP_FRONT = 'front';
    const STEP_BACK = 'back';
    const STEP_SELFIE = 'selfie';

    const REV_ID_LOCAL_START = 'local-';

    const STATUS_NOT_VERIFIED = 0;
    const STATUS_PENDING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = 3;
    const STATUS_QUEUED = 4;

    /**
     * @var string
     */
    protected $table = 'user_identities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'user_id',
        'autohost_reservation_id',
        'errors',
        'image_front_response', 'image_front_status',
        'image_back_response', 'image_back_status',
        'image_selfie_response', 'image_selfie_status',
        'status',
    ];

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_SUCCESS,
        ]);
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function userTrashed()
    {
        return $this->user()->withTrashed();
    }

    /**
     * @return string|null
     */
    public function getFrontImageBase64Attribute()
    {
        $image = $this->imagesIdentityFront()->first();
        $type = $this->getImageTypeModel();
        return (new ImagePathService())->imageBase64($type, $image, ImageType::IDENTITY_TYPE_FRONT);
    }

    /**
     * @return string|null
     */
    public function getBackImageBase64Attribute()
    {
        $image = $this->imagesIdentityBack()->first();
        $type = $this->getImageTypeModel();
        return (new ImagePathService())->imageBase64($type, $image, ImageType::IDENTITY_TYPE_BACK);
    }

    /**
     * @return string|null
     */
    public function getSelfieImageBase64Attribute()
    {
        $image = $this->imagesIdentitySelfie()->first();
        $type = $this->getImageTypeModel();
        return (new ImagePathService())->imageBase64($type, $image, ImageType::IDENTITY_TYPE_SELFIE);
    }

     /**
     * @return string|null
     */
    public function getFrontImageOriginalAttribute()
    {
        $image = $this->imagesIdentityFront()->first();
        $type = $this->getImageTypeModel();
        return (new ImagePathService())->image($type, ImageSize::ORIGINAL, $image, ImageType::IDENTITY_TYPE_FRONT);
    }

    /**
     * @return string|null
     */
    public function getBackImageOriginalAttribute()
    {
        $image = $this->imagesIdentityBack()->first();
        $type = $this->getImageTypeModel();
        return (new ImagePathService())->image($type, ImageSize::ORIGINAL, $image, ImageType::IDENTITY_TYPE_BACK);
    }

    /**
     * @return string|null
     */
    public function getSelfieImageOriginalAttribute()
    {
        $image = $this->imagesIdentitySelfie()->first();
        $type = $this->getImageTypeModel();
        return (new ImagePathService())->image($type, ImageSize::ORIGINAL, $image, ImageType::IDENTITY_TYPE_SELFIE);
    }

    /**
     * @return array
     */
    public function getErrorsArrayAttribute()
    {
        $errors = !is_null($this->errors) ? $this->errors : '{}';
        return json_decode($errors, true);
    }

    /**
     * @return array|null
     */
    public function getErrorsObjectAttribute()
    {
        if (is_null($this->errors)) {
            return null;
        }
        return json_decode($this->errors, true);
    }

    /**
     * @return array
     */
    public function getImageFrontResponseArrayAttribute()
    {
        return $this->getJsonToArray('image_front_response');
    }

    /**
     * @return array
     */
    public function getImageBackResponseArrayAttribute()
    {
        return $this->getJsonToArray('image_back_response');
    }

    /**
     * @return array
     */
    public function getImageSelfieResponseArrayAttribute()
    {
        return $this->getJsonToArray('image_selfie_response');
    }

    /**
     * @return bool
     */
    public function isLocalKey()
    {
        return Str::startsWith($this->autohost_reservation_id, self::REV_ID_LOCAL_START);
    }
}
