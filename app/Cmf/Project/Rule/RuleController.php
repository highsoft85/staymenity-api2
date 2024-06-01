<?php

declare(strict_types=1);

namespace App\Cmf\Project\Rule;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Models\Rule;

class RuleController extends MainController
{
    use RuleSettingsTrait;
    use RuleCustomTrait;
    use RuleThisTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Host Rules';

    /**
     * Имя сущности
     */
    const NAME = 'rule';

    /**
     * Иконка
     */
    const ICON = 'icon-shield';

    /**
     * Модель сущности
     */
    public $class = \App\Models\Rule::class;

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    public $with = ['listings'];

    /**
     * @var array
     */
    public $indexComponent = [
        TableParameter::INDEX_STATE => false,
        TableParameter::INDEX_SHOW => false,
        TableParameter::INDEX_SEARCH => false,
        TableParameter::INDEX_IMAGE => false,
    ];

    /**
     * @var array
     */
    protected $aOrderBy = [
        'column' => 'priority',
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
        'title' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Title',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::REQUIRED => true,
            FieldParameter::DELETE_TITLE => 'Rule',
        ],
        'icon' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Icon',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::REQUIRED => false,
        ],
        'listings' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Listings',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::HIDDEN => true,
            FieldParameter::TABLE_TITLE => 'Listings <i data-tippy-popover data-tippy-content="Active / All" class="fa fa-question-circle-o" aria-hidden="true"></i>',
        ],
        'priority' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Priority',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::REQUIRED => true,
            FieldParameter::LENGTH => 4,
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
