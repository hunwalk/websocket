<?php

declare(strict_types=1);

namespace hunwalk\websocket\events;

use Ratchet\ConnectionInterface;
use yii\base\Event;

/**
 * Class ClientEvent
 * @package hunwalk\websocket\events
 *
 * @property ConnectionInterface $client
 */
class ClientEvent extends Event
{
    /**
     * @var ConnectionInterface $_client
     */
    private $_client;

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