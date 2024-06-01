<?php

declare(strict_types=1);

namespace App\Cmf\Project\Listing;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Cmf\Project\Type\TypeController;
use App\Cmf\Project\User\UserController;
use App\Models\Type;
use App\Models\User;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;

class ListingController extends MainController
{
    use ListingSettingsTrait;
    use ImageableTrait;
    use ListingCustomTrait;
    use ListingThisTrait;
    use ListingExcelExportTrait;
    use ListingPagesTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Listings';

    /**
     * Имя сущности
     */
    const NAME = 'listing';

    /**
     * Иконка
     */
    const ICON = 'icon-home';

    /**
     * Модель сущности
     */
    public $class = \App\Models\Listing::class;

    const PAGE_ACTIVE = 'active';
    const PAGE_POPULAR = 'popular';
    const PAGE_BOOKED = 'booked';
    const PAGE_DELETED = 'deleted';

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    public $with = [
        'user', 'user.modelImages', 'modelImages',
        'reservationsActive',
        // 'reservationsFuture', 'reservationsPassed',
        'reviewsActiveOrdered', 'type',
    ];

    /**
     * Сортировка с учетом сессии, например ['column' => 'created_at', 'type' => 'desc']
     *
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
     * @var array
     */
    public $indexComponent = [
        TableParameter::INDEX_STATE => false,
        TableParameter::INDEX_SHOW => false,
        TableParameter::INDEX_SEARCH => false,
        TableParameter::INDEX_IMAGE => true,
        TableParameter::INDEX_CREATE => false,
        TableParameter::INDEX_PRIVATE_SHOW => true,
        TableParameter::INDEX_MODAL_FAST_EDIT => 'Create & Edit',
        TableParameter::INDEX_SOFT_DELETE => false,
        TableParameter::INDEX_EXPORT => true,
    ];

    /**
     * @var array
     */
    public $image = [
        ImageType::MODEL => [
            'with_main' => true,
            'unique' => false,
            'filters' => [
                ImageSize::SQUARE => ImageSize::IMAGE_SIZE_SQUARE_CONTENT,
                ImageSize::SQUARE_XL => ImageSize::IMAGE_SIZE_SQUARE_XL_CONTENT,
                ImageSize::XS => ImageSize::IMAGE_SIZE_XS_CONTENT,
                ImageSize::XL => ImageSize::IMAGE_SIZE_XL_CONTENT,
            ],
        ],
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

        ],
        'update' => [

        ],
        'upload' => [
            'id' => ['required', 'max:255'],
            'images' => ['required', 'max:5000', 'mimes:jpg,jpeg,gif,png'],
        ],
    ];

    /**
     * @var array
     */
    public $tabs = [
        'scrolling' => [
            'modes' => [
                MainController::MODE_DEVELOPER,
            ],
        ],
        'edit' => [
            TabParameter::TAB_MAIN => TabParameter::TAB_MAIN_CONTENT,
            TabParameter::TAB_IMAGES_MODEL => TabParameter::TAB_IMAGES_MODEL_CONTENT,
            TabParameter::TAB_SETTINGS => TabParameter::TAB_SETTINGS_CONTENT,
            TabParameter::TAB_LISTING_AMENITIES => TabParameter::TAB_LISTING_AMENITIES_CONTENT,
            TabParameter::TAB_LISTING_RULES => TabParameter::TAB_LISTING_RULES_CONTENT,
            TabParameter::TAB_API_DATA => TabParameter::TAB_API_DATA_CONTENT,
            TabParameter::TAB_API_LOCATION => TabParameter::TAB_API_LOCATION_CONTENT,
            TabParameter::TAB_API_DATA_SEARCH => TabParameter::TAB_API_DATA_SEARCH_CONTENT,
            TabParameter::TAB_HOSTFULLY => TabParameter::TAB_HOSTFULLY_CONTENT,
            //TabParameter::TAB_DEV_HOSTFULLY => TabParameter::TAB_DEV_HOSTFULLY_CONTENT,
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
            FieldParameter::DELETE_TITLE => 'Listing',
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
        'title' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Title',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::REQUIRED => true,
        ],
        'type' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'Type',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::RELATIONSHIP => parent::RELATIONSHIP_BELONGS_TO,
            FieldParameter::VALUES => Type::class,
            FieldParameter::ORDER => [
                FieldParameter::ORDER_METHOD => 'orderBy',
                FieldParameter::ORDER_BY => 'title',
            ],
            FieldParameter::REQUIRED => true,
            FieldParameter::TABLE_TITLE => '<i class="' . TypeController::ICON . '"></i>',
        ],
        'description' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXTAREA,
            FieldParameter::TITLE => 'Description',
            FieldParameter::LIMIT => 500,
        ],
        'price' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Price',
            FieldParameter::IN_TABLE => 3,
            FieldParameter::REQUIRED => true,
            FieldParameter::LENGTH => 4,
            FieldParameter::GROUP_TITLE => 'Price',
            FieldParameter::GROUP_NAME => 'price_term',
            FieldParameter::GROUP_COL => 4,
        ],
        'deposit' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Deposit',
            FieldParameter::REQUIRED => false,
            FieldParameter::LENGTH => 4,
            FieldParameter::GROUP_NAME => 'price_term',
            FieldParameter::GROUP_COL => 4,
            FieldParameter::GROUP_HIDE => true,
        ],
        'cleaning_fee' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Cleaning Fee',
            FieldParameter::REQUIRED => false,
            FieldParameter::LENGTH => 4,
            FieldParameter::GROUP_NAME => 'price_term',
            FieldParameter::GROUP_COL => 4,
            FieldParameter::GROUP_HIDE => true,
        ],
        'guests_size' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Guests size',
            FieldParameter::REQUIRED => true,
            FieldParameter::LENGTH => 4,
        ],
        'views' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Views',
            FieldParameter::IN_TABLE => 6,
            FieldParameter::HIDDEN => true,
        ],
        'reservations' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Reservations',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::HIDDEN => true,
            FieldParameter::TABLE_TITLE => 'Reservations <i data-tippy-popover data-tippy-content="Future / In Process / Passed / Cancelled / All" class="fa fa-question-circle-o" aria-hidden="true"></i>',
        ],
//        'reservations' => [
//            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
//            FieldParameter::TITLE => 'Reservations',
//            FieldParameter::IN_TABLE => 6,
//            FieldParameter::HIDDEN => true,
//        ],
        'rating' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Rating',
            FieldParameter::IN_TABLE => 6,
            FieldParameter::HIDDEN => true,
        ],
//        'cancellation_description' => [
//            FieldParameter::TYPE => parent::DATA_TYPE_TEXTAREA,
//            FieldParameter::TITLE => 'Cancellation',
//            FieldParameter::LIMIT => 500,
//        ],
        'published_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Published',
            FieldParameter::REQUIRED => false,
            FieldParameter::DATETIME => true,
            FieldParameter::IN_TABLE => 3,
        ],
        'sync' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Sync',
            FieldParameter::IN_TABLE => 3,
            FieldParameter::TABLE_ONLY => true,
            FieldParameter::MODES => [
                MainController::MODE_DEVELOPER,
            ],
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
            FieldParameter::TYPE => parent::DATA_TYPE_CHECKBOX,
            FieldParameter::TITLE => 'Status',
            FieldParameter::TITLE_FORM => 'Active',
            FieldParameter::IN_TABLE => 5,
            FieldParameter::DEFAULT => true,
        ],
    ];
}
