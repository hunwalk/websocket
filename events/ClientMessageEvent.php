<?php

declare(strict_types=1);

namespace hunwalk\websocket\events;

/**
 * Class ClientMessageEvent
 * @package hunwalk\websocket\events
 *
 * @property string $message
 */
class ClientMessageEvent extends ClientEvent
{
    /**
     * @var string $message
     */
    private $_message;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->_message;
    }

    /**
     * @param $message
     */
    public function setMessage($message): void
    {
        $this->_message = $message;
    }
}