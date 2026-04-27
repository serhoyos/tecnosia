<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class AIDiagnostic
{
    private $db;
    private $table = 'ai_diagnostics';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Guarda el diagnóstico generado por la IA.
     */
    public function store($data)
    {
        $sql = "INSERT INTO {$this->table} (idea_id, raw_response, coherence_analysis, risks_identified, focus_suggestions) 
                VALUES (:idea_id, :raw_response, :coherence_analysis, :risks_identified, :focus_suggestions)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'idea_id'            => $data['idea_id'],
            'raw_response'       => $data['raw_response'],
            'coherence_analysis' => $data['coherence_analysis'],
            'risks_identified'   => $data['risks_identified'],
            'focus_suggestions'  => $data['focus_suggestions']
        ]);
    }

    /**
     * Busca el diagnóstico asociado a una idea.
     */
    public function findByIdeaId($ideaId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE idea_id = :idea_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['idea_id' => $ideaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}