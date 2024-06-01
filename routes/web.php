<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Api\IndexController as ApiIndexController;

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
    'as' => 'app.',
], function () {
    Route::get('/stripe', ['uses' => '\\' . IndexController::class . '@stripe', 'as' => 'stripe']);
    Route::get('/', ['uses' => '\\' . IndexController::class . '@index', 'as' => 'index']);
    Route::get('/login', ['uses' => '\\' . IndexController::class . '@login', 'as' => 'login']);
});

Route::group([
    'domain' => config('api.url'),
    'as' => 'api',
], function () {
    Route::get('/', ['uses' => '\\' . ApiIndexController::class . '@index', 'as' => 'index']);
});

/**
 * Роуты, которые перехватываются на фронте
 * Заглушки, чтобы удобно было ссылаться на них
 */
Route::group([
    'domain' => config('app.web_url'),
    'middleware' => [],
    'as' => 'web.',
], function () {
    Route::get('/messages', ['uses' => '\\' . ApiIndexController::class . '@index', 'as' => 'messages']);
    Route::get('/terms', ['uses' => '\\' . ApiIndexController::class . '@index', 'as' => 'terms']);
    Route::get('/search', ['uses' => '\\' . ApiIndexController::class . '@index', 'as' => 'search']);
    Route::get('/referer', ['uses' => '\\' . ApiIndexController::class . '@index', 'as' => 'referer']);
    // +
    Route::get('/auth/password/reset', ['uses' => '\\' . ApiIndexController::class . '@index', 'as' => 'auth.password.reset']);
    // +
    Route::get('/auth/verify/success', ['uses' => '\\' . ApiIndexController::class . '@index', 'as' => 'auth.verify.success']);
    // +
    Route::get('/auth/verify/failed', ['uses' => '\\' . ApiIndexController::class . '@index', 'as' => 'auth.verify.failed']);
    // +
    Route::get('/payout/connect/success', ['uses' => '\\' . ApiIndexController::class . '@index', 'as' => 'payout.connect.success']);
});
