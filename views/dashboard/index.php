<?php
/**
 * TECNOSIA - Front Controller
 */
session_start();

// Depuración de errores para tu entorno LAMP
ini_set('display_errors', 1);
error_reporting(E_ALL);

// AUTOLOAD: Carga automática de clases
spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', '/', $class);
    $file = dirname(__DIR__) . '/' . str_replace('App/', 'app/', $classPath) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Router;
$router = new Router();

// DEFINICIÓN DE RUTAS (Asegúrate que los nombres de Controller coincidan con tus archivos)
$router->get('/', 'DashboardController@index');
$router->get('dashboard', 'DashboardController@index');

// Rutas de Autenticación
$router->get('login', 'AuthController@mostrarLogin');
$router->post('login', 'AuthController@procesarLogin');
$router->get('registro', 'AuthController@mostrarRegistro');
$router->post('registro', 'AuthController@procesarRegistro');
$router->get('logout', 'AuthController@logout');

// Rutas de Ideas (CRUD para el Avance 3)
$router->get('ideas', 'DashboardController@index'); 
$router->post('ideas/crear', 'DashboardController@crear');
$router->get('ideas/eliminar', 'DashboardController@eliminar');

$router->resolve();