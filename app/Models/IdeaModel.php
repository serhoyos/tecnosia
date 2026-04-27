<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class IdeaModel
{
    private $db;
    private $table = 'ideas';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function store($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, titulo, descripcion, sector) 
                VALUES (:user_id, :titulo, :descripcion, :sector)";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute($data);
        return $success ? $this->db->lastInsertId() : false;
    }

    public function findAll()
    {
        // Traemos todas las ideas ordenadas por la más reciente
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}