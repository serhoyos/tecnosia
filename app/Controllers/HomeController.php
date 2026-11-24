<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Iniciamos la sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si el usuario ya está logueado, lo enviamos directo a su Tablero
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . \URL_BASE . 'dashboard');
            exit;
        }

        // Si no está logueado, le mostramos la Landing Page
        require __DIR__ . '/../../views/home/index.php';
    }
}