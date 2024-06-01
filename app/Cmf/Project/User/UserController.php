<?php

declare(strict_types=1);

namespace App\Cmf\Project\User;

use App\Cmf\Core\Defaults\ImageableTrait;
use App\Cmf\Core\FieldParameter;
use App\Cmf\Core\MainController;
use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Models\User;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends MainController
{
    use UserSettingsTrait;
    use ImageableTrait;
    use UserCustomTrait;
    use UserThisTrait;
    use UserMaintenanceTrait;
    use UserExcelExportTrait;
    use UserPagesTrait;

    /**
     * Заголовок сущности
     */
    const TITLE = 'Users';

    /**
     * Имя сущности
     */
    const NAME = 'user';

    /**
     * Иконка
     */
    const ICON = 'icon-people';

    /**
     * Модель сущности
     */
    public $class = \App\Models\User::class;

    const PAGE_HOSTS = 'hosts';
    const PAGE_GUESTS = 'guests';
    const PAGE_DELETED = 'deleted';
    const PAGE_HOSTFULLY = 'hostfully';

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    public $with = ['roles', 'modelImages', 'socialAccounts', 'listingsActive'];

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
        TableParameter::INDEX_STATE => true,
        TableParameter::INDEX_SHOW => false,
        TableParameter::INDEX_SEARCH => false,
        TableParameter::INDEX_IMAGE => false,
        TableParameter::INDEX_PRIVATE_SHOW => true,
        TableParameter::INDEX_SOFT_DELETE => false,
        TableParameter::INDEX_CREATE => false,
        TableParameter::INDEX_EXPORT => true,
    ];

    /**
     * @var array
     */
    public $image = [
        ImageType::MODEL => [
            'with_main' => true,
            'clear_cache' => true,
            'filters' => [
                ImageSize::XS => ImageSize::IMAGE_SIZE_XS_USER_CONTENT,
                ImageSize::SQUARE => ImageSize::IMAGE_SIZE_SQUARE_CONTENT,
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
        if (!is_null($model)) {
            array_push($this->rules['update']['email'], Rule::unique('users')->ignore($model->id));
        }
        return $this->rules;
    }

    /**
     * Validation rules
     * @var array
     */
    public $rules = [
        'store' => [
            'first_name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'confirmed', 'max:255'],
        ],
        'update' => [
            'first_name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            //'password' => ['confirmed', 'max:255'],
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
            TabParameter::TAB_PASSWORD => TabParameter::TAB_PASSWORD_CONTENT,
            TabParameter::TAB_IMAGES_MODEL => TabParameter::TAB_IMAGES_MODEL_CONTENT,
            TabParameter::TAB_CUSTOMER => TabParameter::TAB_CUSTOMER_CONTENT,
            TabParameter::TAB_API_DATA => TabParameter::TAB_API_DATA_CONTENT,
            TabParameter::TAB_API_LOCATION => TabParameter::TAB_API_LOCATION_CONTENT,
            TabParameter::TAB_USER_TOKENS => TabParameter::TAB_USER_TOKENS_CONTENT,
            TabParameter::TAB_API_RESERVATIONS => TabParameter::TAB_API_RESERVATIONS_CONTENT,
            TabParameter::TAB_API_PAYMENTS => TabParameter::TAB_API_PAYMENTS_CONTENT,
            TabParameter::TAB_API_PAYMENT_CARDS => TabParameter::TAB_API_PAYMENT_CARDS_CONTENT,
            TabParameter::TAB_API_CALENDAR => TabParameter::TAB_API_CALENDAR_CONTENT,
            TabParameter::TAB_API_REVIEWS => TabParameter::TAB_API_REVIEWS_CONTENT,
            TabParameter::TAB_API_RATINGS => TabParameter::TAB_API_RATINGS_CONTENT,
            TabParameter::TAB_API_NOTIFICATION => TabParameter::TAB_API_NOTIFICATION_CONTENT,
            TabParameter::TAB_API_NOTIFICATIONS => TabParameter::TAB_API_NOTIFICATIONS_CONTENT,
            TabParameter::TAB_API_FIREBASE_NOTIFICATIONS => TabParameter::TAB_API_FIREBASE_NOTIFICATIONS_CONTENT,
            TabParameter::TAB_API_CHATS => TabParameter::TAB_API_CHATS_CONTENT,
            TabParameter::TAB_API_SAVES => TabParameter::TAB_API_SAVES_CONTENT,
            TabParameter::TAB_HOSTFULLY => TabParameter::TAB_HOSTFULLY_CONTENT,
        ],
        'show' => [
            TabParameter::TAB_MAIN => TabParameter::TAB_MAIN_CONTENT,
        ],
    ];

    /**
     * @var array
     */
    public $fields = [
        'id' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'ID',
            FieldParameter::LENGTH => 6,
            FieldParameter::HIDDEN => true,
        ],
        'first_name' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'First Name',
            FieldParameter::IN_TABLE => 1,
            FieldParameter::DELETE_TITLE => 'User',
            FieldParameter::REQUIRED => true,
            FieldParameter::GROUP_TITLE => 'Full Name',
            FieldParameter::GROUP_NAME => 'full_name_term',
            FieldParameter::GROUP_COL => 6,
        ],
        'last_name' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Last Name',
            FieldParameter::REQUIRED => false,
            FieldParameter::GROUP_NAME => 'full_name_term',
            FieldParameter::GROUP_COL => 6,
            FieldParameter::GROUP_HIDE => true,
        ],
        'birthday_day' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Day',
            FieldParameter::LENGTH => 2,
            FieldParameter::PLACEHOLDER => 'xx',
            FieldParameter::GROUP_TITLE => 'Birthday Day',
            FieldParameter::GROUP_NAME => 'birthday_term',
            FieldParameter::GROUP_COL => 2,
            FieldParameter::ALIAS => 'birthday_day',
        ],
        'birthday_month' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Month',
            FieldParameter::PLACEHOLDER => 'xx',
            FieldParameter::LENGTH => 2,
            FieldParameter::GROUP_NAME => 'birthday_term',
            FieldParameter::GROUP_COL => 2,
            FieldParameter::GROUP_HIDE => true,
            FieldParameter::ALIAS => 'birthday_month',
        ],
        'birthday_year' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Year',
            FieldParameter::PLACEHOLDER => 'xxxx',
            FieldParameter::LENGTH => 4,
            FieldParameter::GROUP_NAME => 'birthday_term',
            FieldParameter::GROUP_COL => 2,
            FieldParameter::GROUP_HIDE => true,
            FieldParameter::ALIAS => 'birthday_year',
        ],
        'age' => [
            FieldParameter::TYPE => parent::DATA_TYPE_NUMBER,
            FieldParameter::TITLE => 'Age',
            FieldParameter::PLACEHOLDER => 'xx',
            FieldParameter::LENGTH => 2,
            FieldParameter::GROUP_NAME => 'birthday_term',
            FieldParameter::GROUP_COL => 6,
            FieldParameter::GROUP_HIDE => true,
        ],
        'email' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Email',
            FieldParameter::REQUIRED => true,
            FieldParameter::GROUP_TITLE => 'Contacts',
            FieldParameter::GROUP_NAME => 'email_phone_term',
            FieldParameter::GROUP_COL => 6,
        ],
        'phone' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Phone',
            FieldParameter::REQUIRED => false,
            FieldParameter::GROUP_NAME => 'email_phone_term',
            FieldParameter::GROUP_COL => 6,
            FieldParameter::GROUP_HIDE => true,
            FieldParameter::MASK_PHONE => true,
            FieldParameter::IN_TABLE => 3,
        ],
        'roles' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'Roles',
            FieldParameter::RELATIONSHIP => parent::RELATIONSHIP_BELONGS_TO_MANY,
            FieldParameter::VALUES => Role::class,
            FieldParameter::ORDER => [
                FieldParameter::ORDER_METHOD => 'orderBy',
                FieldParameter::ORDER_BY => 'title',
            ],
            FieldParameter::ALIAS => 'title',
            FieldParameter::REQUIRED => true,
            FieldParameter::MULTIPLE => true,
            FieldParameter::EMPTY => false,
            FieldParameter::IN_TABLE => 3,
            FieldParameter::ROLES => [
                User::ROLE_ADMIN,
            ],
            FieldParameter::GROUP_TITLE => 'Role',
            FieldParameter::GROUP_NAME => 'role_term',
            FieldParameter::GROUP_COL => 6,
        ],
        'current_role' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Current Role',
            FieldParameter::REQUIRED => true,
            FieldParameter::GROUP_NAME => 'role_term',
            FieldParameter::GROUP_COL => 6,
            FieldParameter::GROUP_HIDE => true,
        ],
        'register_by' => [
            FieldParameter::TYPE => parent::DATA_TYPE_SELECT,
            FieldParameter::TITLE => 'Register By',
            FieldParameter::REQUIRED => true,
            FieldParameter::VALUES => [
                User::REGISTER_BY_EMAIL => 'Email',
                User::REGISTER_BY_PHONE => 'Phone',
                User::REGISTER_BY_SOCIAL => 'Social',
                User::REGISTER_BY_RESERVATION => 'Reservation',
            ],
            FieldParameter::DEFAULT => User::REGISTER_BY_EMAIL,
            FieldParameter::GROUP_TITLE => 'Registration',
            FieldParameter::GROUP_NAME => 'registration_term',
            FieldParameter::GROUP_COL => 6,
        ],
        'registered_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Registered At',
            FieldParameter::REQUIRED => false,
            FieldParameter::DATETIME => true,
            FieldParameter::GROUP_NAME => 'registration_term',
            FieldParameter::GROUP_COL => 6,
            FieldParameter::GROUP_HIDE => true,
        ],
        'password' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Password',
            FieldParameter::REQUIRED => true,
            FieldParameter::GROUP_NAME => 'password_term',
            FieldParameter::GROUP_COL => 6,
            FieldParameter::GROUP_HIDE => true,
            FieldParameter::MODAL_ONLY => ['create'],
        ],
        'password_confirmation' => [
            FieldParameter::TYPE => parent::DATA_TYPE_TEXT,
            FieldParameter::TITLE => 'Password Confirmation',
            FieldParameter::REQUIRED => true,
            FieldParameter::GROUP_NAME => 'password_term',
            FieldParameter::GROUP_COL => 6,
            FieldParameter::GROUP_HIDE => true,
            FieldParameter::MODAL_ONLY => ['create'],
        ],
        'listings' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Listings',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::HIDDEN => true,
            FieldParameter::TABLE_TITLE => 'Listings <i data-tippy-popover data-tippy-content="Active / All" class="fa fa-question-circle-o" aria-hidden="true"></i>',
        ],
        'reservations' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Reservations',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::HIDDEN => true,
            FieldParameter::TABLE_TITLE => 'Reservations <i data-tippy-popover data-tippy-content="Future / In Process / Passed / Cancelled / All" class="fa fa-question-circle-o" aria-hidden="true"></i>',
        ],
        'last_login_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Last Login',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::HIDDEN => true,
        ],
        'banned_at' => [
            FieldParameter::TYPE => parent::DATA_TYPE_DATE,
            FieldParameter::TITLE => 'Banned',
            FieldParameter::REQUIRED => false,
            FieldParameter::DATETIME => true,
            FieldParameter::HIDDEN => true,
            FieldParameter::IN_TABLE => 3,
        ],
        'social_state' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CUSTOM,
            FieldParameter::TITLE => 'Socials',
            FieldParameter::HIDDEN => true,
            FieldParameter::IN_TABLE => 3,
        ],
        'status' => [
            FieldParameter::TYPE => parent::DATA_TYPE_CHECKBOX,
            FieldParameter::TITLE => 'Status',
            FieldParameter::TITLE_FORM => 'Active',
            FieldParameter::IN_TABLE => 2,
            FieldParameter::DEFAULT => true,
        ],
    ];
}
