<?php

declare(strict_types=1);

namespace App\Cmf\Project\OptionSystemValue;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OptionSystemValueController extends MainController
{
    use OptionSystemValueSettingsTrait;
    use ImageableTrait;
    use OptionSystemValueCustomTrait;
    use OptionSystemValueThisTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Options';

    /**
     * Имя сущности
     */
    const NAME = 'option_system_value';

    /**
     * Иконка
     */
    const ICON = 'icon-options-vertical';

    /**
     * Модель сущности
     */
    public $class = \App\Models\SystemOptionValue::class;

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
        TableParameter::INDEX_DELETE_DISABLED => true,
        //TableParameter::INDEX_DELETE => true,
    ];

    /**
     * Validation name return
     * @var array
     */
    public $attributes = [
        'name' => 'name',
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
        'option' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'Option',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::DELETE_TITLE => 'Option',
            FieldParameter::RELATIONSHIP => parent::RELATIONSHIP_BELONGS_TO,
            FieldParameter::VALUES => Option::class,
            FieldParameter::ORDER => [
                FieldParameter::ORDER_METHOD => 'orderBy',
                FieldParameter::ORDER_BY => 'name',
            ],
            FieldParameter::ALIAS => 'searchName',
            FieldParameter::REQUIRED => true,
            FieldParameter::HIDDEN => true,
        ],
        'value' => [
            FieldParameter::TYPE => 'by_option',
            FieldParameter::TITLE => 'Value',
            FieldParameter::LIMIT => 1000,
            FieldParameter::REQUIRED => true,
            FieldParameter::IN_TABLE => 2,
        ],
    ];
}
