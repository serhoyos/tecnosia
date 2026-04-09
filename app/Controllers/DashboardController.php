<?php
namespace App\Controllers;

class DashboardController {
    public function index() {
        // Protección de ruta: Si no hay sesión, mandamos al login
        if (!isset($_SESSION['user_id'])) {
            header("Location: /tecnosia/login");
            exit();
        }

        // Cargamos la vista del dashboard
        require_once dirname(__DIR__, 2) . '/views/dashboard.php';
    }
}