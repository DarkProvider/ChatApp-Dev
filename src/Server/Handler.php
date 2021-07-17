<?php declare(strict_types=1);

namespace Server;

use Clients\Client;

/**
 * Class Handler
 * @package Server
 */
class Handler
{
    /**
     * Handles all incoming messages from clients.
     * @param string $data
     * @param int|null $id
     * @return void
     */
    public static function handle(string $data, int $id = null): void
    {
        $decoded = json_decode($data);

        switch ($decoded->type) {
            case 'connect' :
                Client::connected($id, $decoded->username);
                break;

            case 'message' :
                Client::sendMessage($decoded->chat, $decoded->from, $decoded->to, $decoded->message);
                break;

            default :
                echo 'Unrecognized type';
                break;
        }
    }
}