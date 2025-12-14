<?php
require_once __DIR__ . '/../config/Database.php';

class Model {
    protected PDO $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
}
