<?php

namespace Gluon\Backend\Events;

class ApiBeforeReadEvent
{
    public $user;
    public $type;
    public $query;
    public $call;

    public function __construct($user, $type, &$query, $call) {
        $this->user = $user;
        $this->type = $type;
        $this->query = &$query;
        $this->call = $call;
    }
}