<?php

namespace Api\Config;

/**
 * A connector for the databse
 */
class DatabaseConnector{

    private $dbConnection = null;

    public function __construct()
    {
        $host = "localhost";
        $port = "3306";
        $db   = "cytech";
        $user = "cytech";
        $pass = "cytech@1234!";

        try {
            $this->dbConnection = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            echo "DB Connection Error: " . $e->getMessage();
            exit();
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}
?>