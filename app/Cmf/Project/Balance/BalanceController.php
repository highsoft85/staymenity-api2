<?php

declare(strict_types=1);

namespace App\Cmf\Project\Balance;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Cmf\Project\Listing\ListingController;
use App\Cmf\Project\User\UserController;
use App\Models\Listing;
use App\Models\Balance;
use App\Models\User;

class BalanceController extends MainController
{
    use BalanceSettingsTrait;
    use BalanceCustomTrait;
    use BalanceThisTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Balances';

    /**
     * Имя сущности
     */
    const NAME = 'balance';

    /**
     * Иконка
     */
    const ICON = 'icon-wallet';

    /**
     * Модель сущности
     */
    public $class = \App\Models\Balance::class;

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
        TableParameter::INDEX_CREATE => false,
        TableParameter::INDEX_IMAGE => false,
        TableParameter::INDEX_DELETE => true,
        TableParameter::INDEX_DELETE_DISABLED => true,
        TableParameter::INDEX_EDIT_DISABLED => true,
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
     * Validation Balances
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
            FieldParameter::DELETE_TITLE => 'Balance',
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
        'amount' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Amount',
            FieldParameter::REQUIRED => true,
            FieldParameter::LENGTH => 6,
            FieldParameter::IN_TABLE => 2,
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
