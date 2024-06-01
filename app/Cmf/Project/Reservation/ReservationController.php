<?php

declare(strict_types=1);

namespace App\Cmf\Project\Reservation;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Cmf\Project\Listing\ListingController;
use App\Cmf\Project\User\UserController;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;

class ReservationController extends MainController
{
    use ReservationSettingsTrait;
    use ReservationCustomTrait;
    use ReservationThisTrait;
    use ReservationExcelExportTrait;
    use ReservationPagesTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Reservations';

    /**
     * Имя сущности
     */
    const NAME = 'reservation';

    /**
     * Иконка
     */
    const ICON = 'icon-pin';

    /**
     * Модель сущности
     */
    public $class = \App\Models\Reservation::class;

    const PAGE_ACTIVE = 'active';
    const PAGE_CANCELLED = 'cancelled';
    const PAGE_PASSED = 'passed';
    const PAGE_PROCESS = 'process';
    const PAGE_FUTURE = 'future';
    const PAGE_HOSTFULLY = 'hostfully';

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    public $with = [
        'userTrashed', 'userTrashed.modelImages',
        'listingTrashed', 'listingTrashed.modelImages', 'listingTrashed.user',
        'payment',
    ];

    /**
     * @var array
     */
    public $indexComponent = [
        TableParameter::INDEX_STATE => true,
        TableParameter::INDEX_SHOW => false,
        TableParameter::INDEX_SEARCH => false,
        TableParameter::INDEX_CREATE => false,
        TableParameter::INDEX_IMAGE => false,
        TableParameter::INDEX_DELETE_DISABLED => true,
        TableParameter::INDEX_EDIT_DISABLED => true,
        TableParameter::INDEX_EDIT => false,
        TableParameter::INDEX_DELETE => false,
        TableParameter::INDEX_EXPORT => true,
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
     * Validation Reservations
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
            FieldParameter::TITLE => 'User',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::DELETE_TITLE => 'Reservation',
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
        'listing' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'Listing',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::RELATIONSHIP => parent::RELATIONSHIP_BELONGS_TO,
            FieldParameter::VALUES => Listing::class,
            FieldParameter::ORDER => [
                FieldParameter::ORDER_METHOD => 'orderBy',
                FieldParameter::ORDER_BY => 'title',
            ],
            FieldParameter::ALIAS => 'searchName',
            FieldParameter::REQUIRED => true,
            FieldParameter::TABLE_TITLE => '<i class="' . ListingController::ICON . '"></i>',
        ],
        'price' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Price',
            FieldParameter::IN_TABLE => 3,
            FieldParameter::TABLE_TITLE => 'Price <i data-tippy-popover data-tippy-content="Price / Service Fee / Total Price" class="fa fa-question-circle-o" aria-hidden="true"></i>',
        ],
        'created_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Created',
            FieldParameter::DATETIME => true,
            FieldParameter::IN_TABLE => 3,
        ],
        'free_cancellation_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Free Cancellation',
            FieldParameter::DATETIME => true,
        ],
        'beginning_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Beginning',
            FieldParameter::DATETIME => true,
        ],
        'passed_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Passed',
            FieldParameter::DATETIME => true,
        ],
        'transfer_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Transfer',
            FieldParameter::DATETIME => true,
        ],
        'payout_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Payout',
            FieldParameter::DATETIME => true,
        ],
        'date' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Date',
            FieldParameter::DATETIME => true,
            FieldParameter::IN_TABLE => 3,
        ],
//        'start_at' => [
//            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
//            FieldParameter::TITLE => 'Started',
//            FieldParameter::REQUIRED => true,
//            FieldParameter::DATETIME => true,
//            FieldParameter::IN_TABLE => 3,
//        ],
//        'finish_at' => [
//            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
//            FieldParameter::TITLE => 'Finished',
//            FieldParameter::REQUIRED => true,
//            FieldParameter::DATETIME => true,
//            FieldParameter::IN_TABLE => 3,
//        ],
        'message' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXTAREA,
            FieldParameter::TITLE => 'Message',
            FieldParameter::LIMIT => 500,
        ],
        'guests_size' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Guests size',
            FieldParameter::REQUIRED => true,
            FieldParameter::LENGTH => 2,
        ],
        'code' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Code',
            FieldParameter::IN_TABLE => 2,
        ],
        'sync' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Sync',
            FieldParameter::IN_TABLE => 5,
            FieldParameter::MODES => [
                MainController::MODE_DEVELOPER,
            ],
        ],
        'status' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'Status',
            FieldParameter::IN_TABLE => 5,
        ],
        'action_cancel' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Cancel',
            FieldParameter::IN_TABLE => 2,
        ],
        'action_transfer' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Transfer',
            FieldParameter::IN_TABLE => 2,
        ],
        'action_payout' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Payout',
            FieldParameter::IN_TABLE => 2,
        ],
    ];
}
