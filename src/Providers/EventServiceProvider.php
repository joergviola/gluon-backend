<?php

namespace Gluon\Backend\Providers;

use Gluon\Backend\Events\ApiAfterReadEvent;
use Gluon\Backend\Listeners\DocumentEventSubscriber;
use Gluon\Backend\Listeners\UserEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $subscribe = [
        UserEventSubscriber::class,
        DocumentEventSubscriber::class,
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
