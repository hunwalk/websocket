<?php
/**
 * This package was originally maintained by Sergey Poltaranin
 * Due to to the fact that the repo is abandoned i recreated it.
 * You can find the old repo here: https://github.com/consik/yii2-websocket
 */

declare(strict_types=1);

namespace hunwalk\websocket;

use Exception;
use hunwalk\websocket\events\ExceptionEvent;
use hunwalk\websocket\traits\WebsocketEventTrait;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use SplObjectStorage;
use yii\base\Component;
use yii\base\InvalidConfigException;

class WebSocketServer extends Component implements MessageComponentInterface
{
    use WebsocketEventTrait;

    /**
     * @event yii\base\Event Triggered when binding is successfully completed
     */
    const EVENT_WEBSOCKET_OPEN = 'ws_open';

    /**
     * @event yii\base\Event Triggered when socket listening is closed
     */
    const EVENT_WEBSOCKET_CLOSE = 'ws_close';

    /**
     * @event ExceptionEvent Triggered when throwed Exception on binding socket
     */
    const EVENT_WEBSOCKET_OPEN_ERROR = 'ws_open_error';

    /**
     * @event ClientEvent Triggered when client connected to the server
     */
    const EVENT_CLIENT_CONNECTED = 'ws_client_connected';

    /**
     * @event WSClientErrorEvent Triggered when an error occurs on a Connection
     */
    const EVENT_CLIENT_ERROR = 'ws_client_error';

    /**
     * @event ClientEvent Triggered when client close connection with server
     */
    const EVENT_CLIENT_DISCONNECTED = 'ws_client_disconnected';

    /**
     * @event WSClientMessageEvent Triggered when message recieved from client
     */
    const EVENT_CLIENT_MESSAGE = 'ws_client_message';

    /**
     * @var int $port
     */
    public $port = 8080;

    /**
     * @var bool $closeConnectionOnError
     */
    protected $closeConnectionOnError = true;

    /**
     * @var IoServer|null $server
     */
    protected $server = null;

    /**
     * @var null|SplObjectStorage $clients
     */
    protected $clients = null;

    /**
     * @return bool
     * @event yii\base\Event EVENT_WEBSOCKET_OPEN
     * @event ExceptionEvent EVENT_WEBSOCKET_OPEN_ERROR
     * @throws InvalidConfigException
     */
    public function start(): bool
    {
        try {
            $this->server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        $this
                    )
                ),
                $this->port
            );
            $this->trigger(self::EVENT_WEBSOCKET_OPEN);
            $this->clients = new SplObjectStorage();
            $this->server->run();

            return true;

        } catch (Exception $e) {
            $event = $this->getExceptionEvent($e);
            $this->trigger(self::EVENT_WEBSOCKET_OPEN_ERROR, $event);
            return false;
        }
    }

    /**
     * @return void
     * @event yii\base\Event EVENT_WEBSOCKET_CLOSE
     */
    public function stop(): void
    {
        $this->server->socket->close();
        $this->trigger(self::EVENT_WEBSOCKET_CLOSE);
    }

    /**
     * @param ConnectionInterface $conn
     * @throws InvalidConfigException
     * @event ClientEvent EVENT_CLIENT_CONNECTED
     */
    function onOpen(ConnectionInterface $conn): void
    {
        $event = $this->getClientEvent($conn);
        $this->trigger(self::EVENT_CLIENT_CONNECTED, $event);

        $this->clients->attach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @throws InvalidConfigException
     * @event ClientEvent EVENT_CLIENT_DISCONNECTED
     */
    function onClose(ConnectionInterface $conn): void
    {
        $event = $this->getClientEvent($conn);
        $this->trigger(self::EVENT_CLIENT_DISCONNECTED, $event);

        $this->clients->detach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param Exception $e
     * @throws InvalidConfigException
     * @event ClientErrorEvent EVENT_CLIENT_ERROR
     */
    function onError(ConnectionInterface $conn, Exception $e): void
    {
        $event = $this->getClientErrorEvent($conn, $e);
        $this->trigger(self::EVENT_CLIENT_ERROR, $event);

        if ($this->closeConnectionOnError) {
            $conn->close();
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     * @throws InvalidConfigException
     * @event ClientMessageEvent EVENT_CLIENT_MESSAGE
     */
    function onMessage(ConnectionInterface $from, $msg): void
    {
        $event = $this->getClientMessageEvent($from, $msg);
        $this->trigger(self::EVENT_CLIENT_MESSAGE, $event);
    }

}
