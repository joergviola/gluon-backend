<?php

namespace Gluon\Backend\Events;

class ApiAfterReadEvent
{
    public $user;
    public $type;
    public $items;

    public function __construct($user, $type, &$items) {
        $this->user = $user;
        $this->type = $type;
        $this->items = &$items;
    }
}