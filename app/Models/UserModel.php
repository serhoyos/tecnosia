<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class UserModel extends Database {
    
    public function __construct() {
        // Inicializa la conexión heredada de la clase padre (Database)
        parent::__construct(); 
    }

    /**
     * Registra un nuevo usuario en la base de datos con contraseña cifrada
     */
    public function registrar($nombre, $email, $password) {
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
        $stmt = $this->getConnection()->prepare($sql);
        
        // Encriptamos la contraseña con BCRYPT (Estándar de seguridad profesional)
        // Esto cumple con el requerimiento de seguridad de datos sensibles
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        
        return $stmt->execute();
    }

    /**
     * Busca un usuario por su correo electrónico para validación de login
     */
    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        // Devuelve un array asociativo con los datos o false si no existe
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
}