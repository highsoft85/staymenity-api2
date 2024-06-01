<?php

use App\Models\User;
use App\Cmf\Project\User\UserController;
use App\Cmf\Project\Listing\ListingController;
use App\Cmf\Project\Type\TypeController;
use App\Cmf\Project\Rule\RuleController;
use App\Cmf\Project\Amenity\AmenityController;
use App\Cmf\Project\Reservation\ReservationController;
use App\Cmf\Project\Balance\BalanceController;
use App\Cmf\Project\Payment\PaymentController;
use App\Cmf\Project\Faq\FaqController;
use App\Cmf\Project\Review\ReviewController;
use App\Cmf\Project\Feedback\FeedbackController;
use App\Cmf\Project\Request\RequestController;
use App\Cmf\Project\Option\OptionController;
use App\Cmf\Project\OptionSystemValue\OptionSystemValueController;
use App\Cmf\Project\Payout\PayoutController;
use App\Cmf\Project\UserIdentity\UserIdentityController;

return [

    'version' => '0.0.1',

    'as' => env('CMF_AS', 'cmf'),

    'url' => env('CMF_URL', env('APP_URL', 'http://localhost')),

    'name' => env('CMF_NAME', env('APP_NAME', '')),

    'prefix' => env('CMF_PREFIX', ''),

    'php_alias' => env('PHP_ALIAS', 'php'),

    'public_path' => env('CMF_PUBLIC_PATH', ''),

    'image_public_directory' => env('IMAGE_PUBLIC_DIRECTORY', 'storage/images'),

    'image_testing_directory' => env('IMAGE_TESTING_DIRECTORY', 'storage/testing/images'),

    'files_public_directory' => env('FILES_PUBLIC_DIRECTORY', 'storage/files'),

    'files_testing_directory' => env('FILES_TESTING_DIRECTORY', 'storage/testing/files'),

    'favicon' => 'cmf/img/favicon-' . env('APP_ENV') . '.ico',

    'sidebar' => [
        'admin' => [
            '' => [
                'title' => 'Home',
                'iconCls' => 'icon-puzzle',
            ],
            UserController::NAME => [
                'title' => UserController::TITLE,
                'iconCls' => UserController::ICON,
                'sub' => [
                    UserController::NAME => [
                        'title' => 'All',
                        'iconCls' => UserController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                        ],
                    ],
                    UserController::NAME . '/to/' . UserController::PAGE_HOSTS => [
                        'title' => 'Hosts',
                        'iconCls' => UserController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    UserController::NAME . '/to/' . UserController::PAGE_GUESTS => [
                        'title' => 'Guests',
                        'iconCls' => UserController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    UserController::NAME . '/to/' . UserController::PAGE_HOSTFULLY => [
                        'title' => 'From Hostfully',
                        'iconCls' => UserController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    UserIdentityController::NAME => [
                        'title' => 'Identities',
                        'iconCls' =>  UserIdentityController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    UserController::NAME . '/to/' . UserController::PAGE_DELETED => [
                        'title' => 'Deleted',
                        'iconCls' => 'icon-trash',
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                ],
                'roles' => [
                    User::ROLE_ADMIN,
                ],
            ],
            ListingController::NAME => [
                'title' => ListingController::TITLE,
                'iconCls' => ListingController::ICON,
                'sub' => [
                    ListingController::NAME => [
                        'title' => 'All',
                        'iconCls' => ListingController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ListingController::NAME . '/to/' . ListingController::PAGE_ACTIVE => [
                        'title' => 'Active',
                        'iconCls' => ListingController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ListingController::NAME . '/to/' . ListingController::PAGE_POPULAR => [
                        'title' => 'Popular',
                        'iconCls' => ListingController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ListingController::NAME . '/to/' . ListingController::PAGE_BOOKED => [
                        'title' => 'Booked',
                        'iconCls' => ListingController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ListingController::NAME . '/to/' .  ListingController::PAGE_DELETED => [
                        'title' => 'Deleted',
                        'iconCls' => 'icon-trash',
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                ],
                'roles' => [
                    User::ROLE_ADMIN,
                ],
            ],
            ReservationController::NAME => [
                'title' => ReservationController::TITLE,
                'iconCls' => ReservationController::ICON,
                'sub' => [
                    ReservationController::NAME => [
                        'title' => 'All',
                        'iconCls' => ReservationController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ReservationController::NAME . '/to/' . ReservationController::PAGE_ACTIVE => [
                        'title' => 'Active',
                        'iconCls' => ReservationController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ReservationController::NAME . '/to/' . ReservationController::PAGE_FUTURE => [
                        'title' => 'Future',
                        'iconCls' => ReservationController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ReservationController::NAME . '/to/' . ReservationController::PAGE_PROCESS => [
                        'title' => 'In Process',
                        'iconCls' => ReservationController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ReservationController::NAME . '/to/' . ReservationController::PAGE_HOSTFULLY => [
                        'title' => 'From Hostfully',
                        'iconCls' => ReservationController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ReservationController::NAME . '/to/' . ReservationController::PAGE_PASSED => [
                        'title' => 'Passed',
                        'iconCls' => ReservationController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                    ReservationController::NAME . '/to/' . ReservationController::PAGE_CANCELLED => [
                        'title' => 'Cancelled',
                        'iconCls' => ReservationController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                ]
            ],
//            BalanceController::NAME => [
//                'title' => BalanceController::TITLE,
//                'iconCls' => BalanceController::ICON,
//                'roles' => [
//                    User::ROLE_ADMIN,
//                ],
//            ],
            PaymentController::NAME => [
                'title' => PaymentController::TITLE,
                'iconCls' => PaymentController::ICON,
                'sub' => [
                    PaymentController::NAME => [
                        'title' => 'All',
                        'iconCls' => PaymentController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                        ],
                    ],
                    PaymentController::NAME . '/to/' . PaymentController::PAGE_ACTIVE => [
                        'title' => 'Active',
                        'iconCls' => PaymentController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                        ],
                    ],
                    PaymentController::NAME . '/to/' . PaymentController::PAGE_CANCELLED => [
                        'title' => 'Cancelled',
                        'iconCls' => PaymentController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                        ],
                    ],
                ],
                'roles' => [
                    User::ROLE_ADMIN,
                ],
            ],
            PayoutController::NAME => [
                'title' => PayoutController::TITLE,
                'iconCls' => PayoutController::ICON,
                'roles' => [
                    User::ROLE_ADMIN,
                ],
            ],
            ReviewController::NAME => [
                'title' => ReviewController::TITLE,
                'iconCls' => ReviewController::ICON,
                'roles' => [
                    User::ROLE_ADMIN,
                ],
            ],

            'options' => [
                'title' => 'Directories',
                'iconCls' => 'icon-grid',
                'sub' => [
                    TypeController::NAME => [
                        'title' => TypeController::TITLE,
                        'iconCls' => TypeController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                        ],
                    ],
                    RuleController::NAME => [
                        'title' => RuleController::TITLE,
                        'iconCls' => RuleController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                        ],
                    ],
                    AmenityController::NAME => [
                        'title' => AmenityController::TITLE,
                        'iconCls' => AmenityController::ICON,
                        'roles' => [
                            User::ROLE_ADMIN,
                        ],
                    ],
                ]
            ],
//            OptionController::NAME => [
//                'title' => OptionController::TITLE,
//                'iconCls' => OptionController::ICON,
//            ],
            OptionSystemValueController::NAME => [
                'title' => OptionSystemValueController::TITLE,
                'iconCls' => OptionSystemValueController::ICON,
            ],
            FeedbackController::NAME => [
                'title' => FeedbackController::TITLE,
                'iconCls' => FeedbackController::ICON,
            ],
            RequestController::NAME => [
                'title' => RequestController::TITLE,
                'iconCls' => RequestController::ICON,
            ],
            FaqController::NAME => [
                'title' => FaqController::TITLE,
                'iconCls' => FaqController::ICON,
            ],
        ],
    ],

    'options' => [
        'app' => [
            'title' => env('CMF_NAME'),
            'title_short' => env('CMF_NAME'),
            'description' => null,
            'keywords' => null,
            'favicon' => [
                'main' => '/favicon.ico',
            ],
            'powered' => [
                'link' => 'https://ag.digital/',
                'title' => 'AG.digital',
            ],
            'copyright' => ' ' . env('APP_NAME'),
        ],
    ],

    'cache' => [
        'fields' => true,
        'member' => true,

        'name' => 'user_',
        'tag' => 'members',
    ]
];
