<?php

declare(strict_types=1);

use App\Services\Visit\VisitService;
use App\Services\Seo\SeoGoogleAnalyticsApiService;

if (!function_exists('visit')) {
    /**
     * @return VisitService
     */
    function visit(): VisitService
    {
        return (new VisitService());
    }
}

if (!function_exists('visitGAnalytics')) {
    /**
     * @return SeoGoogleAnalyticsApiService
     */
    function visitGAnalytics(): SeoGoogleAnalyticsApiService
    {
        return (new SeoGoogleAnalyticsApiService());
    }
}
