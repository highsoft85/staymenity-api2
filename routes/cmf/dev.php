<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Cmf Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Cmf\Project\DevController;

Route::group([
    'domain' => cmfHelper()->getUrl(),
    'as' => cmfHelper()->getAs(),
    'prefix' => cmfHelper()->getPrefix(),
    'middleware' => ['auth', 'cmf', 'member'],
], function () {
    Route::get('/dev/command/{name}', ['uses' => '\\' . DevController::class . '@command', 'as' => 'dev.command.index']);
    Route::get('/dev/developer', ['uses' => '\\' . DevController::class . '@developer', 'as' => 'dev.developer.index']);
    Route::get('/dev/stripe', ['uses' => '\\' . DevController::class . '@stripe', 'as' => 'dev.stripe.index']);
    Route::post('/dev/stripe', ['uses' => '\\' . DevController::class . '@stripePost', 'as' => 'dev.stripe.post']);
    Route::get('/dev/push', ['uses' => '\\' . DevController::class . '@push', 'as' => 'dev.push.index']);
    Route::get('/dev/firebase', ['uses' => '\\' . DevController::class . '@firebase', 'as' => 'dev.firebase.index']);
    Route::get('/dev/auth', ['uses' => '\\' . DevController::class . '@auth', 'as' => 'dev.auth.index']);
    Route::post('/dev/push/token', ['uses' => '\\' . DevController::class . '@pushToken', 'as' => 'dev.push.token.post']);
    Route::post('/dev/push/send', ['uses' => '\\' . DevController::class . '@pushSend', 'as' => 'dev.push.send.post']);
    Route::get('/dev/keys', ['uses' => '\\' . DevController::class . '@keys', 'as' => 'dev.keys.index']);
    Route::get('/dev/queue', ['uses' => '\\' . DevController::class . '@queue', 'as' => 'dev.queue.index']);
    Route::get('/dev/notifications', ['uses' => '\\' . DevController::class . '@notifications', 'as' => 'dev.notifications.index']);
    Route::get('/dev/hostfully', ['uses' => '\\' . DevController::class . '@hostfully', 'as' => 'dev.hostfully.index']);
});
