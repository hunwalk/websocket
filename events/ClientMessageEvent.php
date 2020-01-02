<?php

declare(strict_types=1);

namespace hunwalk\websocket\events;

use Ratchet\ConnectionInterface;

/**
 * Class ClientMessageEvent
 * @package hunwalk\websocket\events
 *
 * @property string $message
 */
class ClientMessageEvent extends ClientEvent
{

    /**
     * @var ConnectionInterface $_client
     */
    private $_client;

    /**
     * @var string $_message
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

    /**
     * @return ConnectionInterface
     */
    public function getClient(): ConnectionInterface
    {
        return $this->_client;
    }

    /**
     * @param ConnectionInterface $client
     */
    public function setClient(ConnectionInterface $client): void
    {
        $this->_client = $client;
    }
}