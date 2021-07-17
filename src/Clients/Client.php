<?php declare(strict_types=1);

namespace Clients;

use Database\Connect;
use PDO;
use Server\ChatServer;

/**
 * Class Client
 * @package Clients
 */
class Client extends ChatServer
{
    /**
     * Add the connected client to list, so the server know who is connected.
     * @param int $id
     * @param string $username
     * @return void
     */
    public static function connected(int $id, string $username): void
    {
        $database = new Connect();
        $query = $database->connect()->prepare('INSERT INTO connected_clients (socket, username) VALUES (:socket, :username);');

        $query->bindParam(':socket', $id, PDO::PARAM_INT);
        $query->bindParam(':username', $username, PDO::PARAM_STR);

        if (!$query->execute()) { /* Could not execute query */ }
    }

    /**
     * Removes the client from the connected clients list.
     * @param int $id
     * @return void
     */
    public static function disconnected(int $id): void
    {
        $database = new Connect();
        $query = $database->connect()->prepare('DELETE FROM connected_clients WHERE socket = :socket');

        $query->bindParam(':socket', $id, PDO::PARAM_INT);

        if (!$query->execute()) { /* Could not execute query */ }
    }

    /**
     * Send message to other connected user.
     * @param string $chat
     * @param string $from
     * @param string $to
     * @param string $msg
     * @return void
     */
    public static function sendMessage(string $chat, string $from, string $to, string $msg): void
    {
        $database = new Connect();
        $query = $database->connect()->prepare('SELECT socket FROM connected_clients WHERE username = :username;');

        $query->bindParam(':username', $to, PDO::PARAM_STR);

        $to_uid = '';

        if ($query->execute()) {
            $to_uid = $query->fetchColumn();
        }

        $database = new Connect();
        $query = $database->connect()->prepare('INSERT INTO messages (chat_id, username, message) VALUES (:chat, :username, :message);');

        $query->bindParam(':message', $msg, PDO::PARAM_STR);
        $query->bindParam(':chat', $chat, PDO::PARAM_STR);
        $query->bindParam(':username', $from, PDO::PARAM_STR);

        if ($query->execute()) {
            if ($to_uid) {
                ChatServer::pushMessage($to_uid, $from, $msg);
            }
        }
    }
}