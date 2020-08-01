<?php

namespace Gluon\Backend\Listeners;

use Gluon\Backend\Events\ApiAfterReadEvent;
use Gluon\Backend\Events\ApiBeforeCreateEvent;
use Gluon\Backend\Events\ApiBeforeUpdateEvent;
use Illuminate\Support\Facades\Hash;

class UserEventSubscriber
{

    public function handleQuery(ApiAfterReadEvent $event) {
        if ($event->type!='users') return;

        foreach ($event->items as $user) {
            unset($user->password);
        }
    }

    public function handleUpdate(ApiBeforeUpdateEvent $event) {
        if ($event->type!='users') return;

        if (!empty($event->item['password'])) {
            $event->item['password'] = Hash::make($event->item['password']);
        } else {
            unset($event->item['password']);
        }
    }

    public function handleCreate(ApiBeforeCreateEvent $event) {
        if ($event->type!='users') return;

        if (!empty($event->item['password'])) {
            $event->item['password'] = Hash::make($event->item['password']);
        } else {
            unset($event->item['password']);
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            ApiAfterReadEvent::class,
            'Gluon\Backend\Listeners\UserEventSubscriber@handleQuery'
        );
        $events->listen(
            ApiBeforeUpdateEvent::class,
            'Gluon\Backend\Listeners\UserEventSubscriber@handleUpdate'
        );
        $events->listen(
            ApiBeforeCreateEvent::class,
            'Gluon\Backend\Listeners\UserEventSubscriber@handleCreate'
        );
    }
}
