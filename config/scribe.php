<?php

use App\Docs\Strategies\Metadata;
use App\Docs\Strategies\UrlParameters;
use App\Docs\Strategies\QueryParameters;
use App\Docs\Strategies\BodyParameters;
use App\Docs\Strategies\ResponseFields;
use App\Docs\Strategies\Responses;
use App\Docs\Strategies\Headers;

/**
 * https://scribe.readthedocs.io/en/latest/guide-getting-started.html
 */
return [
    /*
     * The type of documentation output to generate.
     * - "static" will generate a static HTMl page in the /public/docs folder,
     * - "laravel" will generate the documentation as a Blade view, so you can add routing and authentication.
     */
    'type' => 'static',

    /*
     * Settings for `static` type output.
     */
    'static' => [
        /*
         * HTML documentation, assets and Postman collection will be generated to this folder.
         * Source Markdown will still be in resources/docs.
         */
        'output_path' => 'public/docs',
    ],

    /*
     * Settings for `laravel` type output.
     */
    'laravel' => [
        /*
         * Whether to automatically create a docs endpoint for you to view your generated docs.
         * If this is false, you can still set up routing manually.
         */
        'add_routes' => true,

        /*
         * URL path to use for the docs endpoint (if `add_routes` is true).
         * By default, `/docs` opens the HTML page, and `/docs.json` downloads the Postman collection.
         */
        'docs_url' => '/docs',

        /*
         * Middleware to attach to the docs endpoint (if `add_routes` is true).
         */
        'middleware' => [],
    ],

    /*
     * How is your API authenticated? This information will be used in the displayed docs, generated examples and response calls.
     */
    'auth' => [
        /*
         * Set this to true if your API is authenticated.
         */
        'enabled' => true,

        /*
         * Where is the auth value meant to be sent in a request?
         * Options: query, body, query_or_body, basic, bearer, header (for custom header)
         */
        'in' => 'bearer',

        /*
         * The name of the parameter (eg token, key, apiKey) or header (eg Authorization, Api-Key).
         */
        'name' => 'header',

        /*
         * The value of the parameter to be used by Scribe to authenticate response calls.
         * This will NOT be included in the generated documentation.
         * If this value is empty, Scribe will use a random value.
         */
        'use_value' => env('SCRIBE_AUTH_KEY'),

        /*
         * Placeholder your users will see for the auth parameter in the example requests.
         * If this value is null, Scribe will use a random value.
         */
        'placeholder' => '{YOUR_AUTH_KEY}',

        /*
         * Any extra authentication-related info for your users. For instance, you can describe how to find or generate their auth credentials.
         * Markdown and HTML are supported.
         */
        'extra_info' => 'You can retrieve your token by visiting your dashboard and clicking <b>Generate API token</b>.',
    ],

    /*
     * Text to place in the "Introduction" section, right after the `description`. Markdown and HTML are supported.
     */
    'intro_text' => <<<INTRO
Welcome to our API documentation!

<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile), and you can switch the programming language of the examples with the tabs in the top right (or from the nav menu at the top left on mobile).</aside>
INTRO
    ,

    /*
     * Example requests for each endpoint will be shown in each of these languages.
     * Supported options are: bash, javascript, php, python
     * You can add a language of your own, but you must publish the package's views
     * and define a corresponding view for it in the partials/example-requests directory.
     * See https://scribe.readthedocs.io/en/latest/generating-documentation.html
     *
     */
    'example_languages' => [
        'bash',
        'javascript',
    ],

    /*
     * The base URL to be used in examples.
     * If this is null, Scribe will use the value of config('app.url').
     */
    'base_url' => env('API_URL'),

    /*
     * The HTML <title> for the generated documentation, and the name of the generated Postman collection.
     * If this is null, Scribe will infer it from config('app.name').
     */
    'title' => 'API Documentation for Staymenity',

    'description' => '',

    /*
     * Generate a Postman collection in addition to HTML docs.
     * For 'static' docs, the collection will be generated to public/docs/collection.json.
     * For 'laravel' docs, it will be generated to storage/app/scribe/collection.json.
     * Setting `laravel.add_routes` to true (above) will also add a route for the collection.
     * Collection schema: https://schema.getpostman.com/json/collection/v2.0.0/collection.json
     */
    'postman' => [
        'enabled' => true,

        /*
         * Manually override some generated content in the spec. Dot notation is supported.
         */
        'overrides' => [
            // 'info.version' => '2.0.0',
        ],
    ],

    /*
     * Generate an OpenAPI spec file in addition to docs webpage.
     * For 'static' docs, the collection will be generated to public/docs/openapi.yaml.
     * For 'laravel' docs, it will be generated to storage/app/scribe/openapi.yaml.
     * Setting `laravel.add_routes` to true (above) will also add a route for the spec.
     */
    'openapi' => [
        'enabled' => true,

        /*
         * Manually override some generated content in the spec. Dot notation is supported.
         */
        'overrides' => [
            // 'info.version' => '2.0.0',
        ],
    ],

    /*
     * Name for the group of endpoints which do not have a @group set.
     */
    'default_group' => 'data',

    /*
     * Custom logo path. This will be used as the value of the src attribute for the <img> tag,
     * so make sure it points to a public URL or path accessible from your web server. For best results, the image width should be 230px.
     * Set this to false to not use a logo.
     *
     * For example, if your logo is in public/img:
     * - 'logo' => '../img/logo.png' // for `static` type (output folder is public/docs)
     * - 'logo' => 'img/logo.png' // for `laravel` type
     *
     */
    'logo' => false,

    /*
     * The router your API is using (Laravel or Dingo).
     */
    'router' => 'dingo',

    /*
     * The routes for which documentation should be generated.
     * Each group contains rules defining which routes should be included ('match', 'include' and 'exclude' sections)
     * and settings which should be applied to them ('apply' section).
     */
    'routes' => [
        [
            /*
             * Specify conditions to determine what routes will be parsed in this group.
             * A route must fulfill ALL conditions to pass.
             */
            'match' => [
                /*
                 * Match only routes whose domains match this pattern (use * as a wildcard to match any characters). Example: 'api.*'.
                 */
                'domains' => ['*'],

                /*
                 * Match only routes whose paths match this pattern (use * as a wildcard to match any characters). Example: 'users/*'.
                 */
                'prefixes' => ['api/*'],

                /*
                 * (Dingo router only) Match only routes registered under this version.
                 * Note that wildcards are not supported.
                 */
                'versions' => [
                    env('API_VERSION'),
                ],
            ],

            /*
             * Include these routes when generating documentation, even if they did not match the rules above.
             * The route can be referenced by name or path here. Wildcards are supported.
             */
            'include' => [
                // 'users.index', 'healthcheck*'
            ],

            /*
             * Exclude these routes when generating documentation, even if they matched the rules above.
             * The route can be referenced by name or path here. Wildcards are supported.
             */
            'exclude' => [
                // '/health', 'admin.*'
                'api/sanctum/csrf-cookie',
                'api/user/dev/*',
                'api/dev/*',
            ],

            /*
             * Specify rules to be applied to all the routes in this group when generating documentation
             */
            'apply' => [
                /*
                 * Specify headers to be added to the example requests
                 */
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],

                /*
                 * If no @response or @transformer declarations are found for the route,
                 * we'll try to get a sample response by attempting an API call.
                 * Configure the settings for the API call here.
                 */
                'response_calls' => [
                    /*
                     * API calls will be made only for routes in this group matching these HTTP methods (GET, POST, etc).
                     * List the methods here or use '*' to mean all methods. Leave empty to disable API calls.
                     */
                    'methods' => ['GET'],

                    /*
                     * Laravel config variables which should be set for the API call.
                     * This is a good place to ensure that notifications, emails and other external services
                     *  are not triggered during the documentation API calls.
                     * You can also create a `.env.docs` file and run the generate command with `--env docs`.
                     */
                    'config' => [
                        'app.env' => 'documentation',
                        // 'app.debug' => false,
                    ],

                    /*
                     * Cookies which should be sent with the API call.
                     */
                    'cookies' => [
                        // 'name' => 'value'
                    ],

                    /*
                     * Query parameters which should be sent with the API call.
                     */
                    'queryParams' => [
                        // 'key' => 'value',
                    ],

                    /*
                     * Body parameters which should be sent with the API call.
                     */
                    'bodyParams' => [
                        // 'key' => 'value',
                    ],

                    /*
                     * Files which should be sent with the API call.
                     * Each value should be a valid absolute path to a file on this machine.
                     */
                    'fileParams' => [
                        // 'key' => '/home/me/image.png',
                    ],
                ],
            ],
        ],
    ],

    /*
     * Configure how responses are transformed using @transformer and @transformerCollection (requires league/fractal package)
     */
    'fractal' => [
        /* If you are using a custom serializer with league/fractal, you can specify it here.
         * Leave as null to use no serializer or return simple JSON.
         */
        'serializer' => null,
    ],

    /*
     * If you would like the package to generate the same example values for parameters on each run,
     * set this to any number (eg. 1234)
     */
    'faker_seed' => null,

    /**
     * The strategies Scribe will use to extract information about your routes at each stage.
     * If you write or install a custom strategy, add it here. Unless you know what you're doing, you shouldn't remove any of the default strategies.
     */
    'strategies' => [
        'metadata' => [
            \Knuckles\Scribe\Extracting\Strategies\Metadata\GetFromDocBlocks::class,
            // custom
            Metadata\Index\DataStrategy::class,
            Metadata\Index\DataSubjectStrategy::class,
            Metadata\Index\FaqStrategy::class,
            Metadata\Index\LogoutStrategy::class,
            Metadata\Index\FeedbackStrategy::class,
            Metadata\Index\HostRequestStrategy::class,
            Metadata\Index\Payout\Connect\SuccessStrategy::class,

            // user
            Metadata\UserStrategy::class,

            Metadata\UserShow\ShowStrategy::class,
            Metadata\UserShow\Reviews\IndexStrategy::class,
            Metadata\Host\ShowStrategy::class,
            Metadata\Host\Reviews\IndexStrategy::class,
            Metadata\Guest\ShowStrategy::class,
            Metadata\Guest\Reviews\IndexStrategy::class,

            Metadata\Reservations\StoreStrategy::class,

            Metadata\User\UpdateStrategy::class,
            Metadata\User\DestroyStrategy::class,
            Metadata\User\BalanceStrategy::class,
            Metadata\User\Reviews\IndexStrategy::class,
            Metadata\User\Image\DestroyStrategy::class,
            Metadata\User\Social\DestroyStrategy::class,
            Metadata\User\Settings\Notifications\UpdateStrategy::class,

            Metadata\User\Reservations\IndexStrategy::class,
            Metadata\User\Reservations\StoreStrategy::class,
            Metadata\User\Reservations\ShowStrategy::class,
            Metadata\User\Reservations\UpdateStrategy::class,
            Metadata\User\Reservations\Review\IndexStrategy::class,
            Metadata\User\Reservations\Review\StoreStrategy::class,
            Metadata\User\Reservations\PaymentStrategy::class,
            Metadata\User\Reservations\DeclineStrategy::class,
            Metadata\User\Reservations\CancelStrategy::class,

            Metadata\User\Payments\IndexStrategy::class,

            Metadata\User\Payments\Cards\IndexStrategy::class,
            Metadata\User\Payments\Cards\StoreStrategy::class,
            Metadata\User\Payments\Cards\UpdateStrategy::class,
            Metadata\User\Payments\Cards\DestroyStrategy::class,

            Metadata\User\Payments\Stripe\EphemeralStrategy::class,

            Metadata\User\Payouts\IndexStrategy::class,
            Metadata\User\Payouts\Stripe\ConnectStrategy::class,
            Metadata\User\Payouts\Stripe\DashboardStrategy::class,

            Metadata\User\Listings\ShowStrategy::class,
            Metadata\User\Listings\IndexStrategy::class,
            Metadata\User\Listings\StoreStrategy::class,
            Metadata\User\Listings\UpdateStrategy::class,
            Metadata\User\Listings\DestroyStrategy::class,
            Metadata\User\Listings\Images\IndexStrategy::class,
            Metadata\User\Listings\Image\MainStrategy::class,
            Metadata\User\Listings\Image\DestroyStrategy::class,
            Metadata\User\Listings\Calendar\IndexStrategy::class,
            Metadata\User\Listings\Calendar\UpdateStrategy::class,

            Metadata\User\Saves\IndexStrategy::class,
            Metadata\User\Saves\StoreStrategy::class,
            Metadata\User\Saves\ShowStrategy::class,
            Metadata\User\Saves\DestroyStrategy::class,
            Metadata\User\Favorites\IndexStrategy::class,
            Metadata\User\Favorites\ToggleStrategy::class,

            // notifications
            Metadata\User\Notifications\IndexStrategy::class,
            Metadata\User\Notifications\DestroyStrategy::class,
            Metadata\User\Notifications\ClearStrategy::class,

            Metadata\User\Devices\IndexStrategy::class,
            Metadata\User\Devices\StoreStrategy::class,
            Metadata\User\Devices\DestroyStrategy::class,

            Metadata\User\Chats\IndexStrategy::class,
            Metadata\User\Chats\StoreStrategy::class,
            Metadata\User\Chats\DestroyStrategy::class,

            Metadata\User\Chats\Messages\IndexStrategy::class,
            Metadata\User\Chats\Messages\StoreStrategy::class,

            Metadata\User\Verifications\Identities\StoreStrategy::class,
            Metadata\User\Verifications\Identities\UpdateStrategy::class,
            Metadata\User\Verifications\Identities\ShowStrategy::class,
            Metadata\User\Verifications\Identities\DestoryStrategy::class,
            Metadata\User\Verifications\Identities\Step\UploadStrategy::class,
            Metadata\User\Verifications\VerifiedStrategy::class,

            Metadata\Listings\ShowStrategy::class,
            Metadata\Listings\TimesStrategy::class,
            Metadata\Listings\SimilarStrategy::class,
            Metadata\Listings\Reviews\IndexStrategy::class,
            Metadata\Listings\ChatStrategy::class,

            Metadata\Auth\Socialite\FacebookStrategy::class,
            Metadata\Auth\Socialite\GoogleStrategy::class,
            Metadata\Auth\Socialite\AppleStrategy::class,

            Metadata\Auth\Sanctum\FacebookStrategy::class,
            Metadata\Auth\Sanctum\GoogleStrategy::class,
            Metadata\Auth\Sanctum\AppleStrategy::class,

            Metadata\Auth\Socialite\MockStrategy::class,
            Metadata\Auth\Socialite\MockSecondStrategy::class,
            Metadata\Auth\LoginStrategy::class,
            Metadata\Auth\RegisterStrategy::class,
            Metadata\Auth\ForgotPasswordStrategy::class,
            Metadata\Auth\ResetPasswordStrategy::class,
            Metadata\Auth\Phone\CodeStrategy::class,
            Metadata\Auth\Phone\VerifyStrategy::class,

            Metadata\Auth\Verify\Failed::class,
            Metadata\Auth\Verify\Success::class,

            Metadata\Listings\StoreStrategy::class,

            // search
            Metadata\Search\IndexStrategy::class,
            Metadata\Search\Address\IndexStrategy::class,
            Metadata\Search\Place\IndexStrategy::class,
            Metadata\Search\City\IndexStrategy::class,

            Metadata\Docs\DocsStrategy::class,
            Metadata\Docs\KeysStrategy::class,
        ],
        'urlParameters' => [
            \Knuckles\Scribe\Extracting\Strategies\UrlParameters\GetFromUrlParamTag::class,

            // custom
            UrlParameters\Index\DataSubjectStrategy::class,

            UrlParameters\Listings\ShowStrategy::class,
            UrlParameters\Listings\TimesStrategy::class,
            UrlParameters\Listings\SimilarStrategy::class,
            UrlParameters\Listings\Reviews\IndexStrategy::class,
            UrlParameters\Listings\ChatStrategy::class,

            // user
            UrlParameters\User\ShowStrategy::class,
            UrlParameters\UserShow\ShowStrategy::class,
            UrlParameters\UserShow\Reviews\IndexStrategy::class,

            UrlParameters\Host\ShowStrategy::class,
            UrlParameters\Host\Reviews\IndexStrategy::class,

            UrlParameters\Guest\ShowStrategy::class,
            UrlParameters\Guest\Reviews\IndexStrategy::class,

            UrlParameters\User\Social\DestroyStrategy::class,

            // user listing
            UrlParameters\User\Listings\ListingStrategy::class,
            UrlParameters\User\Listings\UpdateStrategy::class,
            UrlParameters\User\Listings\DestroyStrategy::class,
            UrlParameters\User\Listings\Image\MainStrategy::class,
            UrlParameters\User\Listings\Image\DestroyStrategy::class,
            UrlParameters\User\Listings\Calendar\IndexStrategy::class,
            UrlParameters\User\Listings\Calendar\UpdateStrategy::class,
            UrlParameters\User\Listings\Images\IndexStrategy::class,

            UrlParameters\User\Saves\ShowStrategy::class,
            UrlParameters\User\Saves\DestroyStrategy::class,

            UrlParameters\User\Verifications\Identities\UpdateStrategy::class,
            UrlParameters\User\Verifications\Identities\ShowStrategy::class,
            UrlParameters\User\Verifications\Identities\DestroyStrategy::class,
            UrlParameters\User\Verifications\Identities\Step\UploadStrategy::class,

            UrlParameters\User\Reservations\UpdateStrategy::class,
            UrlParameters\User\Reservations\ShowStrategy::class,
            UrlParameters\User\Reservations\PaymentStrategy::class,
            UrlParameters\User\Reservations\DeclineStrategy::class,
            UrlParameters\User\Reservations\CancelStrategy::class,
            UrlParameters\User\Reservations\Review\IndexStrategy::class,
            UrlParameters\User\Reservations\Review\StoreStrategy::class,

            UrlParameters\User\Notifications\DestroyStrategy::class,

            UrlParameters\User\Payments\Cards\DestroyStrategy::class,
            UrlParameters\User\Payments\Cards\UpdateStrategy::class,

            UrlParameters\User\Chats\DestroyStrategy::class,
            UrlParameters\User\Chats\Messages\IndexStrategy::class,
            UrlParameters\User\Chats\Messages\StoreStrategy::class,
        ],
        'queryParameters' => [
            \Knuckles\Scribe\Extracting\Strategies\QueryParameters\GetFromQueryParamTag::class,
            // custom
            QueryParameters\PaginationStrategy::class,

            // search
            QueryParameters\Search\IndexStrategy::class,
            QueryParameters\Search\Address\IndexStrategy::class,
            QueryParameters\Search\Place\IndexStrategy::class,
            QueryParameters\Search\City\IndexStrategy::class,

            QueryParameters\Listings\TimesStrategy::class,

            //QueryParameters\Auth\Socialite\GoogleStrategy::class,
            //QueryParameters\Auth\Socialite\FacebookStrategy::class,
            //QueryParameters\Auth\Socialite\AppleStrategy::class,
        ],
        'headers' => [
            \Knuckles\Scribe\Extracting\Strategies\Headers\GetFromRouteRules::class,
            \Knuckles\Scribe\Extracting\Strategies\Headers\GetFromHeaderTag::class,
            // custom
            Headers\PostStrategy::class,
            Headers\PutStrategy::class,
            Headers\UserStrategy::class,
        ],
        'bodyParameters' => [
            \Knuckles\Scribe\Extracting\Strategies\BodyParameters\GetFromFormRequest::class,
            \Knuckles\Scribe\Extracting\Strategies\BodyParameters\GetFromBodyParamTag::class,
            // custom
            BodyParameters\Index\FeedbackStrategy::class,
            BodyParameters\Index\HostRequestStrategy::class,
            BodyParameters\Index\Payout\Connect\SuccessStrategy::class,
            BodyParameters\Reservations\StoreStrategy::class,

            BodyParameters\Auth\LoginStrategy::class,
            BodyParameters\Auth\RegisterStrategy::class,
            BodyParameters\Auth\ForgotPasswordStrategy::class,
            BodyParameters\Auth\ResetPasswordStrategy::class,
            BodyParameters\Auth\Phone\CodeStrategy::class,
            BodyParameters\Auth\Phone\VerifyStrategy::class,
            BodyParameters\Auth\Verify\Failed::class,
            BodyParameters\Auth\Verify\Success::class,
            BodyParameters\Listings\StoreStrategy::class,
            BodyParameters\Listings\TimesStrategy::class,

            // user
            BodyParameters\User\UpdateStrategy::class,
            BodyParameters\User\Listings\StoreStrategy::class,
            BodyParameters\User\Listings\UpdateStrategy::class,
            BodyParameters\User\Settings\Notifications\UpdateStrategy::class,
            BodyParameters\User\Listings\Calendar\UpdateStrategy::class,

            // user
            BodyParameters\User\Saves\StoreStrategy::class,
            BodyParameters\User\Favorites\ToggleStrategy::class,
            BodyParameters\User\Notifications\IndexStrategy::class,

            BodyParameters\User\Reservations\StoreStrategy::class,
            BodyParameters\User\Reservations\Review\StoreStrategy::class,
            BodyParameters\User\Reservations\PaymentStrategy::class,

            BodyParameters\User\Devices\IndexStrategy::class,
            BodyParameters\User\Devices\StoreStrategy::class,
            BodyParameters\User\Devices\DestroyStrategy::class,

            BodyParameters\User\Chats\StoreStrategy::class,
            BodyParameters\User\Chats\Messages\StoreStrategy::class,
            BodyParameters\User\Chats\Messages\IndexStrategy::class,

            BodyParameters\User\Verifications\Identities\StoreStrategy::class,
            BodyParameters\User\Verifications\Identities\UpdateStrategy::class,
            BodyParameters\User\Verifications\Identities\Step\UploadStrategy::class,

            BodyParameters\User\Payments\Cards\StoreStrategy::class,
            BodyParameters\User\Payments\Cards\UpdateStrategy::class,

            BodyParameters\Auth\Socialite\GoogleStrategy::class,
            BodyParameters\Auth\Socialite\FacebookStrategy::class,
            BodyParameters\Auth\Socialite\AppleStrategy::class,
        ],
        'responses' => [
            \Knuckles\Scribe\Extracting\Strategies\Responses\UseTransformerTags::class,
            \Knuckles\Scribe\Extracting\Strategies\Responses\UseResponseTag::class,
            \Knuckles\Scribe\Extracting\Strategies\Responses\UseResponseFileTag::class,
            \Knuckles\Scribe\Extracting\Strategies\Responses\UseApiResourceTags::class,
            \Knuckles\Scribe\Extracting\Strategies\Responses\ResponseCalls::class,
            // custom
            Responses\Listings\ShowStrategy::class,

            // user
            Responses\Reservations\StoreStrategy::class,

            Responses\UserStrategy::class,
            Responses\User\DestroyStrategy::class,
            Responses\User\UpdateStrategy::class,
            Responses\User\Listings\StoreStrategy::class,
            Responses\User\Listings\UpdateStrategy::class,
            Responses\User\Listings\DestroyStrategy::class,
            Responses\User\Listings\Image\MainStrategy::class,
            Responses\User\Listings\Image\DestroyStrategy::class,
            Responses\User\Image\DestroyStrategy::class,
            Responses\User\Social\DestroyStrategy::class,
            Responses\User\Settings\Notifications\UpdateStrategy::class,
            Responses\User\Listings\Calendar\UpdateStrategy::class,

            Responses\User\Saves\StoreStrategy::class,
            Responses\User\Saves\DestroyStrategy::class,
            Responses\User\Saves\ShowStrategy::class,
            Responses\User\Favorites\ToggleStrategy::class,

            Responses\User\Reservations\StoreStrategy::class,
            Responses\User\Reservations\Review\StoreStrategy::class,
            Responses\User\Reservations\PaymentStrategy::class,
            Responses\User\Reservations\DeclineStrategy::class,
            Responses\User\Reservations\CancelStrategy::class,

            Responses\User\Notifications\DestroyStrategy::class,
            Responses\User\Notifications\ClearStrategy::class,

            Responses\User\Payments\Cards\StoreStrategy::class,
            Responses\User\Payments\Cards\DestroyStrategy::class,
            Responses\User\Payments\Cards\UpdateStrategy::class,

            Responses\User\Payments\Stripe\EphemeralStrategy::class,

            Responses\User\Payouts\Stripe\ConnectStrategy::class,
            Responses\User\Payouts\Stripe\DashboardStrategy::class,

            Responses\User\Chats\StoreStrategy::class,

            Responses\User\Verifications\Identities\StoreStrategy::class,
            Responses\User\Verifications\Identities\UpdateStrategy::class,
            Responses\User\Verifications\Identities\Step\UploadStrategy::class,

            Responses\Auth\LoginStrategy::class,
            Responses\Auth\RegisterStrategy::class,
            Responses\Auth\ForgotPasswordStrategy::class,
            Responses\Auth\ResetPasswordStrategy::class,
            Responses\Auth\Phone\CodeStrategy::class,
            Responses\Auth\Phone\VerifyStrategy::class,
            Responses\Auth\Verify\Failed::class,
            Responses\Auth\Verify\Success::class,
            Responses\Listings\StoreStrategy::class,
            Responses\Listings\UpdateStrategy::class,
            Responses\Listings\Image\MainStrategy::class,
            Responses\Listings\Image\DestroyStrategy::class,

            // search
            Responses\Search\Address\IndexStrategy::class,
            Responses\Search\Place\IndexStrategy::class,
            Responses\Search\City\IndexStrategy::class,
        ],
        'responseFields' => [
            \Knuckles\Scribe\Extracting\Strategies\ResponseFields\GetFromResponseFieldTag::class,
            // custom
            ResponseFields\Index\DataStrategy::class,
            ResponseFields\Index\DataSubjectStrategy::class,
            ResponseFields\Index\FaqStrategy::class,
            ResponseFields\Listings\ShowStrategy::class,
            ResponseFields\Listings\SimilarStrategy::class,
            ResponseFields\Listings\ChatStrategy::class,

            ResponseFields\Reservations\StoreStrategy::class,

            // user
            ResponseFields\UserStrategy::class,
            ResponseFields\User\Listings\IndexStrategy::class,
            ResponseFields\User\Listings\ShowStrategy::class,
            ResponseFields\User\Listings\CalendarStrategy::class,
            ResponseFields\User\Listings\Images\IndexStrategy::class,
            ResponseFields\User\ShowStrategy::class,
            ResponseFields\User\BalanceStrategy::class,

            ResponseFields\Host\ShowStrategy::class,
            ResponseFields\Guest\ShowStrategy::class,

            ResponseFields\User\Reservations\IndexStrategy::class,
            ResponseFields\User\Reservations\ShowStrategy::class,
            ResponseFields\User\Reservations\StoreStrategy::class,

            ResponseFields\User\Payments\IndexStrategy::class,
            ResponseFields\User\Payments\Cards\IndexStrategy::class,

            ResponseFields\User\Payouts\IndexStrategy::class,
            ResponseFields\User\Payouts\Stripe\ConnectStrategy::class,
            ResponseFields\User\Payouts\Stripe\DashboardStrategy::class,

            ResponseFields\User\Notifications\IndexStrategy::class,
            ResponseFields\User\Reviews\IndexStrategy::class,

            ResponseFields\User\Devices\IndexStrategy::class,

            ResponseFields\User\Saves\IndexStrategy::class,
            ResponseFields\User\Saves\ShowStrategy::class,
            ResponseFields\User\Favorites\IndexStrategy::class,

            ResponseFields\User\Chats\IndexStrategy::class,
            ResponseFields\User\Chats\StoreStrategy::class,
            ResponseFields\User\Chats\Messages\IndexStrategy::class,

            ResponseFields\User\Verifications\Identities\ShowStrategy::class,
            ResponseFields\User\Verifications\Identities\StoreStrategy::class,

            ResponseFields\Auth\LoginStrategy::class,
            ResponseFields\Auth\RegisterStrategy::class,
            ResponseFields\Auth\ResetPasswordStrategy::class,
            ResponseFields\Auth\Phone\VerifyStrategy::class,
            ResponseFields\Auth\Socialite\GoogleStrategy::class,
            ResponseFields\Auth\Socialite\FacebookStrategy::class,
            ResponseFields\Auth\Socialite\AppleStrategy::class,

            // search
            ResponseFields\Search\IndexStrategy::class,
            ResponseFields\Search\Address\IndexStrategy::class,
            ResponseFields\Search\Place\IndexStrategy::class,
            ResponseFields\Search\City\IndexStrategy::class,
        ],
    ],

    /*
     * [Advanced usage] If you would like to customize how routes are matched beyond the route configuration you may
     * declare your own implementation of RouteMatcherInterface
     *
     */
    'routeMatcher' => \Knuckles\Scribe\Matching\RouteMatcher::class,

    /**
     * [Advanced usage] If one of your app's database drivers does not support transactions,
     * docs generation (instantiating Eloquent models and making response calls) will likely fail.
     * To avoid that, you can add the driver class name here.
     * Be warned: that means all database changes will persist.
     */
    'continue_without_database_transactions' => [],
];
