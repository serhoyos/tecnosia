<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost';
    private $db_name = 'tecnosia_db';
    private $username = 'serhoyos'; 
    private $password = 'Akibaldo49*'; 
    protected $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}