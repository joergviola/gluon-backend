<?php

namespace Gluon\Backend\Listeners;

use Gluon\Backend\Api\API;
use Gluon\Backend\Events\ApiAfterAuthenticatedEvent;

class AuthenticatedEventSubscriber
{

    public function handleQuery(ApiAfterAuthenticatedEvent $event) {
        $user = $event->user;
        $project_ids = API::provider('allocation')
            ->where('user_id', $user->id)
            ->pluck('project_id')
            ->toArray();
        $user->projects = array_values(array_filter($project_ids));
    }

    public function subscribe($events)
    {
        $events->listen(
            ApiAfterAuthenticatedEvent::class,
            'Gluon\Backend\Listeners\AuthenticatedEventSubscriber@handleQuery'
        );
    }
}
