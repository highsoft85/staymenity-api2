<?php

return [
    'avatar' => env('IMAGE_URL') . '/img/logo.png',

    // new listing email notification every hour to the recipients
    'new_listing_enabled' => env('APP_FEATURE_NEW_LISTING_MAIL_ENABLED', false),

    // string with , separator, example admin@admin.com,admin1@admin.com
    'new_listing_recipients' => env('APP_FEATURE_NEW_LISTING_MAIL_RECIPIENTS', null),

    // проверять перед бронью подключение payout хоста
    'reservation_check_host_payout_connect' => env('APP_RESERVATION_CHECK_HOST_PAYOUT_CONNECT', true),

    // синхронизировать бронь с hostfully после простого создания, без оплаты
    'reservation_sync_after_store' => env('APP_RESERVATION_SYNC_AFTER_STORE', false),
];
