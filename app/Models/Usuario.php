<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Usuario extends Database {
    
    public function __construct() {
        parent::__construct(); // Inicializa la conexión de la clase padre (Database)
    }

    public function registrar($nombre, $email, $password) {
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
        $stmt = $this->conn->prepare($sql);
        
        // Encriptamos la contraseña con BCRYPT (Estándar de seguridad profesional)
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        
        return $stmt->execute();
    }

    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve un array con los datos o false
}
}