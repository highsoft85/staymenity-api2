<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\Auth\RegisteredEvent;
use App\Events\Auth\ResetPasswordEvent;
use App\Events\ChangeCacheEvent;
use App\Events\Reservation\ReservationSuccessEvent;
use App\Events\Reservation\ReservationSyncToEvent;
use App\Events\TestEvent;
use App\Events\Webhook\WebhookSyncToEvent;
use App\Listeners\Auth\RegisteredListener;
use App\Listeners\Auth\ResetPasswordListener;
use App\Listeners\ChangeCacheListener;
use App\Listeners\DbSnapshots\CreatedSnapshotListener;
use App\Listeners\Reservation\ReservationSuccessListener;
use App\Listeners\Reservation\ReservationSyncToListener;
use App\Listeners\TestListener;
use App\Listeners\Webhook\WebhookSyncToListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RegisteredEvent::class => [
            RegisteredListener::class,
        ],
        ResetPasswordEvent::class => [
            ResetPasswordListener::class,
        ],
        ReservationSuccessEvent::class => [
            ReservationSuccessListener::class,
        ],
//        Registered::class => [
//            SendEmailVerificationNotification::class,
//        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\\Facebook\\FacebookExtendSocialite@handle',
            'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
            'SocialiteProviders\\Apple\\AppleExtendSocialite@handle',
        ],
        ChangeCacheEvent::class => [
            ChangeCacheListener::class,
        ],
        \Spatie\DbSnapshots\Events\CreatedSnapshot::class => [
            CreatedSnapshotListener::class,
        ],
        ReservationSyncToEvent::class => [
            ReservationSyncToListener::class,
        ],
        WebhookSyncToEvent::class => [
            WebhookSyncToListener::class,
        ],
        TestEvent::class => [
            TestListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
