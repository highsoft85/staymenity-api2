<?php

declare(strict_types=1);

namespace App\Cmf\Project\Option;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OptionController extends MainController
{
    use OptionSettingsTrait;
    use ImageableTrait;
    use OptionCustomTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Options';

    /**
     * Имя сущности
     */
    const NAME = 'option';

    /**
     * Иконка
     */
    const ICON = 'icon-options-vertical';

    /**
     * Модель сущности
     */
    public $class = \App\Models\Option::class;

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
    ];

    /**
     * Validation name return
     * @var array
     */
    public $attributes = [
        'name' => 'имя',
    ];

    protected $aOrderBy = [
        'column' => 'priority',
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
        'purpose' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'Purpose',
            FieldParameter::REQUIRED => true,
            FieldParameter::VALUES => [
                Option::PURPOSE_DEFAULT => 'Default',
                Option::PURPOSE_SYSTEM => 'System',
            ],
            FieldParameter::IN_TABLE => 3,
        ],
        'name' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Key',
            FieldParameter::REQUIRED => true,
        ],
        'title' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Title',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::REQUIRED => true,
        ],
        'type' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'Type',
            FieldParameter::REQUIRED => true,
            FieldParameter::VALUES => [
                parent::DATA_TYPE_TEXT => 'Text',
                parent::DATA_TYPE_MARKDOWN => 'Markdown',
            ],
            FieldParameter::IN_TABLE => 2,
        ],
        'description' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXTAREA,
            FieldParameter::TITLE => 'Description',
        ],
        'placeholder' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXTAREA,
            FieldParameter::TITLE => 'Placeholder',
        ],
        'tooltip' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXTAREA,
            FieldParameter::TITLE => 'Tooltip',
        ],
        'priority' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Priority',
            FieldParameter::DEFAULT => 0,
            FieldParameter::LENGTH => 4,
            FieldParameter::IN_TABLE => 4,
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
