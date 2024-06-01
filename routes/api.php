<?php

declare(strict_types=1);

use Dingo\Api\Routing\Router;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use App\Http\Controllers\Api\IndexController;
use App\Http\Controllers\Api\Index;
use App\Http\Controllers\Api\Listings;
use App\Http\Controllers\Api\Reservations;
use App\Http\Controllers\Api\Search;
use App\Http\Controllers\Api\User;
use App\Http\Controllers\Api\UserShow;
use App\Http\Controllers\Api\Host;
use App\Http\Controllers\Api\Guest;
use App\Http\Controllers\Api\Webhooks;
use App\Http\Controllers\Api\Auth\Verify;
use App\Http\Controllers\Api\Auth\Phone;
use App\Http\Controllers\Api\Auth\Password;
use App\Http\Kernel as KernelMiddleware;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\HostController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\Auth\Socialite;
use App\Http\Controllers\Api\Auth\Sanctum;
use App\Http\Controllers\Api\Dev;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

Route::group([
    'domain' => config('api.url'),
    'as' => 'api',
], function () use ($api) {
    $api->version('v1',
        function (Router $api) {
            $api->group(['prefix' => config('sanctum.prefix', 'sanctum'), 'middleware' => 'web', 'as' => 'sanctum'], function (Router $api) {
                $api->get('/csrf-cookie', ['as' => 'csrf', 'uses' => '\\' . CsrfCookieController::class . '@show']);

                /**
                 * Авторизация
                 */
                $api->group(['prefix' => 'auth', 'as' => 'auth'], function (Router $api) {
                    $api->get('/facebook/callback', ['as' => 'facebook.callback', 'uses' => Sanctum\Facebook::class]);
                    $api->get('/google/callback', ['as' => 'google.callback', 'uses' => Sanctum\Google::class]);
                    $api->get('/apple/callback', ['as' => 'apple.callback', 'uses' => Sanctum\Apple::class]);
                });
            });
            $api->get('/', ['as' => 'index', 'uses' => '\\' . IndexController::class . '@index']);


            $api->group([
                'middleware' => ['api'],
                'as' => 'api',
            ], function (Router $api) {
                /**
                 * Общие
                 */
                $api->get('/docs', ['as' => 'docs', 'uses' => '\\' . IndexController::class . '@docs']);
                $api->get('/keys', ['as' => 'keys', 'uses' => '\\' . IndexController::class . '@keys']);

                $api->group(['prefix' => 'dev', 'as' => 'dev'], function (Router $api) {
                    $api->get('/cron/run', ['as' => 'cron.run', 'uses' => Dev\Cron\Run::class]);
                });

                $api->group(['prefix' => 'webhooks', 'as' => 'webhooks'], function (Router $api) {
                    $api->any('/hostfully', ['as' => 'hostfully', 'uses' => Webhooks\Hostfully::class]);
                });


                /**
                 * -------------------
                 * AUTH
                 * -------------------
                 */
                $api->group(['prefix' => 'auth', 'as' => 'auth'], function (Router $api) {
                    $api->post('/login', ['as' => 'login', 'uses' => '\\' . LoginController::class . '@login']);
                    $api->post('/register', ['as' => 'register', 'uses' => '\\' . RegisterController::class . '@register']);
                    $api->post('/password/email', ['as' => 'password.email', 'uses' => '\\' . ForgotPasswordController::class . '@sendResetLinkEmail']);
                    $api->post('/password/reset', ['as' => 'password.reset', 'uses' => '\\' . ResetPasswordController::class . '@reset']);

                    //$api->post('/password/phone', ['as' => 'password.phone', 'uses' => Password\Phone::class]);

                    $api->group(['prefix' => 'verify', 'as' => 'verify'], function (Router $api) {
                        $api->post('/failed', ['as' => 'failed', 'uses' => Verify\Failed::class]);
                        $api->post('/success', ['as' => 'success', 'uses' => Verify\Success::class]);
                    });

                    $api->group(['prefix' => 'phone', 'as' => 'phone'], function (Router $api) {
                        $api->post('/code', ['as' => 'code', 'uses' => Phone\Code::class]);
                        $api->post('/verify', ['as' => 'verify', 'uses' => Phone\Verify::class]);
                    });

                    ///**
                    // * Авторизация
                    // */
                    $api->group(['prefix' => 'socialite', 'as' => 'socialite'], function (Router $api) {
                        $api->get('/facebook/callback', ['as' => 'facebook.callback', 'uses' => Socialite\Facebook::class]);
                        $api->get('/google/callback', ['as' => 'google.callback', 'uses' => Socialite\Google::class]);
                        $api->get('/apple/callback', ['as' => 'apple.callback', 'uses' => Socialite\Apple::class]);
                        $api->get('/mock/callback', ['as' => 'mock.callback', 'uses' => Socialite\Mock::class]);
                        $api->get('/mock-second/callback', ['as' => 'mock-second.callback', 'uses' => Socialite\MockSecond::class]);
                    });
                });

                /**
                 * Общие
                 */
                $api->any('/', ['as' => 'index', 'uses' => '\\' . IndexController::class . '@index']);
                $api->get('/data', ['as' => 'data', 'uses' => Index\Data::class]);

                $api->get('/data/{subject}', ['as' => 'data.subject', 'uses' => Index\DataSubject::class]);

                $api->get('/faq', ['as' => 'faq', 'uses' => Index\Faq::class]);
                $api->post('/logout', ['as' => 'logout', 'uses' => Index\Logout::class]);
                $api->post('/feedback', ['as' => 'feedback', 'uses' => Index\Feedback::class]);
                $api->post('/host-request', ['as' => 'host-request', 'uses' => Index\HostRequest::class]);

                //
                $api->post('/payout/connect/success', ['as' => 'payout.connect.success', 'uses' => Index\Payout\Connect\Success::class]);
                $api->get('/autohost/callback', ['as' => 'autohost.callback', 'uses' => Index\Autohost\Callback::class]);

                /**
                 * -------------------
                 * SEARCH
                 * -------------------
                 */
                $api->group(['prefix' => 'search', 'as' => 'search'], function (Router $api) {
                    $api->get('/', ['as' => 'index', 'uses' => Search\Index::class]);
                    $api->get('/address', ['as' => 'address', 'uses' => Search\Address\Index::class]);
                    $api->get('/place', ['as' => 'place', 'uses' => Search\Place\Index::class]);
                    $api->get('/city', ['as' => 'city', 'uses' => Search\City\Index::class]);
                });

                /**
                 * -------------------
                 * LISTINGS
                 * -------------------
                 */
                $api->group(['prefix' => 'listings', 'as' => 'listings'], function (Router $api) {
                    //$api->get('/', ['as' => 'index', 'uses' => Listings\Index::class]);
                    $api->get('/{id}', ['as' => 'show', 'uses' => Listings\Show::class]);
                    $api->get('/{id}/times', ['as' => 'times', 'uses' => Listings\Times::class]);
                    $api->get('/{id}/similar', ['as' => 'similar', 'uses' => Listings\Similar::class]);

                    $api->get('/{id}/reviews', ['as' => 'reviews.index', 'uses' => Listings\Reviews\Index::class]);

                    $api->group(['middleware' => [config('api.auth_middleware')]], function (Router $api) {
                        $api->get('/{id}/chat', ['as' => 'chat', 'uses' => Listings\Chat::class]);
                    });
                });

                /**
                 * -------------------
                 * RESERVATIONS
                 * -------------------
                 */
                $api->group(['prefix' => 'reservations', 'as' => 'reservations'], function (Router $api) {
                    $api->post('/', ['as' => 'store', 'uses' => Reservations\Store::class]);
                });

                /**
                 * -------------------
                 * USER
                 * -------------------
                 */
                $api->group(['prefix' => 'user', 'as' => 'user', 'middleware' => [config('api.auth_middleware')]], function (Router $api) {
                    $api->get('/', ['as' => 'index', 'uses' => User\Index::class]);
                    $api->get('/balance', ['as' => 'balance', 'uses' => User\Balance::class]);

                    $api->get('/reviews', ['as' => 'reviews.index', 'uses' => User\Reviews\Index::class]);
                    //$api->get('/calendar', ['as' => 'calendar.index', 'uses' => User\Calendar\Index::class]);

                    $api->post('/settings/notifications', ['as' => 'settings.notifications.update', 'uses' => User\Settings\Notifications\Update::class]);

                    $api->delete('/social/{provider}', ['as' => 'social.destroy', 'uses' => User\Social\Destroy::class]);


                    $api->match(['put', 'post'], '/', ['as' => 'update', 'uses' => User\Update::class]);
                    $api->delete('/image', ['as' => 'image.destroy', 'uses' => User\Image\Destroy::class]);
                    $api->delete('/', ['as' => 'destroy', 'uses' => User\Destroy::class]);

                    $api->group(['prefix' => 'verifications', 'as' => 'verifications'], function (Router $api) {
                        $api->group(['prefix' => 'identities', 'as' => 'identities'], function (Router $api) {
                            $api->post('/', ['as' => 'store', 'uses' => User\Verifications\Identities\Store::class]);
                            $api->get('/{id}', ['as' => 'show', 'uses' => User\Verifications\Identities\Show::class]);
                            $api->match(['put', 'post'], '/{id}', ['as' => 'update', 'uses' => User\Verifications\Identities\Update::class]);
                            $api->delete('/{id}', ['as' => 'update', 'uses' => User\Verifications\Identities\Destroy::class]);

                            $api->post('/{id}/{step}/upload', ['as' => 'step.upload', 'uses' => User\Verifications\Identities\Step\Upload::class]);
                        });
                        $api->post('/verified', ['as' => 'verified', 'uses' => User\Verifications\Verified::class]);
                    });

                    $api->group(['prefix' => 'dev', 'as' => 'dev'], function (Router $api) {
                        $api->post('/notification', ['as' => 'notification', 'uses' => User\Dev\Notification::class]);

                        $api->group(['prefix' => 'notifications', 'as' => 'notifications'], function (Router $api) {
                            $api->post('/review/guest', ['as' => 'review.guest', 'uses' => User\Dev\Notifications\Review\Guest::class]);
                            $api->post('/review/host', ['as' => 'review.host', 'uses' => User\Dev\Notifications\Review\Host::class]);
                        });
                    });

                    /**
                     * -------------------
                     * LISTINGS
                     * -------------------
                     */
                    $api->group(['prefix' => 'listings', 'as' => 'listings'], function (Router $api) {
                        $api->get('/', ['as' => 'index', 'uses' => User\Listings\Index::class]);
                        $api->post('/', ['as' => 'store', 'uses' => User\Listings\Store::class]);
                        $api->get('/{id}', ['as' => 'show', 'uses' => User\Listings\Show::class]);
                        $api->match(['put', 'post'], '/{id}', ['as' => 'update', 'uses' => User\Listings\Update::class]);
                        $api->delete('/{id}', ['as' => 'destroy', 'uses' => User\Listings\Destroy::class]);

                        $api->get('/{id}/calendar', ['as' => 'calendar.index', 'uses' => User\Listings\Calendar\Index::class]);
                        $api->post('/{id}/calendar', ['as' => 'calendar.update', 'uses' => User\Listings\Calendar\Update::class]);

                        $api->get('/{id}/images', ['as' => 'images.index', 'uses' => User\Listings\Images\Index::class]);
                        $api->delete('/{id}/image/{image_id}', ['as' => 'image.destroy', 'uses' => User\Listings\Image\Destroy::class]);
                        $api->post('/{id}/image/{image_id}/main', ['as' => 'image.main', 'uses' => User\Listings\Image\Main::class]);
                    });

                    /**
                     * -------------------
                     * SAVES
                     * -------------------
                     */
                    $api->group(['prefix' => 'saves', 'as' => 'saves'], function (Router $api) {
                        $api->get('/', ['as' => 'index', 'uses' => User\Saves\Index::class]);
                        $api->post('/', ['as' => 'store', 'uses' => User\Saves\Store::class]);
                        $api->get('/{id}', ['as' => 'show', 'uses' => User\Saves\Show::class]);
                        $api->delete('/{id}', ['as' => 'destroy', 'uses' => User\Saves\Destroy::class]);
                    });

                    /**
                     * -------------------
                     * FAVORITES
                     * -------------------
                     */
                    $api->group(['prefix' => 'favorites', 'as' => 'favorites'], function (Router $api) {
                        $api->get('/', ['as' => 'index', 'uses' => User\Favorites\Index::class]);
                        $api->post('/toggle', ['as' => 'toggle', 'uses' => User\Favorites\Toggle::class]);
                    });

                    /**
                     * -------------------
                     * PAYMENTS
                     * -------------------
                     */
                    $api->group(['prefix' => 'payments', 'as' => 'payments'], function (Router $api) {
                        $api->get('/', ['as' => 'index', 'uses' => User\Payments\Index::class]);

                        $api->group(['prefix' => 'cards', 'as' => 'cards'], function (Router $api) {
                            $api->get('/', ['as' => 'index', 'uses' => User\Payments\Cards\Index::class]);
                            $api->post('/', ['as' => 'store', 'uses' => User\Payments\Cards\Store::class]);
                            //$api->get('/{id}', ['as' => 'show', 'uses' => User\Payments\Cards\Show::class]);
                            $api->match(['put', 'post'], '/{id}', ['as' => 'update', 'uses' => User\Payments\Cards\Update::class]);
                            $api->delete('/{id}', ['as' => 'destroy', 'uses' => User\Payments\Cards\Destroy::class]);
                        });
                        $api->group(['prefix' => 'stripe', 'as' => 'stripe'], function (Router $api) {
                            $api->get('/ephemeral', ['as' => 'ephemeral', 'uses' => User\Payments\Stripe\Ephemeral::class]);
                        });
                    });

                    /**
                     * -------------------
                     * PAYOUTS
                     * -------------------
                     */
                    $api->group(['prefix' => 'payouts', 'as' => 'payouts'], function (Router $api) {
                        $api->get('/', ['as' => 'index', 'uses' => User\Payouts\Index::class]);

                        $api->group(['prefix' => 'stripe', 'as' => 'stripe'], function (Router $api) {
                            $api->get('/connect', ['as' => 'connect', 'uses' => User\Payouts\Stripe\Connect::class]);
                            $api->get('/dashboard', ['as' => 'dashboard', 'uses' => User\Payouts\Stripe\Dashboard::class]);
                        });
                    });

                    /**
                     * -------------------
                     * RESERVATIONS
                     * -------------------
                     */
                    $api->group(['prefix' => 'reservations', 'as' => 'reservations'], function (Router $api) {
                        $api->get('/', ['as' => 'index', 'uses' => User\Reservations\Index::class]);
                        $api->post('/', ['as' => 'store', 'uses' => User\Reservations\Store::class]);

                        $api->get('/{id}', ['as' => 'show', 'uses' => User\Reservations\Show::class]);

                        $api->post('/{id}/cancel', ['as' => 'cancel', 'uses' => User\Reservations\Cancel::class]);
                        $api->post('/{id}/decline', ['as' => 'decline', 'uses' => User\Reservations\Decline::class]);

                        $api->get('/{id}/review', ['as' => 'review.index', 'uses' => User\Reservations\Review\Index::class]);
                        $api->post('/{id}/review', ['as' => 'review.store', 'uses' => User\Reservations\Review\Store::class]);

                        $api->post('/{id}/payment', ['as' => 'payment', 'uses' => User\Reservations\Payment::class]);
                    });

                    /**
                     * -------------------
                     * NOTIFICATIONS
                     * -------------------
                     */
                    $api->group(['prefix' => 'notifications', 'as' => 'notifications'], function (Router $api) {
                        $api->get('/', ['as' => 'index', 'uses' => User\Notifications\Index::class]);
                        $api->post('/clear', ['as' => 'clear', 'uses' => User\Notifications\Clear::class]);
                        $api->delete('/{id}', ['as' => 'destroy', 'uses' => User\Notifications\Destroy::class]);
                    });

                    /**
                     * -------------------
                     * DEVICES
                     * -------------------
                     */
                    $api->group(['prefix' => 'devices', 'as' => 'devices'], function (Router $api) {
                        $api->get('/', ['as' => 'index', 'uses' => User\Devices\Index::class]);
                        $api->post('/', ['as' => 'store', 'uses' => User\Devices\Store::class]);
                        $api->delete('/', ['as' => 'destroy', 'uses' => User\Devices\Destroy::class]);
                    });

                    /**
                     * -------------------
                     * DEVICES
                     * -------------------
                     */
                    $api->group(['prefix' => 'chats', 'as' => 'chats'], function (Router $api) {
                        $api->get('/', ['as' => 'index', 'uses' => User\Chats\Index::class]);
                        $api->post('/', ['as' => 'store', 'uses' => User\Chats\Store::class]);
                        $api->delete('/{id}', ['as' => 'destroy', 'uses' => User\Chats\Destroy::class]);

                        $api->get('/{id}/messages', ['as' => 'messages.index', 'uses' => User\Chats\Messages\Index::class]);
                        $api->post('/{id}/messages', ['as' => 'messages.store', 'uses' => User\Chats\Messages\Store::class]);
                    });
                });

                /**
                 * -------------------
                 * USER SHOW
                 * -------------------
                 */
                $api->group(['prefix' => 'user', 'as' => 'user_show'], function (Router $api) {
                    $api->get('/{id}', ['as' => 'show', 'uses' => UserShow\Show::class]);
                    $api->get('/{id}/reviews', ['as' => 'reviews.index', 'uses' => UserShow\Reviews\Index::class]);
                });

                /**
                 * -------------------
                 * HOST SHOW
                 * -------------------
                 */
                $api->group(['prefix' => 'host', 'as' => 'host'], function (Router $api) {
                    $api->get('/{id}', ['as' => 'show', 'uses' => Host\Show::class]);
                    $api->get('/{id}/reviews', ['as' => 'reviews.index', 'uses' => Host\Reviews\Index::class]);
                });

                /**
                 * -------------------
                 * GUEST SHOW
                 * -------------------
                 */
                $api->group(['prefix' => 'guest', 'as' => 'guest'], function (Router $api) {
                    $api->get('/{id}', ['as' => 'show', 'uses' => Guest\Show::class]);
                    $api->get('/{id}/reviews', ['as' => 'reviews.index', 'uses' => Guest\Reviews\Index::class]);
                });
            });
        });
});
