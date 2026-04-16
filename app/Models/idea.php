<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Idea extends Database {

    public function crear($usuario_id, $titulo, $descripcion, $sector) {
        $sql = "INSERT INTO ideas (usuario_id, titulo, descripcion, sector) 
                VALUES (:usuario_id, :titulo, :descripcion, :sector)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':sector', $sector);
        return $stmt->execute();
    }

    public function listarPorUsuario($usuario_id) {
        $sql = "SELECT * FROM ideas WHERE usuario_id = :usuario_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}