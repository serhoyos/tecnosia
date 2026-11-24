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

    /**
     * ¡AÑADIDO! Trae únicamente las ideas registradas por un usuario específico
     */
    public function findByUser($user_id)
    {
        // Usamos LEFT JOIN para traer la idea junto con su diagnóstico (si existe)
        $sql = "SELECT i.*, d.result_json 
                FROM ideas i 
                LEFT JOIN diagnostico d ON i.id = d.idea_id 
                WHERE i.user_id = :user_id 
                ORDER BY i.id DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

    public function findById($id, $userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data, $userId)
    {
        $sql = "UPDATE {$this->table} 
                SET titulo = :titulo, 
                    descripcion = :descripcion, 
                    sector = :sector 
                WHERE id = :id AND user_id = :user_id";
                
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'titulo'      => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'sector'      => $data['sector'],
            'id'          => $id,
            'user_id'     => $userId
        ]);
    }

    public function delete($id, $userId)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }
}