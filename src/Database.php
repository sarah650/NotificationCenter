<?php

class Database{

    private PDO $conn;

    public function __construct(string $host, string $dbName, string $username, string $password) {
        $dsn = "mysql:host=$host;dbname=$dbName";
        try {
          $this->conn = new PDO($dsn, $username, $password);
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
          throw new PDOException($e->getMessage());
        }
      }
    
    public function getConn(): PDO {
    return $this->conn;
    }
}
