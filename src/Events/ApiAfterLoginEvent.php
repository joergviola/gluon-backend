<?php

namespace Gluon\Backend\Events;

class ApiAfterLoginEvent
{
    public $user;
    public $params;

    public function __construct(&$user, $params) {
        $this->user = $user;
        $this->params = $params;
    }
}
