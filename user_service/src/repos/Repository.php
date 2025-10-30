<?php

namespace NewCo\UserService\repos;

//require_once __DIR__ . '/../Logger/Log.php';

use PDO;
use PDOException;

//use src\Logger\Log as Log;

/**
 * An example of a base class to reduce database connectivity configuration for each repository subclass.
 */
class Repository
{
    protected PDO $pdo;
    private string $hostname;
    private string $username;
    private string $databaseName;
    private string $databasePassword;
    private string $charset;
    private string $port;

    public function __construct()
    {
        $this->hostname = 'localhost';
        $this->username = 'root';
        $this->databaseName = 'photo_storage';
        $this->databasePassword = '';// Make sure to change this to your own password
        $this->charset = 'utf8mb4';
        $this->port = '3306';

        //$commands = file_get_contents(__DIR__ . '/../../database/schema.sql');
        $dsn = "mysql:host=$this->hostname;port=$this->port;dbname=$this->databaseName;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->databasePassword, $options);


        } catch (PDOException $e) {
            //Log::error($e->getMessage());
            throw $e;
        }


    }
}
