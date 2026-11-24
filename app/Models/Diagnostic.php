<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Diagnostic
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Busca el diagnóstico asociado a una hipótesis
     */
    public function findByIdeaId($ideaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM diagnostico WHERE idea_id = :idea_id LIMIT 1");
        $stmt->execute(['idea_id' => $ideaId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            // Decodificamos el JSON nativo de la DB a un array de PHP
            return json_decode($row['result_json'], true);
        }
        return null;
    }

    /**
     * Guarda o actualiza el diagnóstico en formato JSON
     */
    public function store($ideaId, $resultArray)
    {
        // Primero verificamos si ya existe uno para actualizarlo (Caso de prueba CP-05)
        $stmt = $this->db->prepare("SELECT id FROM diagnostico WHERE idea_id = :idea_id");
        $stmt->execute(['idea_id' => $ideaId]);
        $exists = $stmt->fetch();

        $jsonRaw = json_encode($resultArray, JSON_UNESCAPED_UNICODE);

        if ($exists) {
            $stmt = $this->db->prepare("UPDATE diagnostico SET result_json = :json WHERE idea_id = :idea_id");
            return $stmt->execute(['json' => $jsonRaw, 'idea_id' => $ideaId]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO diagnostico (idea_id, result_json) VALUES (:idea_id, :json)");
            return $stmt->execute(['idea_id' => $ideaId, 'json' => $jsonRaw]);
        }
    }
}
