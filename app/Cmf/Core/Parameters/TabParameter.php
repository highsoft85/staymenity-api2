<?php

declare(strict_types=1);

namespace App\Cmf\Core\Parameters;

use App\Cmf\Core\MainController;
use App\Models\User;

class TabParameter
{
    /**
     *
     */
    const TAB_MAIN = 'tabs.main';
    const TAB_MAIN_CONTENT = [
        'title' => 'Data',
        'tabs_attributes' => [
            'aria-controls' => 'tab-main',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
    ];

    /**
     *
     */
    const TAB_USER_DETAILS = 'tabs.user_details';
    const TAB_USER_DETAILS_CONTENT = [
        'title' => 'Details',
        'tabs_attributes' => [
            'aria-controls' => 'tab-user_details',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
    ];

    /**
     *
     */
    const TAB_INFO = 'tabs.info';
    const TAB_INFO_CONTENT = [
        'title' => 'Info',
        'tabs_attributes' => [
            'aria-controls' => 'tab-info',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
    ];

    /**
     *
     */
    const TAB_SOCIALS = 'tabs.socials';
    const TAB_SOCIALS_CONTENT = [
        'title' => 'Socials',
        'tabs_attributes' => [
            'aria-controls' => 'tab-socials',
            'aria-expanded' => 'false',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'false',
        ],
    ];

    /**
     *
     */
    const TAB_PASSWORD = 'tabs.password';
    const TAB_PASSWORD_CONTENT = [
        'title' => 'Password',
        'tabs_attributes' => [
            'aria-controls' => 'tab-password',
            'aria-expanded' => 'false',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'false',
        ],
    ];

    /**
     *
     */
    const TAB_IMAGES_MODEL = 'tabs.images.model';
    const TAB_IMAGES_MODEL_CONTENT = [
        'title' => 'Image',
    ];

    /**
     *
     */
    const TAB_PARAMETERS = 'tabs.parameters';
    const TAB_PARAMETERS_CONTENT = [
        'title' => 'Параметры',
        'tabs_attributes' => [
            'aria-controls' => 'tab-parameters',
            'aria-expanded' => 'false',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'false',
        ],
    ];

    /**
     *
     */
    const TAB_SETTINGS = 'tabs.settings';
    const TAB_SETTINGS_CONTENT = [
        'title' => 'Settings',
        'tabs_attributes' => [
            'aria-controls' => 'tab-settings',
            'aria-expanded' => 'false',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'false',
        ],
    ];

    /**
     *
     */
    const TAB_PARAMETERS_READONLY = 'tabs.parameters';
    const TAB_PARAMETERS_READONLY_CONTENT = [
        'title' => 'Parameters',
        'tabs_attributes' => [
            'aria-controls' => 'tab-parameters',
            'aria-expanded' => 'false',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'false',
        ],
    ];

    /**
     *
     */
    const TAB_LISTING_AMENITIES = 'tabs.amenities';
    const TAB_LISTING_AMENITIES_CONTENT = [
        'title' => 'Amenities',
        'tabs_attributes' => [
            'aria-controls' => 'tab-amenities',
            'aria-expanded' => 'false',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'false',
        ],
    ];

    /**
     *
     */
    const TAB_LISTING_RULES = 'tabs.rules';
    const TAB_LISTING_RULES_CONTENT = [
        'title' => 'Rules',
        'tabs_attributes' => [
            'aria-controls' => 'tab-rules',
            'aria-expanded' => 'false',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'false',
        ],
    ];

    /**
     *
     */
    const TAB_CUSTOMER = 'tabs.customer';
    const TAB_CUSTOMER_CONTENT = [
        'title' => 'Customer',
        'tabs_attributes' => [
            'aria-controls' => 'tab-customer',
            'aria-expanded' => 'false',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'false',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_DATA = 'tabs.api_data';
    const TAB_API_DATA_CONTENT = [
        'title' => 'Api Data',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_data',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_LOCATION = 'tabs.api_location';
    const TAB_API_LOCATION_CONTENT = [
        'title' => 'Location',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_location',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_USER_TOKENS = 'tabs.user_tokens';
    const TAB_USER_TOKENS_CONTENT = [
        'title' => 'Tokens',
        'tabs_attributes' => [
            'aria-controls' => 'tab-user_tokens',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_RESERVATIONS = 'tabs.api_reservations';
    const TAB_API_RESERVATIONS_CONTENT = [
        'title' => 'Api Reservations',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_reservations',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_CHATS = 'tabs.api_chats';
    const TAB_API_CHATS_CONTENT = [
        'title' => 'Api Chats',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_chats',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_SAVES = 'tabs.api_saves';
    const TAB_API_SAVES_CONTENT = [
        'title' => 'Api Saves',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_saves',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_PAYMENTS = 'tabs.api_payments';
    const TAB_API_PAYMENTS_CONTENT = [
        'title' => 'Api Payments',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_payments',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_PAYMENT_CARDS = 'tabs.api_payment_cards';
    const TAB_API_PAYMENT_CARDS_CONTENT = [
        'title' => 'Api Cards',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_payment_cards',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_CALENDAR = 'tabs.api_calendar';
    const TAB_API_CALENDAR_CONTENT = [
        'title' => 'Api Calendar',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_calendar',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_REVIEWS = 'tabs.api_reviews';
    const TAB_API_REVIEWS_CONTENT = [
        'title' => 'Api Reviews',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_reviews',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_RATINGS = 'tabs.api_ratings';
    const TAB_API_RATINGS_CONTENT = [
        'title' => 'Api Ratings',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_ratings',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_NOTIFICATIONS = 'tabs.api_notifications';
    const TAB_API_NOTIFICATIONS_CONTENT = [
        'title' => 'Api Notifications',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_notifications',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_NOTIFICATION = 'tabs.api_notification';
    const TAB_API_NOTIFICATION_CONTENT = [
        'title' => 'Api Notification',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_notification',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_FIREBASE_NOTIFICATIONS = 'tabs.api_firebase_notifications';
    const TAB_API_FIREBASE_NOTIFICATIONS_CONTENT = [
        'title' => 'Api Firebase Notifications',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_firebase_notifications',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_API_DATA_SEARCH = 'tabs.api_data_search';
    const TAB_API_DATA_SEARCH_CONTENT = [
        'title' => 'Api Search',
        'tabs_attributes' => [
            'aria-controls' => 'tab-api_data_search',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];

    /**
     *
     */
    const TAB_HOSTFULLY = 'tabs.hostfully';
    const TAB_HOSTFULLY_CONTENT = [
        'title' => 'Hostfully',
        'tabs_attributes' => [
            'aria-controls' => 'tab-hostfully',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 0,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
    ];

    /**
     *
     */
    const TAB_DEV_HOSTFULLY = 'tabs.dev_hostfully';
    const TAB_DEV_HOSTFULLY_CONTENT = [
        'title' => 'Dev Hostfully',
        'tabs_attributes' => [
            'aria-controls' => 'tab-dev_hostfully',
            'aria-expanded' => 'true',
            'data-hidden-submit' => 1,
        ],
        'content_attributes' => [
            'aria-expanded' => 'true',
        ],
        'modes' => [
            MainController::MODE_DEVELOPER,
        ],
    ];
}
