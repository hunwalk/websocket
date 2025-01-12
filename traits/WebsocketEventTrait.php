<?php

declare(strict_types=1);

namespace hunwalk\websocket\traits;

use Exception;
use hunwalk\websocket\events\ClientErrorEvent;
use hunwalk\websocket\events\ClientEvent;
use hunwalk\websocket\events\ClientMessageEvent;
use hunwalk\websocket\events\ExceptionEvent;
use Ratchet\ConnectionInterface;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Trait WebsocketEventTrait
 * @package hunwalk\websocket\traits
 */
trait WebsocketEventTrait
{
    /**
     * @param ConnectionInterface $client
     * @return ClientEvent|object
     * @throws InvalidConfigException
     */
    public function getClientEvent(ConnectionInterface $client)
    {
        return Yii::createObject(['class' => ClientEvent::class, 'client' => $client]);
    }

    /**
     * @param ConnectionInterface $client
     * @param string $message
     * @return ClientMessageEvent|object
     * @throws InvalidConfigException
     */
    public function getClientMessageEvent(ConnectionInterface $client, string $message)
    {
        return Yii::createObject(['class' => ClientMessageEvent::class, 'client' => $client, 'message' => $message]);
    }

    /**
     * @param ConnectionInterface $client
     * @param Exception $exception
     * @return ClientErrorEvent|object
     * @throws InvalidConfigException
     */
    public function getClientErrorEvent(ConnectionInterface $client, Exception $exception)
    {
        return Yii::createObject(['class' => ClientErrorEvent::class, 'client' => $client, 'exception' => $exception]);
    }

    /**
     * @param Exception $exception
     * @return ExceptionEvent|object
     * @throws InvalidConfigException
     */
    public function getExceptionEvent(Exception $exception): ExceptionEvent
    {
        return Yii::createObject(['class' => ExceptionEvent::class, 'exception' => $exception]);
    }
}