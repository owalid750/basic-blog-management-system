<?php

// Class to Handle DB Connection
class Database
{
    private $dsn = "mysql:host=localhost;dbname=blog";
    private $username = DB_USER;
    private $password = DB_PASS;
    private $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }
}

// $testConnection = new Database();

// var_export($testConnection->connect());
