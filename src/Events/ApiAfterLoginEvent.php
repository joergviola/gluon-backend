<?php

namespace Gluon\Backend\Events;

class ApiAfterLoginEvent
{
    public $user;

    public function __construct(&$user) {
        $this->user = $user;
    }
}
