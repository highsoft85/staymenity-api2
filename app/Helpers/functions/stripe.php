<?php

declare(strict_types=1);

if (!function_exists('stripeCardDefault')) {
    /**
     * @return array
     */
    function stripeCardDefault()
    {
        return [
            'number' => 4242424242424242,
            'brand' => 'Visa',
            'exp_month' => 4,
            'exp_year' => 24,
            'cvc' => 424,
        ];
    }
}

if (!function_exists('stripeCardDefaultVisaDebit')) {
    /**
     * @return array
     */
    function stripeCardDefaultVisaDebit()
    {
        return [
            'number' => 4000056655665556,
            'brand' => 'Visa (debit)',
            'exp_month' => 4,
            'exp_year' => 24,
            'cvc' => 424,
        ];
    }
}

if (!function_exists('stripeCardMaster')) {
    /**
     * @return array
     */
    function stripeCardMaster()
    {
        return [
            'number' => 5555555555554444,
            'brand' => 'Mastercard',
            'exp_month' => 4,
            'exp_year' => 24,
            'cvc' => 424,
        ];
    }
}

if (!function_exists('stripeCard3DSecure')) {
    /**
     * @return array
     */
    function stripeCard3DSecure()
    {
        return [
            'number' => 4000002500003155,
            'brand' => 'Visa',
            'exp_month' => 4,
            'exp_year' => 24,
            'cvc' => 424,
        ];
    }
}

if (!function_exists('stripeCustomerMy')) {
    /**
     * @return string
     */
    function stripeCustomerMy()
    {
        return 'cus_IP1nSeGavNDlBQ';
    }
}

if (!function_exists('stripeCustomerMyGuest')) {
    /**
     * @return string
     */
    function stripeCustomerMyGuest()
    {
        return 'cus_IP1ofPZQqauprA';
    }
}

if (!function_exists('stripeAccountMain')) {
    /**
     * @return string
     */
    function stripeAccountMain()
    {
        return 'acct_1HJdBZIFDQsDl8sw';
    }
}

if (!function_exists('stripeAccountMainConnected1')) {
    /**
     * @return string
     */
    function stripeAccountMainConnected1()
    {
        return 'acct_1HtvzxRPVfUvcYbU';
    }
}

if (!function_exists('stripeAccountSupport')) {
    /**
     * @return string
     */
    function stripeAccountSupport()
    {
        return 'acct_1HtvZRKqYdEDhBvQ';
    }
}

if (!function_exists('stripeAccountWeekendBusTour')) {
    /**
     * @return string
     */
    function stripeAccountWeekendBusTour()
    {
        return 'acct_1HtxqBRDwaEeoZbW';
    }
}

if (!function_exists('stripeAccountJaniyaCasper')) {
    /**
     * @return string
     */
    function stripeAccountJaniyaCasper()
    {
        return 'acct_1Hu1EPRSpFVpMqKe';
    }
}

if (!function_exists('stripePaymentIntendTest')) {
    /**
     * @return string
     */
    function stripePaymentIntendTest()
    {
        return 'pi_random';
    }
}
