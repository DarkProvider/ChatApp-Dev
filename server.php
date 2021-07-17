<?php declare(strict_types=1);

use Server\ChatServer;

require_once __DIR__ . '/vendor/autoload.php';

$chat_server = new ChatServer('172.17.0.2', 9501);

$chat_server::start();