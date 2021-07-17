<?php declare(strict_types=1);

namespace Database;

use PDO;
use PDOException;

/**
 * Class Connect
 * @package Database
 * @author Mark Vogelzang
 */
class Connect
{
    private string $hostname = 'sql.nickdejager.nl';
    private int $port = 5432;
    private string $username = 'chatapp';
    private string $password = 'pizza';
    private string $database = 'pizza';

    /**
     * Returns a PDO connection.
     * @return PDO
     */
    public function connect(): PDO
    {
        try {
            $data_source = 'pgsql:host=' . $this->hostname . ';port=' . $this->port . ';dbname=' . $this->database . ';';

            return new PDO($data_source, $this->username, $this->password, [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_EMULATE_PREPARES => 1,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            echo 'PGSQL Error > ' . $e->getMessage();
        }
    }
}