<?php

namespace App\Controllers;

use App\Models\IdeaModel;
use App\Models\AIDiagnostic;

class IdeaController
{
    private $ideaModel;
    private $diagnosticModel;

    public function __construct()
    {
        $this->ideaModel = new IdeaModel();
        $this->diagnosticModel = new AIDiagnostic();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    /**
     * Muestra el Tablero con todas las ideas y sus diagnósticos.
     */
    public function dashboard()
    {
        // Traemos todas las ideas (bypass de usuario para pruebas)
        $todasLasIdeas = $this->ideaModel->findAll(); 

        $listaFinal = [];
        foreach ($todasLasIdeas as $idea) {
            // Buscamos si esta idea ya tiene diagnóstico en la DB
            $diagnostico = $this->diagnosticModel->findByIdeaId($idea['id']);
            $idea['ai_data'] = $diagnostico ? $diagnostico : null; 
            $listaFinal[] = $idea;
        }

        $viewData = [
            'titulo_pagina' => 'Mi Tablero de Innovación - TECNOSIA',
            'ideas'         => $listaFinal 
        ];
        
        extract($viewData);

        require __DIR__ . '/../../views/layouts/header.php';
        require __DIR__ . '/../../views/dashboard/ideas.php';
        require __DIR__ . '/../../views/layouts/footer.php';
    }

    public function create()
    {
        $viewData = ['titulo_pagina' => 'Registrar Nueva Hipótesis'];
        extract($viewData);
        require __DIR__ . '/../../views/layouts/header.php';
        require __DIR__ . '/../../views/dashboard/create.php';
        require __DIR__ . '/../../views/layouts/footer.php';
    }

    /**
     * Procesa el guardado de la idea y dispara la IA.
     */
    public function store()
    {
        $data = [
            'user_id'     => 1, 
            'titulo'      => $_POST['titulo'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'sector'      => $_POST['sector'] ?? 'General'
        ];

        // 1. Guardar la idea en la tabla 'ideas'
        $ideaId = $this->ideaModel->store($data);

        if ($ideaId) {
            // 2. Llamar a la IA para generar el diagnóstico
            $ai = new AIController();
            $resultadoAI = $ai->generateDiagnostic($ideaId);
            
            // Redirigir con bandera de éxito (puedes añadir error_ai si falla)
            $status = $resultadoAI ? 'success=1' : 'error_ai=1';
            header('Location: ' . \URL_BASE . 'dashboard?' . $status);
        } else {
            die("Error crítico: No se pudo guardar la idea en la base de datos.");
        }
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->ideaModel->delete($id);
            header('Location: ' . \URL_BASE . 'dashboard?deleted=1');
        }
        exit;
    }
}