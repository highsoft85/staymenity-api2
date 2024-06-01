<?php

declare(strict_types=1);

use DJStarCOM\Breadcrumbs\BreadcrumbsGenerator;
use DJStarCOM\Breadcrumbs\Facades\Breadcrumbs;
use App\Cmf\Project\User\UserController;
use App\Cmf\Project\Listing\ListingController;
use App\Cmf\Project\Type\TypeController;
use App\Cmf\Project\Rule\RuleController;
use App\Cmf\Project\Amenity\AmenityController;
use App\Cmf\Project\Reservation\ReservationController;
use App\Cmf\Project\Balance\BalanceController;
use App\Cmf\Project\Payment\PaymentController;
use App\Cmf\Project\Faq\FaqController;
use App\Cmf\Project\Review\ReviewController;
use App\Cmf\Project\Feedback\FeedbackController;
use App\Cmf\Project\Option\OptionController;
use App\Cmf\Project\OptionSystemValue\OptionSystemValueController;
use App\Cmf\Project\Payout\PayoutController;
use App\Cmf\Project\UserIdentity\UserIdentityController;
use App\Cmf\Project\Request\RequestController;

/**
 * --------------------------------------------------
 * APP
 * --------------------------------------------------
 */
Breadcrumbs::register('app.dashboard', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push(__('Home'), route('index'));
});

/**
 * --------------------------------------------------
 * ADMIN
 * --------------------------------------------------
 */
