<?php

namespace Gluon\Backend\Api;

use Throwable;

class PermissionException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}