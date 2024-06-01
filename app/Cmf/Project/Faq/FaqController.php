<?php

declare(strict_types=1);

namespace App\Cmf\Project\Faq;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Cmf\Project\Listing\ListingController;
use App\Cmf\Project\User\UserController;
use App\Models\Listing;
use App\Models\Faq;
use App\Models\User;

class FaqController extends MainController
{
    use FaqSettingsTrait;
    use FaqCustomTrait;
    use FaqThisTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'FAQ';

    /**
     * Имя сущности
     */
    const NAME = 'faq';

    /**
     * Иконка
     */
    const ICON = 'icon-info';

    /**
     * Модель сущности
     */
    public $class = \App\Models\Faq::class;

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
        TableParameter::INDEX_CREATE => true,
        TableParameter::INDEX_IMAGE => false,
        TableParameter::INDEX_DELETE_DISABLED => false,
        TableParameter::INDEX_EDIT_DISABLED => false,
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
     * Validation Faqs
     * @var array
     */
    public $rules = [
        'store' => [
            // 'name' => ['required', 'max:255'],
            'description' => ['required'],
        ],
        'update' => [
            // 'name' => ['required', 'max:255'],
            'description' => ['required'],
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
            FieldParameter::DELETE_TITLE => 'Faq',
        ],
        'description' => [
            FieldParameter::TYPE => parent::DATA_TYPE_MARKDOWN,
            FieldParameter::TITLE => 'Description',
            FieldParameter::LIMIT => 1000,
            FieldParameter::REQUIRED => true,
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
