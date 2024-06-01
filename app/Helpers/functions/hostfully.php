<?php

declare(strict_types=1);

if (!function_exists('hostfullyFakePropertyUid')) {

    function hostfullyFakePropertyUid(): string
    {
        return 'eb23fc7d-0000-4924-bde5-2916c3a4d8e1';
    }
}

if (!function_exists('hostfullyFakeAgencyUid')) {

    function hostfullyFakeAgencyUid(): string
    {
        return 'eb23fc7d-0000-4924-bde5-2916c3a4d8e1';
    }
}

if (!function_exists('hostfullyFakeLeadUid')) {

    function hostfullyFakeLeadUid(): string
    {
        return 'eb23fc7d-3aeb-0000-bde5-2916c3a4d8e1';
    }
}

if (!function_exists('hostfullyWebhooksUrl')) {

    function hostfullyWebhooksUrl(): string
    {
        if (envIsProduction()) {
            return config('api.url') . '/api/webhooks/hostfully';
        }
        return 'https://api.staymenity.com/api/webhooks/hostfully';
    }
}
