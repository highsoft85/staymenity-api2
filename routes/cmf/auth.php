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
use App\Cmf\Auth\LoginController;
use App\Cmf\Auth\ForgotPasswordController;
use App\Cmf\Auth\ResetPasswordController;

Route::group([
    'domain' => cmfHelper('auth')->getUrl(),
    'as' => cmfHelper('auth')->getAs(),
    'prefix' => cmfHelper('auth')->getPrefix(),
    'middleware' => ['guest'],
], function () {
    Route::get('/login', ['uses' => '\\' . LoginController::class . '@showAdminLoginForm', 'as' => 'login']);
    Route::post('/login', ['uses' => '\\' . LoginController::class . '@login', 'as' => 'login.post']);
    Route::get('/password/request', ['uses' => '\\' . ForgotPasswordController::class . '@showLinkRequestForm', 'as' => 'password.request']);
    Route::post('/password/email', ['uses' => '\\' . ForgotPasswordController::class . '@sendResetLinkEmail', 'as' => 'password.email.post']);

    Route::get('/password/reset', ['uses' => '\\' . ResetPasswordController::class . '@showResetForm', 'as' => 'password.reset']);
    Route::post('/password/reset', ['uses' => '\\' . ResetPasswordController::class . '@reset', 'as' => 'password.reset.post']);
});