Breadcrumbs::register('dashboard', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push(__('Home'), routeCmf('dashboard.index'));
});
Breadcrumbs::register('unknown', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__('Not found'), routeCmf('dashboard.index'));
});
Breadcrumbs::register('index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push(__('Home'), routeCmf('dashboard.index'));
});
Breadcrumbs::register('home', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push(__('Home'), routeCmf('dashboard.index'));
});
Breadcrumbs::register(UserController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(UserController::TITLE), routeCmf(UserController::NAME . '.index'));
});
Breadcrumbs::register(UserController::NAME . '.' . UserController::PAGE_HOSTS, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(UserController::NAME);
    $breadcrumbs->push('Hosts', routeCmf(UserController::NAME . '.index'));
});
Breadcrumbs::register(UserController::NAME . '.' . UserController::PAGE_GUESTS, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(UserController::NAME);
    $breadcrumbs->push('Guests', routeCmf(UserController::NAME . '.index'));
});
Breadcrumbs::register(UserController::NAME . '.' . UserController::PAGE_DELETED, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(UserController::NAME);
    $breadcrumbs->push('Deleted', routeCmf(UserController::NAME . '.index'));
});
Breadcrumbs::register(UserController::NAME . '.' . UserController::PAGE_HOSTFULLY, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(UserController::NAME);
    $breadcrumbs->push('From Hostfully', routeCmf(UserController::NAME . '.index'));
});
Breadcrumbs::register(ListingController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(ListingController::TITLE), routeCmf(ListingController::NAME . '.index'));
});
Breadcrumbs::register(ListingController::NAME . '.' . ListingController::PAGE_ACTIVE, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ListingController::NAME);
    $breadcrumbs->push('Active', routeCmf(ListingController::NAME . '.index'));
});
Breadcrumbs::register(ListingController::NAME . '.' . ListingController::PAGE_POPULAR, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ListingController::NAME);
    $breadcrumbs->push('Popular', routeCmf(ListingController::NAME . '.index'));
});
Breadcrumbs::register(ListingController::NAME . '.' . ListingController::PAGE_BOOKED, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ListingController::NAME);
    $breadcrumbs->push('Booked', routeCmf(ListingController::NAME . '.index'));
});
Breadcrumbs::register(ListingController::NAME . '.' . ListingController::PAGE_DELETED, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ListingController::NAME);
    $breadcrumbs->push('Deleted', routeCmf(ListingController::NAME . '.index'));
});
Breadcrumbs::register(TypeController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(TypeController::TITLE), routeCmf(TypeController::NAME . '.index'));
});
Breadcrumbs::register(RuleController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(RuleController::TITLE), routeCmf(RuleController::NAME . '.index'));
});
Breadcrumbs::register(AmenityController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(AmenityController::TITLE), routeCmf(AmenityController::NAME . '.index'));
});
Breadcrumbs::register(ReservationController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(ReservationController::TITLE), routeCmf(ReservationController::NAME . '.index'));
});
Breadcrumbs::register(ReservationController::NAME . '.' . ReservationController::PAGE_FUTURE, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ReservationController::NAME);
    $breadcrumbs->push('Future', routeCmf(ReservationController::NAME . '.index'));
});
Breadcrumbs::register(ReservationController::NAME . '.' . ReservationController::PAGE_PROCESS, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ReservationController::NAME);
    $breadcrumbs->push('In Process', routeCmf(ReservationController::NAME . '.index'));
});
Breadcrumbs::register(ReservationController::NAME . '.' . ReservationController::PAGE_PASSED, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ReservationController::NAME);
    $breadcrumbs->push('Passed', routeCmf(ReservationController::NAME . '.index'));
});
Breadcrumbs::register(ReservationController::NAME . '.' . ReservationController::PAGE_CANCELLED, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ReservationController::NAME);
    $breadcrumbs->push('Cancelled', routeCmf(ReservationController::NAME . '.index'));
});
Breadcrumbs::register(ReservationController::NAME . '.' . ReservationController::PAGE_ACTIVE, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ReservationController::NAME);
    $breadcrumbs->push('Active', routeCmf(ReservationController::NAME . '.index'));
});
Breadcrumbs::register(ReservationController::NAME . '.' . ReservationController::PAGE_HOSTFULLY, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(ReservationController::NAME);
    $breadcrumbs->push('From Hostfully', routeCmf(ReservationController::NAME . '.index'));
});
Breadcrumbs::register(BalanceController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(BalanceController::TITLE), routeCmf(BalanceController::NAME . '.index'));
});
Breadcrumbs::register(PaymentController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(PaymentController::TITLE), routeCmf(PaymentController::NAME . '.index'));
});
Breadcrumbs::register(PaymentController::NAME . '.' . PaymentController::PAGE_ACTIVE, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(PaymentController::NAME);
    $breadcrumbs->push('Active', routeCmf(PaymentController::NAME . '.index'));
});
Breadcrumbs::register(PaymentController::NAME . '.' . PaymentController::PAGE_CANCELLED, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent(PaymentController::NAME);
    $breadcrumbs->push('Cancelled', routeCmf(PaymentController::NAME . '.index'));
});
Breadcrumbs::register(FaqController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(FaqController::TITLE), routeCmf(FaqController::NAME . '.index'));
});
Breadcrumbs::register(ReviewController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(ReviewController::TITLE), routeCmf(ReviewController::NAME . '.index'));
});
Breadcrumbs::register(FeedbackController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(FeedbackController::TITLE), routeCmf(FeedbackController::NAME . '.index'));
});
Breadcrumbs::register(OptionController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(OptionController::TITLE), routeCmf(OptionController::NAME . '.index'));
});
Breadcrumbs::register(OptionSystemValueController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(OptionSystemValueController::TITLE), routeCmf(OptionSystemValueController::NAME . '.index'));
});
Breadcrumbs::register(PayoutController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(PayoutController::TITLE), routeCmf(PayoutController::NAME . '.index'));
});
Breadcrumbs::register(UserIdentityController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(UserIdentityController::TITLE), routeCmf(UserIdentityController::NAME . '.index'));
});
Breadcrumbs::register(RequestController::NAME, function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__(RequestController::TITLE), routeCmf(RequestController::NAME . '.index'));
});

/**
 * --------------------------------------------------
 * ADMIN DEV
 * --------------------------------------------------
 */
Breadcrumbs::register('dev.stripe', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__('Stripe'), routeCmf('dev.stripe.index'));
});
Breadcrumbs::register('dev.push', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__('Push'), routeCmf('dev.push.index'));
});
Breadcrumbs::register('dev.firebase', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__('Firebase'), routeCmf('dev.firebase.index'));
});
Breadcrumbs::register('dev.auth', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Auth', routeCmf('dev.auth.index'));
});
Breadcrumbs::register('dev.keys', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Keys', routeCmf('dev.keys.index'));
});
Breadcrumbs::register('dev.notifications', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Notifications', routeCmf('dev.notifications.index'));
});
Breadcrumbs::register('dev.hostfully', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Hostfully', routeCmf('dev.hostfully.index'));
});
