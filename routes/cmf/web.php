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
use App\Cmf\Core\RouteCmf;
use App\Cmf\Project\HomeController;
use App\Cmf\Auth\LoginController;
use App\Cmf\Project\User\UserController;
use App\Cmf\Project\Listing\ListingController;
use App\Cmf\Project\Type\TypeController;
use App\Cmf\Project\Rule\RuleController;
use App\Cmf\Project\Amenity\AmenityController;
use App\Cmf\Project\Reservation\ReservationController;
use App\Cmf\Project\Balance\BalanceController;
use App\Cmf\Project\Payment\PaymentController;
use App\Cmf\Project\Payout\PayoutController;
use App\Cmf\Project\Faq\FaqController;
use App\Cmf\Project\Review\ReviewController;
use App\Cmf\Project\Feedback\FeedbackController;
use App\Cmf\Project\Request\RequestController;
use App\Cmf\Project\Option\OptionController;
use App\Cmf\Project\OptionSystemValue\OptionSystemValueController;
use App\Cmf\Project\UserIdentity\UserIdentityController;

Route::group([
    'domain' => cmfHelper()->getUrl(),
    'as' => cmfHelper()->getAs(),
    'prefix' => cmfHelper()->getPrefix(),
    'middleware' => ['cmf', 'member'],
], function () {

    Route::get('/', ['uses' => '\\' . HomeController::class . '@index', 'as' => 'index']);
    Route::get('/home', ['uses' => '\\' . HomeController::class . '@index', 'as' => 'home.index']);
    Route::get('/dashboard', ['uses' => '\\' . HomeController::class . '@index', 'as' => 'dashboard.index']);
    Route::post('/logout', ['uses' => '\\' . LoginController::class . '@logoutAdmin', 'as' => 'logout.post']);

    RouteCmf::resource(UserController::NAME);
    RouteCmf::resource(ListingController::NAME);
    RouteCmf::resource(TypeController::NAME);
    RouteCmf::resource(RuleController::NAME);
    RouteCmf::resource(AmenityController::NAME);
    RouteCmf::resource(ReservationController::NAME);
    RouteCmf::resource(BalanceController::NAME);
    RouteCmf::resource(PaymentController::NAME);
    RouteCmf::resource(FaqController::NAME);
    RouteCmf::resource(ReviewController::NAME);
    RouteCmf::resource(FeedbackController::NAME);
    RouteCmf::resource(OptionController::NAME);
    RouteCmf::resource(OptionSystemValueController::NAME);
    RouteCmf::resource(PayoutController::NAME);
    RouteCmf::resource(UserIdentityController::NAME);
    RouteCmf::resource(RequestController::NAME);

    Route::get('/{name}', ['uses' => '\\' . HomeController::class . '@unknown', 'as' => 'unknown']);
});
