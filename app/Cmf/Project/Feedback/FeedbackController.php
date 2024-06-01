<?php

declare(strict_types=1);

namespace App\Cmf\Project\Feedback;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Cmf\Project\Listing\ListingController;
use App\Cmf\Project\User\UserController;
use App\Models\Listing;
use App\Models\Feedback;
use App\Models\User;

class FeedbackController extends MainController
{
    use FeedbackSettingsTrait;
    use FeedbackCustomTrait;
    use FeedbackThisTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Feedbacks';

    /**
     * Имя сущности
     */
    const NAME = 'feedback';

    /**
     * Иконка
     */
    const ICON = 'icon-feed';

    /**
     * Модель сущности
     */
    public $class = \App\Models\Feedback::class;

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    public $with = [];

    /**
     * @var array
     */
    public $cache = [
        'cmf:feedback:count',
    ];

    /**
     * @var array
     */
    public $indexComponent = [
        TableParameter::INDEX_STATE => false,
        TableParameter::INDEX_SHOW => false,
        TableParameter::INDEX_SEARCH => false,
        TableParameter::INDEX_CREATE => false,
        TableParameter::INDEX_IMAGE => false,
        TableParameter::INDEX_DELETE_DISABLED => false,
        TableParameter::INDEX_EDIT_DISABLED => false,
    ];

    /**
     * @var array
     */
    protected $aOrderBy = [
        'column' => 'created_at',
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
     * Validation Feedbacks
     * @var array
     */
    public $rules = [
        'store' => [
            // 'name' => ['required', 'max:255'],
            //'description' => ['required'],
        ],
        'update' => [
            // 'name' => ['required', 'max:255'],
            //'description' => ['required'],
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
        'name' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Name',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::REQUIRED => true,
            FieldParameter::DELETE_TITLE => 'Feedback',
        ],
        'email' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Email',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::REQUIRED => true,
        ],
        'message' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXTAREA,
            FieldParameter::TITLE => 'Message',
            FieldParameter::LIMIT => 1000,
            FieldParameter::REQUIRED => true,
            FieldParameter::IN_TABLE => 1,
        ],
        'status' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CHECKBOX,
            FieldParameter::TITLE => 'Status',
            FieldParameter::TITLE_FORM => 'Active',
            FieldParameter::IN_TABLE => 5,
            FieldParameter::DEFAULT => true,
        ],
    ];
}
