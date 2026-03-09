<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$route = $_GET['route'] ?? 'home';

switch ($route) {

    case 'register':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;

    case 'login':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'dashboard':
        require_once '../app/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    default:
        echo "<h1>Bienvenido a Tecnosia</h1>";
        echo "<a href='?route=register'>Registrarse</a><br>";
        echo "<a href='?route=login'>Iniciar sesión</a>";
}
