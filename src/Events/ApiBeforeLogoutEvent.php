<?php

namespace Gluon\Backend\Events;

class ApiBeforeLogoutEvent
{
    public $user;

    public function __construct(&$user) {
        $this->user = $user;
    }
}
