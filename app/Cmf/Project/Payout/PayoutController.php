<?php

declare(strict_types=1);

namespace App\Cmf\Project\Payout;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Cmf\Project\Listing\ListingController;
use App\Cmf\Project\User\UserController;
use App\Models\Listing;
use App\Models\Payout;
use App\Models\User;

class PayoutController extends MainController
{
    use PayoutSettingsTrait;
    use PayoutCustomTrait;
    use PayoutThisTrait;
    use PayoutExcelExportTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Payouts';

    /**
     * Имя сущности
     */
    const NAME = 'payout';

    /**
     * Иконка
     */
    const ICON = 'icon-action-undo';

    /**
     * Модель сущности
     */
    public $class = \App\Models\Payout::class;

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    public $with = [
        'userTrashed', 'userTrashed.modelImages',
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
        TableParameter::INDEX_EDIT => false,
        TableParameter::INDEX_DELETE => false,
        TableParameter::INDEX_DELETE_DISABLED => true,
        TableParameter::INDEX_EDIT_DISABLED => true,
        //TableParameter::INDEX_EXPORT => true,
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
     * Validation Payouts
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
            FieldParameter::DELETE_TITLE => 'Payout',
            FieldParameter::RELATIONSHIP => parent::RELATIONSHIP_BELONGS_TO,
            FieldParameter::VALUES => User::class,
            FieldParameter::ORDER => [
                FieldParameter::ORDER_METHOD => 'orderBy',
                FieldParameter::ORDER_BY => 'first_name',
            ],
            FieldParameter::ALIAS => 'searchName',
            FieldParameter::REQUIRED => true,
            //FieldParameter::TABLE_TITLE => '<i class="' . UserController::ICON . '"></i>',
        ],
        'amount' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Amount',
            FieldParameter::IN_TABLE => 2,
        ],
        'reservation' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Reservation',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::HIDDEN => true,
        ],
        'created_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Created',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::HIDDEN => true,
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
