<?php

declare(strict_types=1);

namespace App\Cmf\Project\Role;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TabParameter;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleController extends MainController
{
    use RoleSettingsTrait;
    use RoleCustomTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Role';

    /**
     * Имя сущности
     */
    const NAME = 'role';

    /**
     * Иконка
     */
    const ICON = 'icon-list';

    /**
     * Модель сущности
     */
    public $class = Role::class;

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    public $with = [];

    /**
     * Сортировка с учетом сессии, например ['column' => 'created_at', 'type' => 'desc']
     *
     * @var array
     */
    protected $aOrderBy = [
        'column' => 'title',
        'type' => 'asc',
    ];

    /**
     * @return array
     */
    public static function reject()
    {
        return [
            //User::ROLE_ADMIN,
        ];
    }

    /**
     * @var int
     */
    protected $tableLimit = 30;

    /**
     * @var array
     */
    public $indexComponent = [];

    /**
     * @var array
     */
    public $image = [];

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
            'title' => ['required', 'max:255'],
        ],
        'update' => [
            'title' => ['required', 'max:255'],
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
            FieldParameter::DELETE_TITLE => 'Role',
            FieldParameter::REQUIRED => true,
        ],
        'status' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CHECKBOX,
            FieldParameter::TITLE => 'Status',
            FieldParameter::TITLE_FORM => 'Active',
            FieldParameter::IN_TABLE => 3,
            FieldParameter::DEFAULT => true,
        ],
    ];
}
