<?php

namespace Gluon\Backend\Providers;

use Gluon\Backend\Events\ApiAfterReadEvent;
use Gluon\Backend\Listeners\AuthenticatedEventSubscriber;
use Gluon\Backend\Listeners\DocumentEventSubscriber;
use Gluon\Backend\Listeners\NotificationsEventSubscriber;
use Gluon\Backend\Listeners\UserEventSubscriber;
use Gluon\Backend\Listeners\VersioningEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $subscribe = [
        AuthenticatedEventSubscriber::class,
        DocumentEventSubscriber::class,
//        NotificationsEventSubscriber::class,
        UserEventSubscriber::class,
        VersioningEventSubscriber::class,
    ];


    public function shouldDiscoverEvents() {
        return true;
    }

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
