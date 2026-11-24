<?php

namespace App\Controllers;

use App\Models\IdeaModel;
use App\Models\Diagnostic;
use App\Services\IdeaEvaluator;

class IdeaController
{
    private $ideaModel;
    private $diagnosticModel;

    public function __construct()
    {
        $this->ideaModel = new IdeaModel();
        $this->diagnosticModel = new Diagnostic();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    
    private function requireUser()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: ' . \URL_BASE . 'login');
            exit;
        }
        return (int) $userId;
    }

    
    private function requireOwnedIdea($id, $userId)
    {
        $idea = $this->ideaModel->findById($id, $userId);
        if (!$idea) {
            http_response_code(404);
            exit('Idea no encontrada.');
        }
        return $idea;
    }

    
    public function dashboard()
    {
        $userIdLogueado = $_SESSION['user_id'] ?? null;

        if (!$userIdLogueado) {
            header('Location: ' . \URL_BASE . 'login');
            exit;
        }

        // Traemos las ideas del usuario autenticado
        $todasLasIdeas = $this->ideaModel->findByUser($userIdLogueado); 

        $listaFinal = [];
        foreach ($todasLasIdeas as $idea) {
            // Buscamos si esta idea ya tiene su diagnóstico estructurado en la DB
            $diagnostico = $this->diagnosticModel->findByIdeaId($idea['id']);
            $idea['diagnostic_data'] = $diagnostico ? $diagnostico : null; 
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
        $this->requireUser();
        $viewData = ['titulo_pagina' => 'Registrar Nueva Idea de Negocio'];
        extract($viewData);
        require __DIR__ . '/../../views/layouts/header.php';
        require __DIR__ . '/../../views/dashboard/create.php';
        require __DIR__ . '/../../views/layouts/footer.php';
    }

    
    public function store()
    {
        $userIdLogueado = $_SESSION['user_id'] ?? null;

        if (!$userIdLogueado) {
            header('Location: ' . \URL_BASE . 'login');
            exit;
        }

        $data = [
            'user_id'     => $userIdLogueado,
            'titulo'      => $_POST['titulo'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'sector'      => $_POST['sector'] ?? 'General'
        ];

        // 1. Guardar la idea en la tabla 'ideas'
        $ideaId = $this->ideaModel->store($data);

        if ($ideaId) {
            // 2. Ejecutar el Motor de Evaluación Interno Basado en Reglas
            $diagnosticoProcesado = IdeaEvaluator::evaluate($data);
            
            // 3. Almacenar el diagnóstico JSON en la base de datos
            $this->diagnosticModel->store($ideaId, $diagnosticoProcesado);
            
            header('Location: ' . \URL_BASE . 'dashboard?success=1');
        } else {
            die("Error crítico: No se pudo guardar la idea en la base de datos.");
        }
        exit;
    }

    
    public function edit()
    {
        $userId = $this->requireUser();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . \URL_BASE . 'dashboard');
            exit;
        }

        // Buscamos la idea en la base de datos
        $idea = $this->requireOwnedIdea($id, $userId);

        $viewData = [
            'titulo_pagina' => 'Editar Idea de Negocio - TECNOSIA',
            'idea' => $idea
        ];
        
        extract($viewData);
        require __DIR__ . '/../../views/layouts/header.php';
        require __DIR__ . '/../../views/dashboard/edit.php';
        require __DIR__ . '/../../views/layouts/footer.php';
    }

    
    public function update()
    {
        $userId = $this->requireUser();
        $id = $_POST['id'] ?? null;

        if ($id) {
            $this->requireOwnedIdea($id, $userId);
            $data = [
                'titulo'      => $_POST['titulo'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'sector'      => $_POST['sector'] ?? 'General'
            ];

            // 1. Actualizamos la información en la tabla 'ideas'
            if (!$this->ideaModel->update($id, $data, $userId)) {
                http_response_code(500);
                exit('No se pudo actualizar la idea.');
            }

            // 2. Ejecutamos nuevamente el Motor de Evaluación con los nuevos datos
            $nuevoDiagnostico = IdeaEvaluator::evaluate($data);

            // 3. Actualizamos el diagnóstico JSON en la base de datos usando store() (Upsert)
            $this->diagnosticModel->store($id, $nuevoDiagnostico);

            header('Location: ' . \URL_BASE . 'dashboard?updated=1');
        } else {
            header('Location: ' . \URL_BASE . 'dashboard?error=1');
        }
        exit;
    }

    public function delete()
    {
        // 1. Aseguramos mantener viva la sesión antes de hacer cualquier cosa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Comprobamos autenticación y propiedad antes de eliminar
        $userId = $this->requireUser();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->requireOwnedIdea($id, $userId);
            $this->ideaModel->delete($id, $userId);
        }

        // 3. Redirigimos de vuelta de forma segura
        $url = defined('URL_BASE') ? \URL_BASE : '/';
        header('Location: ' . $url . 'dashboard');
        exit;
    }
}