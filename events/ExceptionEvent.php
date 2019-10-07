<?php

declare(strict_types=1);

namespace hunwalk\websocket\events;

use Exception;
use yii\base\Event;

/**
 * Class ExceptionEvent
 * I'm not really going to place here getters and setters.
 * This is fine as it is.
 * @package hunwalk\websocket\events
 */
class ExceptionEvent extends Event
{
    /**
     * @var Exception $exception
     */
    public $exception;
}