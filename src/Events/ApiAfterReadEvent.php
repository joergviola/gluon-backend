<?php

namespace Gluon\Backend\Events;

class ApiAfterReadEvent
{
    public $user;
    public $type;
    public $items;
    public $call;

    public function __construct($user, $type, &$items, $call) {
        $this->user = $user;
        $this->type = $type;
        $this->items = &$items;
        $this->call = $call;
    }
}