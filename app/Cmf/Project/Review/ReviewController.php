<?php

declare(strict_types=1);

namespace App\Cmf\Project\Review;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Cmf\Project\Listing\ListingController;
use App\Cmf\Project\User\UserController;
use App\Models\Listing;
use App\Models\Review;
use App\Models\User;

class ReviewController extends MainController
{
    use ReviewSettingsTrait;
    use ReviewCustomTrait;
    use ReviewThisTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Reviews';

    /**
     * Имя сущности
     */
    const NAME = 'review';

    /**
     * Иконка
     */
    const ICON = 'icon-speech';

    /**
     * Модель сущности
     */
    public $class = \App\Models\Review::class;

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    public $with = [
        'modelTrashed', 'modelTrashed.modelImages',
        'userTrashed', 'userTrashed.modelImages',
    ];

    /**
     * @var array
     */
    public $indexComponent = [
        TableParameter::INDEX_STATE => false,
        TableParameter::INDEX_SHOW => true,
        TableParameter::INDEX_SEARCH => false,
        TableParameter::INDEX_CREATE => false,
        TableParameter::INDEX_IMAGE => false,
        TableParameter::INDEX_DELETE => false,
        TableParameter::INDEX_EDIT => false,
    ];

    /**
     * @var array
     */
    protected $aOrderBy = [
        'column' => 'published_at',
        'type' => 'desc',
    ];

    /**
     * @var int
     */
    protected $tableLimit = 30;

    /**
     * @param object|null $model
     * @return array
     */
    public function rules($model = null)
    {
        return $this->rules;
    }

    /**
     * Validation Reviews
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
        'show' => [
            TabParameter::TAB_MAIN => TabParameter::TAB_MAIN_CONTENT,
        ],
    ];

    /**
     * @var array
     */
    public $fields = [
        'user' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'From',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::DELETE_TITLE => 'Review',
            FieldParameter::RELATIONSHIP => parent::RELATIONSHIP_BELONGS_TO,
            FieldParameter::VALUES => User::class,
            FieldParameter::ORDER => [
                FieldParameter::ORDER_METHOD => 'orderBy',
                FieldParameter::ORDER_BY => 'first_name',
            ],
            FieldParameter::ALIAS => 'searchName',
            FieldParameter::REQUIRED => true,
        ],
        'model' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'To',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::HIDDEN => true,
        ],
        'description' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXTAREA,
            FieldParameter::TITLE => 'Description',
            FieldParameter::LIMIT => 1000,
            FieldParameter::REQUIRED => true,
            FieldParameter::IN_TABLE => 3,
        ],
        'rating' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Rating',
            FieldParameter::IN_TABLE => 3,
            FieldParameter::HIDDEN => true,
        ],
        'published_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Published',
            FieldParameter::REQUIRED => true,
            FieldParameter::DATETIME => true,
            FieldParameter::IN_TABLE => 3,
        ],
        'banned_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Banned',
            FieldParameter::REQUIRED => false,
            FieldParameter::DATETIME => true,
            FieldParameter::HIDDEN => true,
            FieldParameter::IN_TABLE => 3,
        ],
        'status' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'Status',
            FieldParameter::IN_TABLE => 5,
        ],
    ];
}
