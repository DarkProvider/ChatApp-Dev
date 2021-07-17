<?php declare(strict_types=1);

namespace Server;

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

use Server\Handler;
use Clients\Client;

/**
 * Class ChatServer
 * @package Server
 */
class ChatServer
{
    public static Server $server;

    /**
     * ChatServer constructor.
     * @param string $hostname
     * @param int $port
     */
    public function __construct(string $hostname, int $port) {
        self::$server = new Server($hostname, $port);
    }

    /**
     * Run the chat server.
     * @return void
     */
    public static function start(): void
    {
        self::$server->on('start', function () {
            echo "Websocket server started on > 172.17.0.2:9501\n";
        });

        self::$server->on('open', function (Server $server, Request $request) {
            echo "New client connected > {$request->fd}\n";
        });

        self::$server->on('message', function (Server $server, Frame $frame) {
            echo "Received message > '{$frame->data}' from client client > {$frame->fd}\n";

            Handler::handle($frame->data, $frame->fd);
        });

        self::$server->on('close', function (Server $server, $client) {
            echo "Client > {$client} disconnected from the server.\n";

            Client::disconnected($client);
        });

        self::$server->start();
    }

    /**
     * Send message to client.
     * @param int $to
     * @param string $from
     * @param string $message
     * @return void
     */
    public static function pushMessage(int $to, string $from, string $message): void
    {
        self::$server->push($to, json_encode(['type' => 'message', 'from' => $from, 'message' => $message]));
    }
}