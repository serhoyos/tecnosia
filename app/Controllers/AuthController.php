<?php
namespace App\Controllers;

class AuthController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . \URL_BASE . 'dashboard');
            exit;
        }

        $viewData = ['titulo_pagina' => 'Iniciar Sesión - TECNOSIA'];
        require __DIR__ . '/../../views/layouts/header.php';
        require __DIR__ . '/../../views/auth/login.php';
        require __DIR__ . '/../../views/layouts/footer.php';
    }

    public function showRegister()
    {
        $viewData = ['titulo_pagina' => 'Crear Cuenta - TECNOSIA'];
        require __DIR__ . '/../../views/layouts/header.php';
        require __DIR__ . '/../../views/auth/registro.php';
        require __DIR__ . '/../../views/layouts/footer.php';
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Simulación de login para pruebas
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = 'Usuario Prueba';
        header('Location: ' . \URL_BASE . 'dashboard');
        exit;
    }

    public function register()
    {
        // Por ahora, solo te redirigimos al login tras "registrarte"
        header('Location: ' . \URL_BASE . 'login?registered=1');
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: ' . \URL_BASE . 'login');
        exit;
    }
}