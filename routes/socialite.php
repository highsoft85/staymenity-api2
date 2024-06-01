<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Socialite\SocialAuthGoogleController;
use App\Http\Controllers\Socialite\SocialAuthFacebookController;
use App\Http\Controllers\Socialite\SocialAuthAppleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'domain' => config('app.url'),
    'middleware' => [],
    'as' => 'socialite.',
    'prefix' => 'auth',
], function () {
    Route::group(['prefix' => 'google', 'as' => 'google.'], function () {
        Route::get('/redirect', ['uses' => '\\' . SocialAuthGoogleController::class . '@redirectToProvider', 'as' => 'redirect']);
        Route::get('/callback', ['uses' => '\\' . SocialAuthGoogleController::class . '@handleProviderCallback', 'as' => 'callback']);
    });
    Route::group(['prefix' => 'facebook', 'as' => 'facebook.'], function () {
        Route::get('/redirect', ['uses' => '\\' . SocialAuthFacebookController::class . '@redirectToProvider', 'as' => 'redirect']);
        Route::get('/callback', ['uses' => '\\' . SocialAuthFacebookController::class . '@handleProviderCallback', 'as' => 'callback']);
    });
    Route::group(['prefix' => 'apple', 'as' => 'apple.'], function () {
        Route::get('/redirect', ['uses' => '\\' . SocialAuthAppleController::class . '@redirectToProvider', 'as' => 'redirect']);
        Route::get('/callback', ['uses' => '\\' . SocialAuthAppleController::class . '@handleProviderCallback', 'as' => 'callback']);
    });
});
