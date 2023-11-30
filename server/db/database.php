<?php

class Database {
    private $dbHost;
    private $dbPort;
    private $dbName;
    private $dbUser;
    private $dbPassword;
    private $conn;

    public function __construct() {
        $this->dbHost = getenv('DB_HOST') ?: 'localhost';  
        $this->dbPort = getenv('DB_PORT') ?: 3306;        
        $this->dbName = getenv('DB_DATABASE') ?: 'book_db';
        $this->dbUser = getenv('DB_USERNAME') ?: 'root';
        $this->dbPassword = getenv('DB_PASSWORD') ?: 'admin';
    }

    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'mysql:host=' . $this->dbHost . ';port=' . $this->dbPort . ';dbname=' . $this->dbName;
            $this->conn = new PDO($dsn, $this->dbUser, $this->dbPassword);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }

        return $this->conn;
    }
}

?>
