<?php

namespace Gluon\Backend\Events;


class ApiAfterAuthenticatedEvent
{
    public $user;

    public function __construct(&$user) {
        $this->user = $user;
    }
}
