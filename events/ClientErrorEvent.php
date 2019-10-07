<?php

declare(strict_types=1);

namespace hunwalk\websocket\events;

use Exception;

class ClientErrorEvent extends ClientEvent
{
    /**
     * @var Exception $exception
     */
    public $exception;
}