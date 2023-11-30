<?php

class Bookmark {
    // Database connection and table name
    private $conn;
    private $dbTable = 'bookmarks';

    // Bookmark properties
    public $id;
    public $url;
    public $title;
    public $dateAdded;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new bookmark
    public function create() {
        $query = "INSERT INTO " . $this->dbTable . " (url, title, date_added) VALUES (:url, :title, NOW())";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->title = htmlspecialchars(strip_tags($this->title));

        // Bind data
        $stmt->bindParam(":url", $this->url);
        $stmt->bindParam(":title", $this->title);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all bookmarks
    public function readAll() {
        $query = "SELECT id, url, title, date_added FROM " . $this->dbTable . " ORDER BY date_added DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Read one bookmark
    public function readOne() {
        $query = "SELECT id, url, title, date_added FROM " . $this->dbTable . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->url = $row['url'];
            $this->title = $row['title'];
            $this->dateAdded = $row['date_added'];
            return true;
        }
        return false;
    }

    // Update a bookmark
    public function update() {
        $query = "UPDATE " . $this->dbTable . " SET url = :url, title = :title WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(":url", $this->url);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete a bookmark
    public function delete() {
        $query = "DELETE FROM " . $this->dbTable . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>
