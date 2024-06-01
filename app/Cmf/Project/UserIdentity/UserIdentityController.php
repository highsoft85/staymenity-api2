<?php

declare(strict_types=1);

namespace App\Cmf\Project\UserIdentity;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Cmf\Project\User\UserController;
use App\Models\Option;
use App\Models\User;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserIdentityController extends MainController
{
    use UserIdentitySettingsTrait;
    use ImageableTrait;
    use UserIdentityCustomTrait;
    use UserIdentityThisTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'User Identities';

    /**
     * Имя сущности
     */
    const NAME = 'user_identity';

    /**
     * Иконка
     */
    const ICON = 'icon-shield';

    /**
     * Модель сущности
     */
    public $class = \App\Models\UserIdentity::class;

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    public $with = [];

    /**
     * @var array
     */
    public $indexComponent = [
        TableParameter::INDEX_STATE => false,
        TableParameter::INDEX_SHOW => false,
        TableParameter::INDEX_SEARCH => false,
        TableParameter::INDEX_IMAGE => false,
        TableParameter::INDEX_CREATE => false,
        TableParameter::INDEX_DELETE => true,
        TableParameter::INDEX_EDIT => true,
        TableParameter::INDEX_EDIT_DISABLED => true,
    ];

    /**
     * Validation name return
     * @var array
     */
    public $attributes = [
        'name' => 'name',
    ];

    protected $aOrderBy = [
        'column' => 'created_at',
        'type' => 'desc',
    ];

    /**
     * @param object|null $model
     * @return array
     */
    public function rules($model = null)
    {
        return $this->rules;
    }

    /**
     * @var array
     */
    public $image = [
        ImageType::IDENTITY_TYPE_FRONT => [
            'with_main' => true,
            'unique' => true,
            'filters' => [
                ImageSize::SQUARE => ImageSize::IMAGE_SIZE_SQUARE_CONTENT,
                ImageSize::XL => ImageSize::IMAGE_SIZE_XL_CONTENT,
            ],
        ],
        ImageType::IDENTITY_TYPE_BACK => [
            'with_main' => true,
            'unique' => true,
            'filters' => [
                ImageSize::SQUARE => ImageSize::IMAGE_SIZE_SQUARE_CONTENT,
                ImageSize::XL => ImageSize::IMAGE_SIZE_XL_CONTENT,
            ],
        ],
        ImageType::IDENTITY_TYPE_SELFIE => [
            'with_main' => true,
            'unique' => true,
            'filters' => [
                ImageSize::SQUARE => ImageSize::IMAGE_SIZE_SQUARE_CONTENT,
                ImageSize::XL => ImageSize::IMAGE_SIZE_XL_CONTENT,
            ],
        ],
    ];

    /**
     * Validation rules
     * @var array
     */
    public $rules = [
        'store' => [
            // 'name' => ['required', 'max:255'],
            //'password' => ['required', 'confirmed', 'max:255'],
        ],
        'update' => [
            // 'name' => ['required', 'max:255'],
            //'password' => ['confirmed', 'max:255'],
        ],
    ];

    /**
     * @var array
     */
    public $tabs = [
        'edit' => [
            TabParameter::TAB_MAIN => TabParameter::TAB_MAIN_CONTENT,
        ],
    ];

    /**
     * @var array
     */
    public $fields = [
        'user' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'User',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::DELETE_TITLE => 'Verification',
            FieldParameter::RELATIONSHIP => parent::RELATIONSHIP_BELONGS_TO,
            FieldParameter::VALUES => User::class,
            FieldParameter::ORDER => [
                FieldParameter::ORDER_METHOD => 'orderBy',
                FieldParameter::ORDER_BY => 'first_name',
            ],
            FieldParameter::ALIAS => 'searchName',
            FieldParameter::REQUIRED => true,
            FieldParameter::TABLE_TITLE => '<i class="' . UserController::ICON . '"></i>',
        ],
//        'type' => [
//            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
//            FieldParameter::TITLE => 'Type',
//            FieldParameter::IN_TABLE => 2,
//            FieldParameter::REQUIRED => true,
//        ],
//        'photos' => [
//            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
//            FieldParameter::TITLE => 'Photos',
//            FieldParameter::IN_TABLE => 2,
//            FieldParameter::REQUIRED => true,
//        ],
        'errors' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Errors',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::REQUIRED => true,
        ],
        'created_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Created',
            FieldParameter::DATETIME => true,
            FieldParameter::IN_TABLE => 3,
        ],
        'action_check' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Send',
            FieldParameter::DATETIME => true,
            FieldParameter::IN_TABLE => 3,
        ],
//        'action_verified' => [
//            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
//            FieldParameter::TITLE => 'Force Verified',
//            FieldParameter::DATETIME => true,
//            FieldParameter::IN_TABLE => 3,
//        ],
        'status' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CHECKBOX,
            FieldParameter::TITLE => 'Status',
            FieldParameter::TITLE_FORM => 'Active',
            FieldParameter::IN_TABLE => 5,
            FieldParameter::DEFAULT => true,
        ],
    ];
}
