<?php

namespace Gluon\Backend\Events;

class ApiBeforeCreateEvent
{
    public $user;
    public $type;
    public $item;

    public function __construct($user, $type, &$item) {
        $this->user = $user;
        $this->type = $type;
        $this->item = &$item;
    }
}